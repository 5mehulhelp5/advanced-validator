<?php
declare(strict_types=1);
/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;

class EnabledField extends Select
{

    protected function _toHtml(): string
    {
        $options[] = ['value' => 1, 'label' => __('Yes')];
        $options[] = ['value' => 0, 'label' => __('No')];
        if (!$this->getOptions()) {
            $this->setOptions($options);
        }
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
