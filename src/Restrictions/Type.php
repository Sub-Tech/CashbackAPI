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
    /**
     * @var
     */
    public static $uniqueId;
    /**
     * @var bool
     */
    protected $typeData = false;
    /**
     * @var null
     */
    protected $restriction = null;
    /**
     * @var null
     */
    protected $api = null;

    /**
     * @var null|int
     */
    protected $resourceType = null;
    /**
     * @var null
     */
    protected $resource = null;

    /**
     * Type constructor.
     * @param $resourceType
     * @param $typeData
     * @param $restriction
     */
    public function __construct($resourceType, $typeData, $restriction = null, BaseApi $api = null)
    {

        $this->setTypeData($typeData);
        $this->setResourceType($resourceType);
        $this->setRestriction($restriction);
        $this->setApi($api);
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

    /**
     * @param $inputName
     * @return mixed
     */
    public static function getLabelName($inputName)
    {
        return str_replace('Ip', 'IP', ucwords(str_replace('_', ' ', $inputName)));
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->getTypeData()->description ?? 'no description';

    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return static::getLabelName($this->getTypeData()->type ?? 'no title');
    }

    /**
     * @return bool
     */
    public function getType()
    {
        return $this->getTypeData()->type ?? false;
    }

    public function getInputsHtml()
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
                $value = $this->getDefinitiveFieldValue($name);
                switch ($arg->type) {
                    case 'string':
                    case 'int':
                        $type = 'text';
                        if ($arg->type == 'int') {
                            $type = 'number';
                        }
                        $returnValue .= '<input type="' . $type . '" name="' .
                            $name . '" class="restrictionInput_' . $i . '" value="' . $value . '" />';
                        break;
                    case 'array':
                        $returnValue .= '<div><textarea name="' .
                            $name . '" class="restrictionInput_' . $i . '" >' . $value . '</textarea></div>';
                        break;
                }
                $returnValue .= '</div>';

            }
        }
        return $returnValue;
    }

    public function displayValue()
    {
        $returnValue = '';
        $args = $this->getArguments();
        if (Giraffe::canIterate($args)) {


            foreach ($args as $arg) {

                $name = $arg->input_name;
                $value = $this->getDefinitiveFieldValue($name);
                switch ($name) {
                    case 'resource_id':
                        $resourceId = $value;
                        break;
                    case 'resource_type':
                        $resourceType = $value;
                        break;
                    default:
                        $returnValue .= $value;

                        break;
                }
                if (isset($resourceId) && isset($resourceType)) {
                    return ucwords($resourceType) . ' (' . $resourceId . ') : ' . $this->getResource($resourceType, $resourceId);
                }

            }
        }
        return $returnValue;
    }

    public function getResource($resourceType, $resourceId)
    {
        $this->resource = $this->resource ?? new Resource($resourceType, $resourceId, $this->getApi());

        return $this->resource->getName();
    }


    public function getDefinitiveFieldValue($key)
    {

        $fieldsArray = $this->getTypeData()->definitive_fields;

        if (!Giraffe::canIterate($fieldsArray)) {
            return '';
        }

        foreach ($fieldsArray as $fieldKey) {
            if ($key == $fieldKey) {

                $restrictionValue = $this->getRestriction()->{$key}??false;
                if (!is_array($restrictionValue) && $restrictionValue) {

                    return $restrictionValue;
                } elseif (is_array($restrictionValue) && count($restrictionValue)) {
                    return join(',', $restrictionValue);
                }

            }
        }


        return '';
    }


    /**
     * @return null
     */
    public function getRestriction()
    {
        return $this->restriction;
    }

    /**
     * @param null $restriction
     */
    public function setRestriction($restriction)
    {
        $this->restriction = $restriction;
    }


    /**
     * @param null|string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    /**
     * @return int|null
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }

    /**
     * @param int|null $resourceType
     */
    public function setResourceType($resourceType)
    {
        $this->resourceType = $resourceType;
    }

    /**
     * @return null
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param null $api
     */
    public function setApi($api)
    {
        $this->api = $api;
    }

}