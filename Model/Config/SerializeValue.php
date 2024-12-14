<?php
/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\Model\Config;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Config\Model\Config\Backend\Serialized;

class SerializeValue extends Serialized
{

    /**
     * Processing object before save data
     *
     * @return $this
     * @throws LocalizedException
     */
    public function beforeSave()
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
        $this->setValue($value);
        return parent::beforeSave();
    }    
}

