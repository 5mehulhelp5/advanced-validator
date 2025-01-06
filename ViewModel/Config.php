<?php
declare(strict_types=1);
/**
 * Copyright © 2025
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Serialize\Serializer\Json;

class Config implements ArgumentInterface
{
    public const int ALL_FORMS = 0;

    public const int SHIPPING_FORMS = 1;

    public const int BILLING_FORMS = 2;

    public const string BILLING_ADDRESS_PAYMENT_METHODS_PATH = 'components/checkout/children/steps/children/billing-step/children/payment/children/payments-list/children';

    public const string COMPONENT_PATH = 'components/checkout/children/steps/children';

    protected const string M2S_ENABLE_MODULE_PATH = 'm2s/general/enabled';

    protected const string M2S_ADVANCED_SHIPPING_ADDRESS_PATH = 'm2s/advanced/shipping_address_path';

    protected const string M2S_ADVANCED_BILLING_ADDRESS_PATH = 'm2s/advanced/billing_address_path';

    protected const string M2S_VALIDATION_JSON_REGEX_PATH = 'm2s/general/validation_json_regex';

    protected const string M2S_CUSTOM_FIELDS_VALIDATION_JSON_PATH = 'm2s/general/fields_validation';

    protected const string M2S_CUSTOM_FIELDS_SORT_ORDER_JSON_PATH = 'm2s/sort/sort_order_configuration';

    protected const string M2S_CUSTOM_FIELDS_LABEL_JSON_PATH = 'm2s/label/label_configuration';

    protected const string M2S_CUSTOM_FIELDS_CLASS_JSON_PATH = 'm2s/class/class_configuration';

    protected const string CHECKOUT_DISPLAY_BILLING_ADDRESS = 'checkout/options/display_billing_address_on';

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Json $serializer
     */
    public function __construct(
        protected ScopeConfigInterface $scopeConfig,
        protected Json $serializer
    ) {
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
     * @return string
     */
    public function getValidationJsonRegex(): string
    {
        $result = [];

        $validations = $this->scopeConfig->getValue(
            self::M2S_VALIDATION_JSON_REGEX_PATH,
            ScopeInterface::SCOPE_STORE);

        if ($validations) {
            $validations = is_array($validations) ? $validations : $this->serializer->unserialize($validations);
            foreach ($validations as $validation) {
                $result[] = [
                    'country_id' => $validation['country_id'],
                    'validation_name_regex' => $validation['validation_name_regex'],
                    'regex' => $validation['regex'],
                    'message' => $validation['message']
                ];
            }
        }

        return $this->serializer->serialize($result);
    }

    /**
     * @return array
     */
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
        }, is_array($values) ? $values : $this->serializer->unserialize($values));
    }

    /**
     * @return array
     */
    public function getCustomSortOrderJson(): array
    {
        $values = $this->scopeConfig->getValue(
            self::M2S_CUSTOM_FIELDS_SORT_ORDER_JSON_PATH,
            ScopeInterface::SCOPE_STORE);

        if (!$values) {
            return [];
        }

        return array_map(function ($value) {
            return $value;
        }, is_array($values) ? $values : $this->serializer->unserialize($values));
    }

    /**
     * @return array
     */
    public function getCustomLabelJson(): array
    {
        $values = $this->scopeConfig->getValue(
            self::M2S_CUSTOM_FIELDS_LABEL_JSON_PATH,
            ScopeInterface::SCOPE_STORE);

        if (!$values) {
            return [];
        }

        return array_map(function ($value) {
            return $value;
        }, is_array($values) ? $values : $this->serializer->unserialize($values));
    }

    /**
     * @return array
     */
    public function getCustomClassJson(): array
    {
        $values = $this->scopeConfig->getValue(
            self::M2S_CUSTOM_FIELDS_CLASS_JSON_PATH,
            ScopeInterface::SCOPE_STORE);

        if (!$values) {
            return [];
        }

        return array_map(function ($value) {
            return $value;
        }, is_array($values) ? $values : $this->serializer->unserialize($values));
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
