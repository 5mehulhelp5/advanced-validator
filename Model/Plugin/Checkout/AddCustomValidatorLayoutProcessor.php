<?php
declare(strict_types=1);
/**
 * Copyright © 2025
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\Model\Plugin\Checkout;

use Magento\Framework\Stdlib\ArrayManager;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use M2S\AdvancedValidator\ViewModel\Config;
use Magento\Framework\Serialize\SerializerInterface;
use M2S\AdvancedValidator\Model\CustomFieldProcessor;

class AddCustomValidatorLayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @param Config $config
     * @param ArrayManager $arrayManager
     * @param SerializerInterface $serializeInterface
     * @param CustomFieldProcessor $customFieldProcessor
     */
    public function __construct(
        protected Config $config,
        protected ArrayManager $arrayManager,
        protected SerializerInterface $serializeInterface,
        protected CustomFieldProcessor $customFieldProcessor,
    ) {
    }

    /**
     * Implement custom label to jsLayout
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout): array
    {
        if ($this->config->isEnabled()) {
            $this->customFieldProcessor->implementShippingAddress(
                $jsLayout,
                $this->config->getCustomFieldsValidationJson(),
                CustomFieldProcessor::VALIDATION_FIELD
            );
            $this->customFieldProcessor->implementBillingAddress(
                $jsLayout,
                $this->config->getCustomFieldsValidationJson(),
                CustomFieldProcessor::VALIDATION_FIELD
            );
        }

        return $jsLayout;
    }
}
