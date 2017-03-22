<?php

namespace CashbackApi\Restrictions;

use CashbackApi\BaseApi;
use CashbackApi\Reseller\BaseReseller;
use CashbackApi\Resources\Resource;
use CashbackApi\Whitelabel\BaseWhitelabel;
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
     * @param null $restriction
     * @param BaseApi|null $api
     */
    public function __construct($restriction = null, BaseApi $api = null)
    {

        if (is_string($restriction)) {
            $restrictionObject = new \stdClass();
            $restrictionObject->type = $restriction;
            $restriction = $restrictionObject;
        }
        $this->setRestriction($restriction);
        $this->setApi($api);


    }

    /**
     * @return object
     */
    public function getTypeData()
    {
        if ($this->typeData) {
            return $this->typeData;
        }

        $restriction = $this->getRestriction() ?? false;
        if ($restriction && isset($restriction->type) && isset($this->api)) {

            $types = $this->getApi()->getApiRestrictions()->getRestrictionTypes(true);
            if ($types) {
                foreach ($types as $type) {
                    if ($type->type == $restriction->type) {
                        return $this->typeData = $type;
                    }
                }
            }
        }

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

    /**
     * @return string
     */
    public function getInputsHtml($hidden = false)
    {

        $returnValue = '';
        if ($hidden) {
            $returnValue .= '<div style="display: none;">';
        }
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
        if ($hidden) {
            $returnValue .= '</div>';
        }
        return $returnValue;
    }

    /**
     * @param null $currentResourceType
     * @param null $currentResourceId
     * @return string
     */
    public function displayValue($currentResourceType = null, $currentResourceId = null)
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
                    if (isset($currentResourceId) && isset($currentResourceType)) {
                        $id = $this->getOwnedByResourceId();
                        $type = $this->getOwnedByResourceType();
                        if (($id && $type) && ($id != $currentResourceId || $type != $currentResourceType)) {
                            $resourceType = $type;
                            $resourceId = $id;
                        }
                    }
                    return ucwords($resourceType) . ' (' . $resourceId . ') : ' . $this->getResource($resourceType, $resourceId)->getName();
                }

            }
        }
        return $returnValue;
    }

    /**
     * @param $resourceType
     * @param $resourceId
     * @return string
     */
    public function getResource($resourceType, $resourceId)
    {
        return $this->resource ?? new Resource($resourceType, $resourceId, $this->getApi());

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
     * @return null | BaseApi | BaseWhitelabel | BaseReseller
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param null $api
     */
    public function setApi(BaseApi $api = null)
    {
        $this->api = $api;
    }

    public function getOwnedByResourceId()
    {
        return $this->getRestriction()->owned_by_resource_id ?? false;
    }

    public function getOwnedByResourceType()
    {
        return $this->getRestriction()->owned_by_resource_type ?? false;
    }

}