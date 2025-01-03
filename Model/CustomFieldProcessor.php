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
    protected const SORT_ORDER_FIELD = 'sortOrder';

    protected const VALIDATION_FIELD = 'validation';

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
    public function __construct(ArrayManager $arrayManager, Config $config)
    {
        $this->arrayManager = $arrayManager;
        $this->config = $config;
    }

    /**
     * Adds custom validation to the fields.
     *
     * @param array $customField
     * @return array
     */
    public function addCustomValidation(array $customField): array
    {
        return [
            $customField['validation_name'] => !!$customField['validation_value']
        ];
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
     * Applies custom checkout options based on address type.
     *
     * @param array $customFields
     * @param array &$fields
     * @param int $addressType
     * @param string $type (validation or sortOrder)
     * @return void
     */
    public function applyCustomFieldSettings(array $customFields, array &$fields, int $addressType, string $type): void
    {
        foreach ($customFields as $customField) {
            if ($this->isEnabledForAddressType($customField, $addressType)) {
                $fieldCode = $customField['field_code'];

                if ($type === self::VALIDATION_FIELD) {
                    $fields[$fieldCode][self::VALIDATION_FIELD] = array_merge(
                        $fields[$fieldCode][self::VALIDATION_FIELD],
                        $this->addCustomValidation($customField)
                    );
                } elseif ($type === self::SORT_ORDER_FIELD) {
                    $fields[$fieldCode][self::SORT_ORDER_FIELD] = $this->addCustomSortOrder($customField);
                }  elseif ($type === self::SORT_ORDER_FIELD) {
                    $fields[$fieldCode][self::SORT_ORDER_FIELD] = $this->addCustomSortOrder($customField);
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
}
