<?php
declare(strict_types=1);
/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class ColumnValidation extends AbstractFieldArray
{
    /**
     * Create columns for configuration
     *
     * @return void
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn('country_id',['label' => __('Country codes')]);
        $this->addColumn('validation_name_regex',['label' => __('Validation key'), 'class' => 'required-entry']);
        $this->addColumn('regex',['label' => __('Regex'),'class' => 'required-entry']);
        $this->addColumn('message',['label' => __('Message'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add validation');
    }
}
