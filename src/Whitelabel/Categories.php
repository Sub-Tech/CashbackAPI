<?php

namespace CashbackApi\Whitelabel;

/**
 * Class Categories
 * @package CashbackApi\Reseller
 */
class Categories extends BaseWhitelabel
{

    /**
     * @param null $validateFields
     * @return \stdClass
     */
    private function getDefaultCategoryData($validateFields = null)
    {
        $data = new \stdClass();

        $data->name = null;
        $data->category_id = null;
        $this->formatDataWithValidateFields($data, $validateFields, ['retailer_id']);
        return $data;
    }


    /**
     * @return bool|object
     */
    public function getAllCategories()
    {
        $data = new \stdClass();
        return $this->doRequest('whitelabel/category/get-all', $data);
    }


    /**
     * @param int $retailerId
     * @return bool|object
     */
    public function getRetailerCategories($retailerId)
    {
        $data = new \stdClass();
        $data->retailer_id = (int)$retailerId;

        return $this->doRequest('whitelabel/category/get-retailer-categories', $data);
    }

    /**
     * @param int $offerId
     * @return bool|object
     */
    public function getOfferCategories($offerId)
    {
        $data = new \stdClass();
        $data->offer_id = (int)$offerId;

        return $this->doRequest('whitelabel/category/get-offer-categories', $data);
    }

}