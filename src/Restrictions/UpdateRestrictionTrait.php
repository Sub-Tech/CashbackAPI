<?php

namespace CashbackApi\Restrictions;

/**
 * Class UpdateRestrictionTrait
 * @package CashbackApi\Restrictions
 */
trait UpdateRestrictionTrait
{
    public $updateRestrictionPath;

    /**
     * @param $restrictionId
     * @param $resourceType
     * @param $resourceId
     * @return mixed
     */
    public function getRestriction($restrictionId, $resourceType, $resourceId)
    {
        $data = new \stdClass();
        $data->restriction_id = $restrictionId;
        $data->restriction_resource_id = $resourceId;
        $data->restriction_resource_type = $resourceType;

        return $this->doRequest($this->updateRestrictionPath . '/restrictions/get', $data);

    }

    /**
     * @param $restrictionId
     * @param $resourceType
     * @param $resourceId
     * @param $restrictionData
     * @return mixed
     */
    public function updateRestriction($restrictionId, $resourceType, $resourceId, $restriction = null)
    {
        $data = $restriction ?? new \stdClass();
        $data->restriction_id = $restrictionId;
        $data->restriction_resource_id = $resourceId;
        $data->restriction_resource_type = $resourceType;

        return $this->doRequest($this->updateRestrictionPath . '/restrictions/update', $data);
    }

    public function updateMinAge($restrictionId, $resourceType, $resourceId, $age)
    {
        $restriction = new \stdClass();
        $restriction->restriction_type = 'minimum_age';
        $restriction->minimum_age = $age;
        return $this->updateRestriction($restrictionId, $resourceType, $resourceId, $restriction);
    }

    public function updateMaxAge($restrictionId, $resourceType, $resourceId, $age)
    {
        $restriction = new \stdClass();
        $restriction->restriction_type = 'maximum_age';
        $restriction->maximum_age = $age;
        return $this->updateRestriction($restrictionId, $resourceType, $resourceId, $restriction);
    }

    public function updateIPRestriction($restrictionId, $resourceType, $resourceId, $ips)
    {
        $restriction = new \stdClass();
        $restriction->restriction_type = 'ip_blacklist';
        if (is_array($ips)) {
            $restriction->ip_addresses = $ips;
        } else {
            $restriction->ip_address = $ips;
        }
        return $this->updateRestriction($restrictionId, $resourceType, $resourceId, $restriction);
    }


    /**
     * @param $resourceType
     * @param $resourceId
     * @return \stdClass
     */
    protected function getUpdateResourceBlacklist($resourceType, $resourceId)
    {
        $restriction = new \stdClass();
        $restriction->restriction_type = 'resource_blacklist';
        $restriction->resource_id = $resourceId;
        $restriction->resource_type = $resourceType;
        return $restriction;
    }

    /**
     * @param $restrictionId
     * @param $resourceType
     * @param $resourceId
     * @param $categoryId
     * @return mixed
     */
    public function updateCategoryRestriction($restrictionId, $resourceType, $resourceId, $categoryId)
    {
        $restriction = $this->getUpdateResourceBlacklist('category', $categoryId);
        return $this->updateRestriction($restrictionId, $resourceType, $resourceId, $restriction);
    }

    /**
     * @param $restrictionId
     * @param $resourceType
     * @param $resourceId
     * @param $offerId
     * @return mixed
     */
    public function updateOfferRestriction($restrictionId, $resourceType, $resourceId, $offerId)
    {
        $restriction = $this->getUpdateResourceBlacklist('offer', $offerId);
        return $this->updateRestriction($restrictionId, $resourceType, $resourceId, $restriction);
    }

    /**
     * @param $restrictionId
     * @param $resourceType
     * @param $resourceId
     * @param $retailerId
     * @return mixed
     */
    public function updateRetailerRestriction($restrictionId, $resourceType, $resourceId, $retailerId)
    {
        $restriction = $this->getUpdateResourceBlacklist('offer', $retailerId);
        return $this->updateRestriction($restrictionId, $resourceType, $resourceId, $restriction);
    }

    /**
     * @param $restrictionId
     * @param $resourceType
     * @param $resourceId
     * @param $whitelabelId
     * @return mixed
     */
    public function updateWhitelabelRestriction($restrictionId, $resourceType, $resourceId, $whitelabelId)
    {
        $restriction = $this->getUpdateResourceBlacklist('whitelabel', $whitelabelId);
        return $this->updateRestriction($restrictionId, $resourceType, $resourceId, $restriction);
    }

}