<?php
/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\Model\Config;

use Magento\Config\Model\Config\Backend\Serialized;
use Magento\Framework\Exception\LocalizedException;

class SerializeValue extends Serialized
{
    /**
     * @return SerializeValue
     * @throws LocalizedException
     */
    public function beforeSave(): object
    {
        $value = $this->getValue();
        if (is_array($value)) {
            unset($value['__empty']);

            if (empty($value)) {
                throw new LocalizedException(
                    __('At least one reason value is required')
                );
            }
        }
        $this->removeValidationNameDuplicates($value);

        $this->setValue($value);
        return parent::beforeSave();
    }

    /**
     * @param $data
     * @return void
     * @throws LocalizedException
     */
    public function removeValidationNameDuplicates(&$data): void
    {
    $uniqueValidationNames = [];

        foreach ($data as $key => $item) {
            $validationName = isset($item['validation_name_regex']) ? $item['validation_name_regex'] : false;

            if ($validationName && in_array($validationName, $uniqueValidationNames)) {
                unset($data[$key]);
                throw new LocalizedException(
                    __('Validation name must be unique!')
                );
            } else if ($validationName) {
                $uniqueValidationNames[] = $validationName;
            }
        }
    }
}

