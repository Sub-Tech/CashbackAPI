<?php

namespace CashbackApi\Restrictions;


trait RestrictionTrait
{

    public $baseUrl;

    /**
     * @param bool $withDescription
     * @return bool|object
     */
    public function getOfferTypes($withDescription = false)
    {
        $data = new \stdClass();
        $data->with_description = $withDescription;
        return $this->doRequest($this->baseUrl.'/restrictions/get-types', $data);
    }

    public function baseRestriction()
    {

    }

    public function addRestrictionToRetailer($retailerId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->retailer_id = $retailerId;

        return $this->doRequest($this->baseUrl.'/restrictions/get-types', $data);
    }

    public function addMinAgeRestrictionToRetailer()
    {
        /**
         * TODO make this shit make sense!
         */
    }
}