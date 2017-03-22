<?php

namespace CashbackApi\Reseller;

use CashbackApi\Categories\AddCategoryTrait;

/**
 * Class Categories
 * @package CashbackApi\Reseller
 */
class Categories extends BaseReseller
{
    use AddCategoryTrait;

    public function __construct($apiKey = null, $url = null, $sessionToken = null, $timeSessionGenerated = null)
    {
        parent::__construct($apiKey, $url, $sessionToken, $timeSessionGenerated);

        $this->addCategoryPath = 'reseller';
    }

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
     * @param string $name
     * @return bool|object
     */
    public function createCategory($name)
    {
        $data = new \stdClass();
        $data->name = $name;

        return $this->doRequest('reseller/category/create', $data);
    }

    /**
     * @return bool|object
     */
    public function getAllCategories()
    {
        $data = new \stdClass();
        return $this->doRequest('reseller/category/get-all', $data);
    }


    /**
     * @param int $offerId
     * @param int $categoryId
     * @return bool|object
     */
    public function removeOfferFromCategory($offerId, $categoryId)
    {
        $data = new \stdClass();
        $data->offer_id = (int)$offerId;
        $data->category_id = (int)$categoryId;

        return $this->doRequest('reseller/category/remove-offer', $data);
    }


    /**
     * @param int $retailerId
     * @param int $categoryId
     * @return bool|object
     */
    public function removeRetailerFromCategory($retailerId, $categoryId)
    {
        $data = new \stdClass();
        $data->retailer_id = (int)$retailerId;
        $data->category_id = (int)$categoryId;

        return $this->doRequest('reseller/category/remove-retailer', $data);
    }

    /**
     * @param int $retailerId
     * @return bool|object
     */
    public function getRetailerCategories($retailerId)
    {
        $data = new \stdClass();
        $data->retailer_id = (int)$retailerId;

        return $this->doRequest('reseller/category/get-retailer-categories', $data);
    }

    /**
     * @param int $offerId
     * @return bool|object
     */
    public function getOfferCategories($offerId)
    {
        $data = new \stdClass();
        $data->offer_id = (int)$offerId;

        return $this->doRequest('reseller/category/get-offer-categories', $data);
    }

    /**
     * @param $search
     * @param $orderBy
     * @param $page
     * @param int $records
     * @return bool|object
     */
    public function getPaginated($search, $orderBy, $page, $records = 20)
    {
        $data = new \stdClass();
        $data->search = $search;
        $data->order_by = $orderBy;
        $data->page = $page;
        $data->per_page = $records;
        return $this->doRequest('reseller/category/get-paginated', $data);
    }

    /**
     * @param $search
     * @return bool|object
     */
    public function getTotalRecords($search)
    {
        $data = new \stdClass();
        $data->search = $search;
        return $this->doRequest('reseller/category/get-total', $data);
    }

    /**
     * @param $id
     * @return bool|object
     */
    public function get($id)
    {
        $data = new \stdClass();
        $data->category_id = $id;
        return $this->doRequest('reseller/category/get', $data);
    }
}