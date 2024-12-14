/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
define([
    'ko',
    'uiElement',
    '../model/validation-list'
], function (ko, Component, validationList) {
    return Component.extend({
        initialize: function(config) {
            this._super();

            validationList(config);
        }
    })
})