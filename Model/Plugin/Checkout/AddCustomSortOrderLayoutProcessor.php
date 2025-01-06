<?php
declare(strict_types=1);
/**
 * Copyright © 2025
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\Model\Plugin\Checkout;

use M2S\AdvancedValidator\ViewModel\Config;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\ArrayManager;
use M2S\AdvancedValidator\Model\CustomFieldProcessor;

class AddCustomSortOrderLayoutProcessor implements LayoutProcessorInterface
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
                $this->config->getCustomSortOrderJson(),
                CustomFieldProcessor::SORT_ORDER_FIELD
            );
            $this->customFieldProcessor->implementBillingAddress(
                $jsLayout,
                $this->config->getCustomSortOrderJson(),
                CustomFieldProcessor::SORT_ORDER_FIELD
            );
        }

        return $jsLayout;
    }
}
