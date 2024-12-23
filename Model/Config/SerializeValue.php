<?php
/**
 * Copyright © 2024
 * Piotr Wlosek piotr.wlosekx@gmail.com
 */
namespace M2S\AdvancedValidator\Model\Config;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;

class SerializeValue extends Value
{
    /**
     * @var Json
     */
    private $serializer;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param array $data
     * @param Json $serializer
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = [],
        Json $serializer = null
    ) {
        $this->serializer = $serializer ?: ObjectManager::getInstance()
            ->get(Json::class);
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

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
            $this->removeValidationNameDuplicates($value);
        }
        $value = $this->serializeValue($value);
        $this->setValue($value);
        return parent::beforeSave();
    }

    public function serializeValue($value)
    {
        if (is_array($value)) {
            $data = [];

            foreach ($value as $key => $item) {
                if (!array_key_exists($key, $data)) {
                    $data[$key] = $item;
                }
            }

            return $this->serializer->serialize($data);
        } else {
            return $value;
        }

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

    /**
     * Process data after load
     *
     * @return void
     */
    protected function _afterLoad(): void
    {
        $value = [];
        if ($this->getValue()) {
            $value = $this->serializer->unserialize($this->getValue());
        }
        $this->setValue($value);
    }
}

