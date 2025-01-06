<?php
declare(strict_types=1);
/**
 * Copyright © 2025
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\Model;

use M2S\AdvancedValidator\ViewModel\Config;
use Magento\Framework\Stdlib\ArrayManager;

class CustomFieldProcessor
{
    public const string SORT_ORDER_FIELD = 'sortOrder';

    public const string VALIDATION_FIELD = 'validation';

    public const string LABEL_FIELD = 'label';

    public const string ADDITIONAL_CLASSES_FIELD = 'additionalClasses';

    /**
     * @var ArrayManager
     */
    protected ArrayManager $arrayManager;

    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @param ArrayManager $arrayManager
     * @param Config $config
     */
    public function __construct(
        ArrayManager $arrayManager,
        Config $config
    ) {
        $this->arrayManager = $arrayManager;
        $this->config = $config;
    }

    /**
     * Adds custom validation to the fields.
     *
     * @param array $customField
     * @param array $validationArray
     * @return array
     */
    public function addCustomValidation(array $customField, array $validationArray): array
    {
        return array_merge($validationArray, [
            $customField['validation_name'] => !!$customField['validation_value']
        ]);
    }

    /**
     * Adds custom sort order to the fields.
     *
     * @param array $customField
     * @return int
     */
    public function addCustomSortOrder(array $customField): int
    {
        return $customField['sort_order'];
    }

    /**
     * Adds custom label to the fields.
     *
     * @param array $customField
     * @return string
     */
    public function addCustomAdditionalClass(array $customField): string
    {
        return $customField['additional_classes'];
    }

    /**
     * Adds custom label to the fields.
     *
     * @param array $customField
     * @return string
     */
    public function addCustomLabel(array $customField): string
    {
        return $customField['label'];
    }

    /**
     * Applies custom checkout options based on address type.
     *
     * @param array $customFields
     * @param array $fields
     * @param int $addressType
     * @param string $type
     * @return void
     */
    public function applyCustomFieldSettings(array $customFields, array &$fields, int $addressType, string $type): void
    {
        foreach ($customFields as $customField) {
            if ($this->isEnabledForAddressType($customField, $addressType)) {
                $fieldCode = $customField['field_code'];

                if ($type === self::VALIDATION_FIELD) {
                    $fields[$fieldCode][self::VALIDATION_FIELD] =
                        $this->addCustomValidation($customField, $fields[$fieldCode][self::VALIDATION_FIELD]);
                } elseif ($type === self::SORT_ORDER_FIELD) {
                    $fields[$fieldCode][self::SORT_ORDER_FIELD] = $this->addCustomSortOrder($customField);
                } elseif ($type === self::LABEL_FIELD) {
                    $fields[$fieldCode][self::LABEL_FIELD] = $this->addCustomLabel($customField);
                } elseif ($type === self::ADDITIONAL_CLASSES_FIELD) {
                    $fields[$fieldCode][self::ADDITIONAL_CLASSES_FIELD] = $this->addCustomAdditionalClass($customField);
                }
            }
        }
    }

    /**
     * Checks if the field should be enabled for the specific address type.
     *
     * @param array $customField
     * @param int $addressType
     * @return bool
     */
    private function isEnabledForAddressType(array $customField, int $addressType): bool
    {
        $configType = $addressType === Config::SHIPPING_FORMS ? Config::SHIPPING_FORMS : Config::BILLING_FORMS;
        return Config::ALL_FORMS === $customField['address_type'] || $configType === $customField['address_type'];
    }

    /**
     * Get payment method billing address path
     *
     * @param array $jsLayout
     * @return array
     */
    public function getPaymentMethods(array $jsLayout): array
    {
        return $this->arrayManager->get(
            Config::BILLING_ADDRESS_PAYMENT_METHODS_PATH,
            $jsLayout
        );
    }

    /**
     * Implement custom changes for shipping address
     *
     * @param array $jsLayout
     * @param array $customFields
     * @param string $type
     * @return array
     */
    public function implementShippingAddress(&$jsLayout, array $customFields, string $type): array
    {
        $shippingForm = Config::COMPONENT_PATH . $this->config->getAdvancedShippingAddressPath();

        if (empty($fields = $this->arrayManager->get($shippingForm, $jsLayout))) {
            return $jsLayout;
        }

        $this->applyCustomFieldSettings($customFields, $fields, Config::SHIPPING_FORMS, $type);
        $jsLayout = $this->arrayManager->replace($shippingForm, $jsLayout, $fields);
        return $jsLayout;
    }

    /**
     * Implement custom changes for billing address
     *
     * @param array $jsLayout
     * @param array $customFields
     * @param string $type
     * @return array
     */
    public function implementBillingAddress(&$jsLayout, array $customFields, string $type): array
    {
        $billingMode = $this->config->getDisplayBillingAddressMode();
        if ($billingMode) {
            $billingForm = Config::COMPONENT_PATH . $this->config->getAdvancedBillingAddressPath();
            if (empty($fields = $this->arrayManager->get($billingForm, $jsLayout))) {
                return $jsLayout;
            }

            $this->applyCustomFieldSettings($customFields, $fields, Config::BILLING_FORMS, $type);
            $jsLayout = $this->arrayManager->replace($billingForm, $jsLayout, $fields);

        } else {
            foreach ($this->getPaymentMethods($jsLayout) as $paymentKey => &$paymentMethod) {
                $paymentPath = Config::BILLING_ADDRESS_PAYMENT_METHODS_PATH .
                    '/' . $paymentKey . '/' . 'children/form-fields/children';
                $fields = &$paymentMethod['children']['form-fields']['children'];
                if ($fields === null) {
                    continue;
                }
                $this->applyCustomFieldSettings($customFields, $fields, Config::BILLING_FORMS, $type);
                $jsLayout = $this->arrayManager->replace($paymentPath, $jsLayout, $fields);
            }
        }
        return $jsLayout;
    }
}
