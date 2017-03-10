<?php

namespace CashbackApi\Restrictions;

use CashbackApi\BaseApi;
use Giraffe\Giraffe;

/**
 * Class Types
 * @package CashbackApi\Restrictions
 */
class Type
{
    public static $uniqueId;
    protected $typeData = false;

    /**
     * Types constructor.
     * @param $typeData | object
     */
    public function __construct($typeData, BaseApi $api = null)
    {
        $this->setTypeData($typeData);
        $this->api = $api;
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
            $required = $split2[2]??'required';
            if (strpos($baseType, '[]') !== false) {
                $type = 'array';
                $of = str_replace('[]', '', $baseType);
            } else {
                $type = $baseType;
                $of = $baseType;
            }
            $argObject = new \stdClass();

            $argObject->input_name = $name;
            $argObject->type = $type;
            $argObject->of = $of;
            $argObject->required = ($required == 'required') ? 1 : 0;
            $returnArray[] = $argObject;
        }
        return $returnArray;
    }

    public static function getLabelName($inputName)
    {
        return str_replace('Ip', 'IP', ucwords(str_replace('_', ' ', $inputName)));
    }


    public function getDescription()
    {
        return $this->getTypeData()->description ?? 'no description';

    }

    public function getTitle()
    {
        return static::getLabelName($this->getTypeData()->type ?? 'no title');
    }

    public function getInputsHtml($delimited = ',')
    {
        $returnValue = '';
        $args = $this->getArguments();
        if (Giraffe::canIterate($args)) {
            static::$uniqueId = static::$uniqueId ?? 1;
            $returnValue .= '<h5>' . $this->getTitle() . '</h5>';
            $returnValue .= '<h6>' . $this->getDescription() . '</h6>';
            foreach ($args as $arg) {
                $i = static::$uniqueId++;
                $name = $arg->input_name;
                $returnValue .= '<div><label>' . static::getLabelName($name) . '</label>';
                switch ($arg->type) {
                    case 'string':
                    case 'int':
                        $type = 'text';
                        if ($arg->type == 'int') {
                            $type = 'number';
                        }
                        $returnValue .= '<input type="' . $type . '" name="' .
                            $name . '" class="restrictionInput_' . $i . '" />';
                        break;
                    case 'array':
                        $returnValue .= '<textarea name="' .
                            $name . '" class="restrictionInput_' . $i . '" /></textarea>';
                        break;
                }
                $returnValue .= '</div>';

            }
        }
        return $returnValue;
    }


}