<?php

namespace CashbackApi\Restrictions;

/**
 * Class ListRestrictionTrait
 * @package CashbackApi\Restrictions
 */
trait ListRestrictionTrait
{

    public $listRestrictionPath;

    /**
     * @param bool $withDescription
     * @return bool|object
     */
    public function getRestrictionTypesForList($withDescription = false)
    {
        $data = new \stdClass();
        $data->with_description = $withDescription;
        return $this->doRequest($this->listRestrictionPath . '/restrictions/get-types', $data);
    }

    /**
     * @param null $retailerId
     * @return mixed
     */
    public function listRetailerRestrictions($retailerId = null)
    {
        $data = new \stdClass();
        $data->retailer_id = $retailerId;

        return $this->doRequest($this->listRestrictionPath . '/restrictions/retailer/list', $data);
    }

    /**
     * @param null $offerId
     * @return mixed
     */
    public function listOfferRestrictions($offerId = null)
    {
        $data = new \stdClass();
        $data->offer_id = $offerId;

        return $this->doRequest($this->listRestrictionPath . '/restrictions/offer/list', $data);
    }

    /**
     * @param null $categoryId
     * @return mixed
     */
    public function listCategoryRestrictions($categoryId = null)
    {
        $data = new \stdClass();
        $data->category_id = $categoryId;

        return $this->doRequest($this->listRestrictionPath . '/restrictions/category/list', $data);
    }

    /**
     * @param null $whitelabelId
     * @return mixed
     */
    public function listWhitelabelRestrictions($whitelabelId = null)
    {
        $data = new \stdClass();
        $data->whitelabel_id = $whitelabelId;

        return $this->doRequest($this->listRestrictionPath . '/restrictions/whitelabel/list', $data);
    }


}