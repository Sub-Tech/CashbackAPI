<?php
namespace CashbackApi\Restrictions;


trait RemoveRestrictionTrait
{
    public $removeRestrictionPath;


    public function removeRestrictionFromRetailer($retailerId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->retailer_id = $retailerId;

        return $this->doRequest($this->removeRestrictionPath . '/restrictions/retailer/remove', $data);
    }

    public function removeRestrictionFromOffer($offerId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->offer_id = $offerId;

        return $this->doRequest($this->removeRestrictionPath . '/restrictions/offer/remove', $data);
    }

    public function removeRestrictionFromCategory($categoryId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->category_id = $categoryId;

        return $this->doRequest($this->removeRestrictionPath . '/restrictions/category/remove', $data);
    }

    public function removeRestrictionFromWhitelabel($whitelabelId = null, $restriction = null)
    {
        $data = new \stdClass();
        $data->whitelabel_id = $whitelabelId;

        return $this->doRequest($this->removeRestrictionPath . '/restrictions/whitelabel/remove', $data);
    }
}