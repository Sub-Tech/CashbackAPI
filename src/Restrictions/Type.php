<?php

namespace CashbackApi\Restrictions;

use CashbackApi\BaseApi;
use CashbackApi\Reseller\Whitelabel;
use Giraffe\Giraffe;

/**
 * Class Types
 * @package CashbackApi\Restrictions
 */
class Type
{
    public static $uniqueId;
    protected $typeData = false;
    protected $restriction = null;
    /**
     * @var BaseApi
     */
    protected $api;
    /**
     * @var Whitelabel
     */
    protected $whitelabelApi;

    /**
     * Type constructor.
     * @param $typeData
     * @param BaseApi|null $api
     * @param null $restriction
     */
    public function __construct($typeData, BaseApi $api = null, $restriction = null)
    {
        $this->setTypeData($typeData);
        $this->api = $api;
        $this->setRestriction($restriction);
    }

    /**
     * @param BaseApi $api
     */
    public function setApi(BaseApi $api)
    {
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
                        $returnValue .= '<textarea name="' .
                            $name . '" class="restrictionInput_' . $i . '" />' . $value . '</textarea>';
                        break;
                }
                $returnValue .= '</div>';

            }
        }
        return $returnValue;
    }

    public function getDefinitiveFieldValue($key)
    {
        $fieldsArray = $this->getTypeData()->definitive_fields;

        if (!Giraffe::canIterate($fieldsArray)) {
            return '';
        }
        Giraffe::notification(print_r($fieldsArray, true));
        foreach ($fieldsArray as $fieldKey) {
            if ($key == $fieldKey) {
                return print_r($this->getRestriction()->{$key},true);
            }
        }


        return '';
    }

    /**
     * @return BaseApi | bool
     */
    public function getApi()
    {
        if (!isset($api)) {
            return false;
        }
        return $api;
    }

    /**
     * @return bool|Whitelabel
     */
    public function getWhitelabelApi()
    {

        if (isset($this->whitelabelApi)) {
            return $this->whitelabelApi;
        }

        if ($this->getApi()) {
            return $this->whitelabelApi = new Whitelabel();
        }
        return false;
    }

    public function getWhitelabels()
    {
        $whitelabelApi = $this->getWhitelabelApi();
        if (!$whitelabelApi) {
            return false;
        }
        return $this->getWhitelabelApi()->getAll();

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

}