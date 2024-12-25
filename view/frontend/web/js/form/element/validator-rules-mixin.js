/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */

define([
    'jquery',
    'underscore',
    'Magento_Ui/js/lib/validation/utils',
    'validationList',
    'mage/translate',
    'mage/utils/wrapper',
    'Magento_Ui/js/lib/validation/rules'
], function ($, _, utils, validationList, $t, wrapper, rulesList) {
    'use strict';

    return function () {
        /**
         * Validates provided value be the specified rule.
         *
         * @param {String} id - Rule identifier.
         * @param {*} value - Value to be checked.
         * @param {*} [params]
         * @param {*} additionalParams - additional validation params set by method caller
         * @param countryId
         * @returns {Object}
         */
        function validate(id, value, params, additionalParams, countryId) {
            var rule,
                message,
                valid,
                result = {
                    rule: id,
                    passed: true,
                    message: ''
                };

            if (_.isObject(params)) {
                message = params.message || '';
            }

            if (!rulesList[id]) {
                return result;
            }

            rule    = rulesList[id];
            message = message || rule.message;
            valid   = rule.handler(value, params, additionalParams, countryId);

            if (!valid) {
                params = Array.isArray(params) ?
                    params :
                    [params];

                if (typeof message === 'function') {
                    message = message.call(rule);
                }

                message = params.reduce(function (msg, param, idx) {
                    return msg.replace(new RegExp('\\{' + idx + '\\}', 'g'), param);
                }, message);

                result.passed = false;
                result.message = message;
            }

            return result;
        }

        /**
         * New param added (countryId) to original function
         * Validates provided value by a specified set of rules.
         *
         * @param {(String|Object)} rules - One or many validation rules.
         * @param {*} value - Value to be checked.
         * @param {*} additionalParams - additional validation params set by method caller
         * @param countryId
         * @returns {Object}
         */
        function validator(rules, value, additionalParams, countryId) {
            var result;

            if (typeof rules === 'object') {
                result = {
                    passed: true
                };

                _.every(rules, function (ruleParams, id) {
                    if (ruleParams.validate || ruleParams !== false || additionalParams) {
                        result = validate(id, value, ruleParams, additionalParams, countryId);

                        return result.passed;
                    }

                    return true;
                });

                return result;
            }

            return validate.apply(null, arguments);
        }

        validator.addRule = function (id, handler, message) {
            rulesList[id] = {
                handler: handler,
                message: message,
            };
        };

        validationList().forEach(function(item) {
            const regex = new RegExp(item.regex, "i");
            validator.addRule(
                item.validation_name_regex,
                function (value, params, additionalParams, countryId) {
                    if (item.country_id.includes(countryId) || !item.country_id) {
                        return utils.isEmpty(value) || regex.test(value);
                    }

                    return true;
                },
                $t(`${item.message}`)
            );

        })

        return validator;
    };
});
