<?php

namespace CashbackApi\Restrictions;


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


    public function listRetailerRestrictions($retailerId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->retailer_id = $retailerId;

        return $this->doRequest($this->listRestrictionPath . '/restrictions/retailer/list', $data);
    }

    public function listOfferRestrictions($offerId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->offer_id = $offerId;

        return $this->doRequest($this->listRestrictionPath . '/restrictions/offer/list', $data);
    }

    public function listCategoryRestrictions($categoryId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->category_id = $categoryId;

        return $this->doRequest($this->listRestrictionPath . '/restrictions/category/list', $data);
    }

    public function listWhitelabelRestrictions($whitelabelId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->whitelabel_id = $whitelabelId;

        return $this->doRequest($this->listRestrictionPath . '/restrictions/whitelabel/list', $data);
    }

    public function addMinAgeRestrictionToRetailer()
    {
        /**
         * TODO make this shit make sense!
         */
    }
}