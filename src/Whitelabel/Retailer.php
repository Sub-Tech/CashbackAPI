<?php

namespace CashbackApi\Whitelabel;


/**
 * Class Retailer
 * @package CashbackApi\Whitelabel
 */
class Retailer extends BaseWhitelabel
{
    /**
     * @var
     */
    private $offer;

    /**
     * @param null $validateFields
     * @return \stdClass
     */
    private function getDefaultRetailerData($validateFields = null)
    {
        $data = new \stdClass();

        $data->name = null;
        $data->status = null;
        $data->retailer_id = null;
        $this->formatDataWithValidateFields($data, $validateFields, ['retailer_id']);
        return $data;
    }

    /**
     * @param null $validateFields
     * @return \stdClass
     */
    private function getDefaultRetailerSetupData($validateFields = null)
    {
        $data = new \stdClass();
        $data->retailer_id = null;
        $data->description = null;
        $data->meta_description = null;
        $data->cover_image = null;
        $data->logo_light = null;
        $data->logo_light_bg_color = null;
        $data->logo_dark = null;
        $data->logo_dark_bg_color = null;
        $data->keywords = null;

        $this->formatDataWithValidateFields($data, $validateFields, ['retailer_id']);


        return $data;
    }


    /**
     * @param bool $restrictions
     * @param bool $restricted
     * @return bool|object
     */
    public function getAll($restrictions = false, $restricted = false)
    {
        $data = new \stdClass();
        $data->awaiting_approval = true;
        $data->inlcude_live_setup = true;
        $data->include_restrictions = $restrictions;
        $data->include_restricted = $restricted;
        return $this->doRequest('whitelabel/retailer/get-all', $data);
    }


    /**
     * @param $categoryId
     * @param $search
     * @param $orderBy
     * @param $page
     * @param int $records
     * @param bool $liveSetup
     * @param bool $draftSetup
     * @return bool|object
     */
    public function getPaginated($categoryId, $search, $orderBy, $page, $records = 20, $liveSetup = false,
                                 $status = null, $restrictions = false, $restricted = false)
    {
        $data = new \stdClass();
        $data->category_id = $categoryId;
        $data->search = $search;
        $data->order_by = $orderBy;
        $data->page = $page;
        $data->per_page = $records;
        $data->include_live_setup = $liveSetup;
        $data->status = $status;
        $data->include_restrictions = $restrictions;
        $data->include_restricted = $restricted;
        return $this->doRequest('whitelabel/retailer/get-paginated', $data);
    }

    /**
     * @param $categoryId
     * @param $search
     * @return bool|object
     */
    public function getTotalRecords($categoryId, $search, $status = null, $restrictions = false, $restricted = false)
    {
        $data = new \stdClass();
        $data->category_id = $categoryId;
        $data->search = $search;
        $data->status = $status;
        $data->include_restrictions = $restrictions;
        $data->include_restricted = $restricted;
        return $this->doRequest('whitelabel/retailer/get-total', $data);
    }


    /**
     * @param $id
     * @param bool $live
     * @param bool $draft
     * @return bool|object
     */
    public function get($id, $live = false, $draft = false, $restrictions = false, $restricted = false)
    {
        $data = new \stdClass();
        $data->retailer_id = (int)$id;
        $data->include_live_setup = $live;
        $data->include_draft_setup = $draft;
        $data->restrictions = $restrictions;
        $data->include_restricted = $restricted;
        return $this->doRequest('whitelabel/retailer/get', $data);
    }


    /**
     * @param $retailerId
     * @return bool|object
     */
    public function getCategories($retailerId)
    {
        return $this->getApiCategories()->getRetailerCategories($retailerId);
    }


    /**
     * @param $retailerId
     * @return Offer
     */
    public function getOffer($retailerId)
    {

        if (!isset($this->offer)) {
            $this->offer = [];
        }
        if (isset($this->offer[$retailerId])) {
            return $this->offer[$retailerId];
        }
        $this->offer[$retailerId] = new Offer();
        $this->offer[$retailerId]->setRetailerId($retailerId);
        return $this->offer[$retailerId];
    }

}