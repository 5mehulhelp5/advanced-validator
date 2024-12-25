<?php
declare(strict_types=1);
/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\Model\Plugin\Checkout;

use Magento\Framework\Stdlib\ArrayManager;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use M2S\AdvancedValidator\ViewModel\Config;
use Magento\Framework\Serialize\SerializerInterface;

class AddCustomValidatorLayoutProcessor implements LayoutProcessorInterface
{
    protected const BILLING_ADDRESS_PAYMENT_METHODS_PATH = 'components/checkout/children/steps/children/billing-step/children/payment/children/payments-list/children';

    /**
     * @param Config $config
     * @param ArrayManager $arrayManager
     * @param SerializerInterface $serializeInterface
     */
    public function __construct(
        protected Config $config,
        protected ArrayManager $arrayManager,
        protected SerializerInterface $serializeInterface
    ) {
    }

    /**
     * @param $jsLayout
     * @return array
     */
    public function process($jsLayout): array
    {
        if ($this->config->isEnabled()) {
            $this->implementShippingAddressValidation($jsLayout);
            $this->implementBillingAddressValidation($jsLayout);
        }

        return $jsLayout;
    }

    /**
     * @param array $customField
     * @return true[]
     */
    public function addCustomValidation(array $customField): array
    {
        return [
            $customField['validation_name'] => !!$customField['validation_value']
        ];
    }

    /**
     * @param $jsLayout
     * @return array
     */
    protected function implementShippingAddressValidation(&$jsLayout): array
    {
        $step = 'components/checkout/children/steps/children';
        $shippingForm = $step . $this->config->getAdvancedShippingAddressPath();

        if (empty($fields = $this->arrayManager->get($shippingForm, $jsLayout))) {
            return $jsLayout;
        }

        $customFields = $this->config->getCustomFieldsValidationJson();

        foreach($customFields as $key => $customField) {
            if ($this->isEnabledForShipping($customField)) {
                $fieldCode = $customField['field_code'];
                $fields[$fieldCode]['validation'] = array_merge($fields[$fieldCode]['validation'], $this->addCustomValidation($customField));
            }
        }

        $jsLayout = $this->arrayManager->replace($shippingForm, $jsLayout, $fields);
        return $jsLayout;
    }


    /**
     * @param $jsLayout
     * @return array
     */
    protected function implementBillingAddressValidation(&$jsLayout): array
    {
        $step = 'components/checkout/children/steps/children';
        $billingMode = $this->config->getDisplayBillingAddressMode();
        $customFields = $this->config->getCustomFieldsValidationJson();
        if ($billingMode) {
            $billingForm = $step . $this->config->getAdvancedBillingAddressPath();
            if (empty($fields = $this->arrayManager->get($billingForm, $jsLayout))) {
                return $jsLayout;
            }

            foreach($customFields as $key => $customField) {
                if ($this->isEnabledForBilling($customField)) {
                    $fieldCode = $customField['field_code'];
                    $fields[$fieldCode]['validation'] = array_merge($fields[$fieldCode]['validation'], $this->addCustomValidation($customField));
                }
            }

            $jsLayout = $this->arrayManager->replace($billingForm, $jsLayout, $fields);
        } else {
            foreach ($this->getPaymentMethods($jsLayout) as $paymentKey => &$paymentMethod) {
                $paymentPath = self::BILLING_ADDRESS_PAYMENT_METHODS_PATH . '/' . $paymentKey . '/' . 'children/form-fields/children';
                $fields = &$paymentMethod['children']['form-fields']['children'];
                if ($fields === null) {
                    continue;
                }

                foreach($customFields as $key => $customField) {
                    if ($this->isEnabledForBilling($customField)) {
                        $fieldCode = $customField['field_code'];
                        $fields[$fieldCode]['validation']
                            = array_merge($fields[$fieldCode]['validation'], $this->addCustomValidation($customField));
                    }
                }

                $jsLayout = $this->arrayManager->replace($paymentPath, $jsLayout, $fields);

            }
        }
        return $jsLayout;
    }


    /**
     * @param array $jsLayout
     * @return array
     */
    private function getPaymentMethods(array $jsLayout): array
    {
        return $this->arrayManager->get(
            self::BILLING_ADDRESS_PAYMENT_METHODS_PATH,
            $jsLayout
        );
    }

    /**
     * @param $config
     * @return bool
     */
    private function isEnabledForShipping($config): bool
    {
        return Config::ALL_FORMS === $config['address_type'] || Config::SHIPPING_FORMS === $config['address_type'];
    }

    /**
     * @param $config
     * @return bool
     */
    private function isEnabledForBilling($config): bool
    {
        return Config::ALL_FORMS === $config['address_type'] || Config::BILLING_FORMS === $config['address_type'];
    }
}
