<?php
declare(strict_types=1);
/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;
use M2S\AdvancedValidator\ViewModel\Config;

class AddressType extends Select
{
    /**
     * @return string
     */
    protected function _toHtml(): string
    {
        $this->addOption(Config::ALL_FORMS, __('All forms'));
        $this->addOption(Config::SHIPPING_FORMS,  __('Shipping Address'));
        $this->addOption(Config::BILLING_FORMS, __('Billing Address'));

        return parent::_toHtml();
    }

    /**
     * Sets name for input element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Set "id" for <select> element
     *
     * @param $value
     * @return $this
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }
}
