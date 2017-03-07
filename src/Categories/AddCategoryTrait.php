<?php
namespace CashbackApi\Categories;

trait AddCategoryTrait
{
    public $addCategoryPath;

    /**
     * @param int $offerId
     * @param int $categoryId
     * @return bool|object
     */
    public function addOfferToCategory($offerId, $categoryId)
    {
        $data = new \stdClass();
        $data->offer_id = (int)$offerId;
        $data->category_id = (int)$categoryId;

        return $this->doRequest($this->addCategoryPath . '/category/add-offer', $data);
    }

    /**
     * @param int $retailerId
     * @param int $categoryId
     * @return bool|object
     */
    public function addRetailerToCategory($retailerId, $categoryId)
    {
        $data = new \stdClass();
        $data->retailer_id = (int)$retailerId;
        $data->category_id = (int)$categoryId;

        mail('john@stechga.co.uk', '-->' . $this->addCategoryPath, 'hi');

        return $this->doRequest($this->addCategoryPath . '/category/add-retailer', $data);
    }
}