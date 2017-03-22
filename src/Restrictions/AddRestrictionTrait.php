<?php

namespace CashbackApi\Restrictions;


use CashbackApi\Exception\ApiException;

/**
 * Class AddRestrictionTrait
 * @package CashbackApi\Restrictions
 */
trait AddRestrictionTrait
{

    public $addRestrictionPath;
    public static $restrictionTypes = [];

    /**
     * @param bool $withDescription
     * @return bool|object
     */
    public function getRestrictionTypes($withDescription = false)
    {
        if (isset(self::$restrictionTypes[(int)$withDescription])) {
            return self::$restrictionTypes[(int)$withDescription];
        }
        $data = new \stdClass();
        $data->with_description = $withDescription;
        return self::$restrictionTypes[(int)$withDescription] = $this->doRequest($this->addRestrictionPath . '/restrictions/get-types', $data);
    }

    /**
     * @param bool $withDescription
     * @return mixed
     */
    public function getResourceTypes($withDescription = false)
    {
        $data = new \stdClass();
        $data->with_description = $withDescription;
        return $this->doRequest($this->addRestrictionPath . '/restrictions/get-resource-types', $data);
    }

    /**
     * @param $data
     * @param object $restriction
     */
    protected function setAddRestriction(&$data, \stdClass $restriction)
    {
        foreach ($restriction as $key => $val) {
            $data->{$key} = $val;
        }
    }

    /**
     * @param null $retailerId
     * @param null $restriction
     * @return mixed
     */
    public function addRestrictionToRetailer($retailerId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->retailer_id = (int)$retailerId;
        $this->setAddRestriction($data, $restriction);
        return $this->doRequest($this->addRestrictionPath . '/restrictions/retailer/add', $data);
    }

    /**
     * @param null $offerId
     * @param null $restriction
     * @return mixed
     */
    public function addRestrictionToOffer($offerId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->offer_id = (int)$offerId;
        $this->setAddRestriction($data, $restriction);
        return $this->doRequest($this->addRestrictionPath . '/restrictions/offer/add', $data);
    }

    /**
     * @param null $categoryId
     * @param null $restriction
     * @return mixed
     */
    public function addRestrictionToCategory($categoryId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->category_id = (int)$categoryId;
        $this->setAddRestriction($data, $restriction);
        return $this->doRequest($this->addRestrictionPath . '/restrictions/category/add', $data);
    }

    /**
     * @param null $whitelabelId
     * @param null $restriction
     * @return mixed
     */
    public function addRestrictionToWhitelabel($whitelabelId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->whitelabel_id = (int)$whitelabelId;
        $this->setAddRestriction($data, $restriction);

        return $this->doRequest($this->addRestrictionPath . '/restrictions/whitelabel/add', $data);
    }

    /**
     * @param $toType (retailer,offer,category,whitelabel)
     * @param $id
     * @param object $restriction
     * @return mixed
     */
    protected function mapToType($toType, $id, \stdClass $restriction)
    {
        $allowed = ['retailer', 'offer', 'category', 'whitelabel'];
        if (!in_array($toType, $allowed)) {
            throw new ApiException('Incorrect Type used!');
        }
        switch ($toType) {
            case 'retailer':
                return $this->addRestrictionToRetailer($id, $restriction);
                break;
            case 'offer':
                return $this->addRestrictionToOffer($id, $restriction);
                break;
            case 'category':
                return $this->addRestrictionToCategory($id, $restriction);
                break;
            case 'whitelabel':
                return $this->addRestrictionToCategory($id, $restriction);
                break;
        }
    }

    /**
     * @param $toType
     * @param $id
     * @param int $age
     * @return mixed
     */
    public function addMinimumAgeRestriction($toType, $id, $age = 18)
    {
        $restriction = new \stdClass();
        $restriction->restriction_type = 'minimum_age';
        $restriction->minimum_age = (int)$age;
        return $this->mapToType($toType, $id, $restriction);

    }


    /**
     * @param $toType (retailer,offer,category,whitelabel)
     * @param $id
     * @param int $age
     * @return mixed
     */
    public function addMaximumAgeRestriction($toType, $id, $age = 65)
    {
        $restriction = new \stdClass();
        $restriction->restriction_type = 'maximum_age';
        $restriction->maximum_age = (int)$age;
        return $this->mapToType($toType, $id, $restriction);

    }

    /**
     * @param $toType
     * @param $id
     * @param array|string $ips
     * @return mixed
     */
    public function addIpBlacklistRestriction($toType, $id, $ips)
    {
        $restriction = new \stdClass();
        $restriction->restriction_type = 'ip_blacklist';
        if (is_array($ips)) {
            $restriction->ip_addresses = $ips;
        } else {
            $restriction->ip_address = $ips;
        }

        return $this->mapToType($toType, $id, $restriction);
    }

    /**
     * @param $resourceType
     * @param $resourceId
     * @return \stdClass
     */
    protected function getResourceBlacklist($resourceType, $resourceId)
    {
        $restriction = new \stdClass();
        $restriction->restriction_type = 'resource_blacklist';
        $restriction->resource_id = (int)$resourceId;
        $restriction->resource_type = $resourceType;
        return $restriction;
    }

    /**
     * @param $toType
     * @param $id
     * @param $offerId
     * @return mixed
     */
    public function addOfferRestriction($toType, $id, $offerId)
    {
        $restriction = $this->getResourceBlacklist('offer', $offerId);
        return $this->mapToType($toType, $id, $restriction);
    }

    /**
     * @param $toType
     * @param $id
     * @param $retailerId
     * @return mixed
     */
    public function addRetailerRestriction($toType, $id, $retailerId)
    {
        $restriction = $this->getResourceBlacklist('retailer', $retailerId);
        return $this->mapToType($toType, $id, $restriction);
    }

    /**
     * @param $toType * @param $id
     * @param $categoryId
     * @return mixed
     */
    public function addCategoryRestriction($toType, $id, $categoryId)
    {
        $restriction = $this->getResourceBlacklist('category', $categoryId);
        return $this->mapToType($toType, $id, $restriction);
    }

    /**
     * @param $toType
     * @param $id
     * @param $whitelabelId
     * @return mixed
     */
    public function addWhitelabelRestriction($toType, $id, $whitelabelId)
    {
        $restriction = $this->getResourceBlacklist('whitelabel', $whitelabelId);
        return $this->mapToType($toType, $id, $restriction);
    }
}