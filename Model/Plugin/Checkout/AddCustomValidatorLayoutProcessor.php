<?php
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

    public function __construct(
        protected Config $config,
        protected ArrayManager $arrayManager,
        protected SerializerInterface $serializeInterface
    ) {
        $this->config = $config;
        $this->arrayManager = $arrayManager;
        $this->serializeInterface = $serializeInterface;
    }

    /**
     * 
     */
    public function process($jsLayout): array
    {
        if ($this->config->isEnabled()) {
            $step = 'components/checkout/children/steps/children';
            $shippingForm = $step . $this->config->getAdvancedShippingAddressPath();
    
            if (empty($fields = $this->arrayManager->get($shippingForm, $jsLayout))) {
                return $jsLayout;
            }
    
            $customFields = $this->config->getCustomFieldsValidationJson();
    
            foreach($customFields as $key => $customField) {
                $fieldCode = $customField['field_code'];
                $fields[$fieldCode]['validation'] = array_merge($fields[$fieldCode]['validation'], $this->addCustomValidation($customField));
            }
    
            $jsLayout = $this->arrayManager->replace($shippingForm, $jsLayout, $fields);
        }

        return $jsLayout;
    }

    public function addCustomValidation(array $customField): array
    {
        return [
            $customField['validation_name'] => true
        ];
    }
}