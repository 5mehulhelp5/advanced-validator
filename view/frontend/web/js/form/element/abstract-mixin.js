/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
define([
    'ko',
    'Magento_Ui/js/lib/validation/validator'
], function (ko, validator) {

    var mixin = {
        defaults: {
            imports: {
                countryUpdate: '${ $.parentName }.country_id:value'
            }
        },

        /**
         * Validates itself by it's validation rules using validator object.
         * If validation of a rule did not pass, writes it's message to
         * 'error' observable property.
         *
         * @returns {Object} Validate information.
         */
        validate: function () {
            var value = this.value(),
                result = validator(this.validation, value, this.validationParams, this.countryUpdate),
                message = !this.disabled() && this.visible() ? result.message : '',
                isValid = this.disabled() || !this.visible() || result.passed;

            this.error(message);
            this.error.valueHasMutated();
            this.bubble('error', message);

            //TODO: Implement proper result propagation for form
            if (this.source && !isValid) {
                this.source.set('params.invalid', true);
            }

            return {
                valid: isValid,
                target: this
            };
        },
    }
    
    return function (target) {
        return target.extend(mixin);
    }; 
})