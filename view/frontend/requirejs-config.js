/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */

var config = {
    map: {
        '*': {
            validationList: 'M2S_AdvancedValidator/js/model/validation-list'
        }
    },
    config: {
        mixins: {
            'Magento_Ui/js/lib/validation/validator': {
                'M2S_AdvancedValidator/js/form/element/validator-rules-mixin': true
            },
            'Magento_Ui/js/form/element/abstract': {
                'M2S_AdvancedValidator/js/form/element/abstract-mixin': true
            }
        }
    }
};
