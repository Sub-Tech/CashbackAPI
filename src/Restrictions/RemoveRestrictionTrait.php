<?php
namespace CashbackApi\Restrictions;


trait RemoveRestrictionTrait
{
    public $removeRestrictionBaseUrl;


    public function removeRestrictionFromRetailer($retailerId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->retailer_id = $retailerId;

        return $this->doRequest($this->removeRestrictionBaseUrl . '/restrictions/retailer/remove', $data);
    }

    public function removeRestrictionFromOffer($offerId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->offer_id = $offerId;

        return $this->doRequest($this->removeRestrictionBaseUrl . '/restrictions/offer/remove', $data);
    }

    public function removeRestrictionFromCategory($categoryId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->category_id = $categoryId;

        return $this->doRequest($this->removeRestrictionBaseUrl . '/restrictions/category/remove', $data);
    }

    public function removeRestrictionFromWhitelabel($whitelabelId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->whitelabel_id = $whitelabelId;

        return $this->doRequest($this->removeRestrictionBaseUrl . '/restrictions/whitelabel/remove', $data);
    }
}