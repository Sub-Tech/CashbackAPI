<?php

namespace CashbackApi\Restrictions;

/**
 * Class Types
 * @package CashbackApi\Restrictions
 */
class Type
{

    protected $typeData = false;

    /**
     * Types constructor.
     * @param $typeData | object
     */
    public function __construct($typeData)
    {
        $this->setTypeData($typeData);
    }

    /**
     * @return object
     */
    public function getTypeData()
    {
        return $this->typeData;
    }

    /**
     * @param object $typeData
     */
    public function setTypeData($typeData)
    {
        $this->typeData = $typeData;
    }

    /**
     * @return array|bool
     */
    public function getArguments()
    {


        $args = $this->getTypeData()->arguments ?? false;
        if (!$args) {
            return false;
        }

        $split = explode(' ', $args);
        $returnArray = [];
        foreach ($split as $arg) {
            $split2 = explode(':', $arg);
            $name = $split2[0]??'unknown';
            $baseType = $split2[1]??'unknown';
            if (strpos($baseType, '[]') !== false) {
                $type = 'array';
                $of = str_replace('[]', '', $baseType);
            } else {
                $type = $baseType;
                $of = $baseType;
            }
            $argObject = new \stdClass();
            $argObject->type = $type;
            $argObject->of = $of;
            $returnArray[] = $argObject;
        }
        return $returnArray;
    }


}