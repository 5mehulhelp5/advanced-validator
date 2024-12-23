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
    protected const M2S_ENABLE_MODULE_PATH = 'm2s/general/enabled';

    protected const M2S_ADVANCED_SHIPPING_ADDRESS_PATH = 'm2s/advanced/shipping_address_path';

    protected const M2S_ADVANCED_BILLING_ADDRESS_PATH = 'm2s/advanced/billing_address_path';

    protected const M2S_VALIDATION_JSON_REGEX_PATH = 'm2s/general/validation_json_regex';

    protected const M2S_CUSTOM_FIELDS_VALIDATION_JSON_PATH = 'm2s/general/fields_validation';

    protected const CHECKOUT_DISPLAY_BILLING_ADDRESS = 'checkout/options/display_billing_address_on';

    const ALL_FORMS = '0';

    const SHIPPING_FORMS = '1';

    const BILLING_FORMS = '2';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        protected ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::M2S_ENABLE_MODULE_PATH,
            ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getAdvancedShippingAddressPath(): string
    {
        return $this->scopeConfig->getValue(
            self::M2S_ADVANCED_SHIPPING_ADDRESS_PATH,
            ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getAdvancedBillingAddressPath(): string
    {
        return $this->scopeConfig->getValue(
            self::M2S_ADVANCED_BILLING_ADDRESS_PATH,
            ScopeInterface::SCOPE_STORE);
    }

    /**
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
        $values = $this->scopeConfig->getValue(
            self::M2S_CUSTOM_FIELDS_VALIDATION_JSON_PATH,
            ScopeInterface::SCOPE_STORE);

        if (!$values) {
            return [];
        }

        return array_map(function ($value) {
            return $value;
        }, is_array($values) ? $values : json_decode($values, true));
    }


    /**
     * @return mixed
     */
    public function getDisplayBillingAddressMode(): string
    {
        return $this->scopeConfig->getValue(
            self::CHECKOUT_DISPLAY_BILLING_ADDRESS,
            ScopeInterface::SCOPE_STORE);
    }
}
