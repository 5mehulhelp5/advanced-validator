<?php
declare(strict_types=1);
/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class ColumnFieldsValidation extends AbstractFieldArray
{
    /**
     * @return void
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn('field_code',['label' => __('Field code'), 'class' => 'required-entry']);
        $this->addColumn('validation_name',['label' => __('Validation key'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add validation');
    }
}
