<?php
/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config implements ArgumentInterface
{
    const M2S_ENABLE_MODULE_PATH = 'm2s/general/enabled';

    const M2S_ADVANCED_SHIPPING_ADDRESS_PATH = 'm2s/advanced/shipping_address_path';

    const M2S_ADVANCED_BILLING_ADDRESS_PATH = 'm2s/advanced/billing_address_path';

    const M2S_VALIDATION_JSON_REGEX_PATH = 'm2s/general/validation_json_regex';

    const M2S_CUSTOM_FIELDS_VALIDATION_JSON_PATH = 'm2s/general/fields_validation';

    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct( 
        protected ScopeConfigInterface $scopeConfig 
    ) { 
        $this->scopeConfig = $scopeConfig; 
    } 
    
    /**
     * isEnabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    { 
        return (bool)$this->scopeConfig->getValue( 
        self::M2S_ENABLE_MODULE_PATH, 
        ScopeInterface::SCOPE_STORE); 
    } 
    
    /**
     * getAdvancedShippingAddressPath
     *
     * @return string
     */
    public function getAdvancedShippingAddressPath(): string
    { 
        return $this->scopeConfig->getValue( 
        self::M2S_ADVANCED_SHIPPING_ADDRESS_PATH, 
        ScopeInterface::SCOPE_STORE); 
    } 
    
    /**
     * getAdvancedBillingAddressPath
     *
     * @return string
     */
    public function getAdvancedBillingAddressPath(): string
    { 
        return $this->scopeConfig->getValue( 
        self::M2S_ADVANCED_BILLING_ADDRESS_PATH, 
        ScopeInterface::SCOPE_STORE); 
    }
     
     /**
      * getValidationJsonRegex
      *
      * @return array
      */
     public function getValidationJsonRegex(): array
     { 
        $validations = $this->scopeConfig->getValue( 
            self::M2S_VALIDATION_JSON_REGEX_PATH, 
            ScopeInterface::SCOPE_STORE);
        $validations = is_array($validations) ? $validations : json_decode($validations, true);
        $result = [];
        foreach ($validations as $validation) {
        $result[] = [
            'country_id' => $validation['country_id'],
            'validation_name_regex' => $validation['validation_name_regex'],
            'regex' => $validation['regex'],
            'message' => $validation['message']
        ];
    }

    return $result;
}

    public function getCustomFieldsValidationJson(): array
    { 
        $reasons = $this->scopeConfig->getValue( 
            self::M2S_CUSTOM_FIELDS_VALIDATION_JSON_PATH, 
            ScopeInterface::SCOPE_STORE);

            if (!$reasons) {
                return [];
            }

        return array_map(function ($reason) {
            return $reason;
        }, is_array($reasons) ? $reasons : json_decode($reasons, true));
   } 
}
