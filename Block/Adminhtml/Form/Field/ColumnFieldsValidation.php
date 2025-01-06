<?php
declare(strict_types=1);
/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\Block\Adminhtml\Form\Field;

use Magento\Framework\DataObject;
use M2S\AdvancedValidator\Block\Adminhtml\Form\Field\EnabledField;
use M2S\AdvancedValidator\Block\Adminhtml\Form\Field\AddressType;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;

class ColumnFieldsValidation extends AbstractFieldArray
{
    /**
     * Create select options block
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    protected function getAddressTypeSelect(): BlockInterface
    {
            $this->addressType = $this->getLayout()->createBlock(
                AddressType::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        $this->addressType->setClass('customer_group_select admin__control-select');

        return $this->addressType;
    }

    /**
     * Create select options block
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    protected function optionIsEnabled(): BlockInterface
    {
            $this->valdationEnabled = $this->getLayout()->createBlock(
                EnabledField::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        $this->valdationEnabled->setClass('customer_group_select admin__control-select');

        return $this->valdationEnabled;
    }

    /**
     * Create columns for configuration
     *
     * @return void
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn('field_code', [
            'label' => __('Field code'), 'class' => 'required-entry']);
        $this->addColumn('validation_name', [
            'label' => __('Validation Key'), 'class' => 'required-entry']);
        $this->addColumn('validation_value', [
            'label' => __('Validation Enabled'), 'renderer' => $this->optionIsEnabled()]);
        $this->addColumn('address_type', [
            'label' => __('Form Type'), 'renderer' => $this->getAddressTypeSelect()]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add validation');
    }

    /**
     * Prepare existing row data object.
     *
     * @param DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(DataObject $row): void
    {

        $customAttribute = $row->getData('validation_value');
        $key = 'option_' . $this->optionIsEnabled()->calcOptionHash($customAttribute);
        $options[$key] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);

        $customAttribute = $row->getData('address_type');
        $key = 'option_' . $this->getAddressTypeSelect()->calcOptionHash($customAttribute);
        $options[$key] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);
    }
}
