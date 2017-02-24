<?php

namespace CashbackApi\Reseller;


use CashbackApi\BaseApi;


class Offer extends BaseApi
{
    /**
     * @var int|null
     */
    private $retailerId = null;
    /**
     * @var null
     */
    private $categoryId = null;


    /**
     * @param $offerId
     * @param $categoryId
     * @return bool|object
     */
    public function addToCategory($offerId, $categoryId)
    {
        return $this->getApiCategories()->addOfferToCategory($offerId, $categoryId);
    }

    /**
     * @param $offerId
     * @param $categoryId
     * @return bool|object
     */
    public function removeFromCategory($offerId, $categoryId)
    {
        return $this->getApiCategories()->removeOfferFromCategory($offerId, $categoryId);
    }

    /**
     * @param $offerId
     * @return bool|object
     */
    public function getCategories($offerId)
    {
        return $this->getApiCategories()->getOfferCategories($offerId);
    }

    /**
     * @return int|null
     */
    public function getRetailerId()
    {
        return $this->retailerId;
    }

    /**
     * @param int|null $retailerId
     */
    public function setRetailerId($retailerId)
    {
        $this->retailerId = $retailerId;
    }

    /**
     * @param null $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return null
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    protected function resolveRetailerId($retailerId = null)
    {
        if ($retailerId == 'all') {
            $retailerId = null;
        } else {
            $retailerId = (int)$this->getRetailerId();
        }
        return $retailerId;
    }



    /////////////////////////


    /**
     * @param null $validateFields
     * @return \stdClass
     */
    private function getDefaultOfferData($validateFields = null)
    {
        $data = new \stdClass();

        $data->name = null;
        $data->status = null;
        $data->retailer_id = $this->getRetailerId()??null;
        $data->offer_id = null;
        $this->formatDataWithValidateFields($data, $validateFields, ['retailer_id', 'offer_id']);
        return $data;
    }

    /**
     * @param null $validateFields
     * @return \stdClass
     */
    private function getDefaultOfferSetupData($validateFields = null)
    {
        $data = new \stdClass();
        $data->offer_id = null;
        /**
         * type cpl or voucher
         */
        $data->type = null;
        $data->description = null;
        $data->currency = null;
        $data->commission = null;
        $data->commission_type = null;
        $data->dt_start = null;
        $data->dt_end = null;
        $data->url = null;
        $data->voucher_code = null;

        $this->formatDataWithValidateFields($data, $validateFields, ['retailer_id', 'offer_id']);


        return $data;
    }

    /**
     * @param $retailerSetup
     * @param null $validateFields
     * @return bool|object
     */
    public function updateOfferDraftSetup($offerSetup, $validateFields = null)
    {
        $data = $this->getDefaultOfferSetupData($validateFields);
        $this->mapData($data, $offerSetup);

        return $this->doRequest('reseller/retailer/offer/update-draft-setup', $data);
    }

    /**
     * @param int $retailerId
     * @return bool|object
     */
    public function offerDraftSetupReadyForLive($offerId)
    {

        $data = new \stdClass();
        $data->offer_id = (int)$offerId;

        return $this->doRequest('reseller/retailer/offer/draft-setup-ready-for-live', $data);
    }

    public function getAll()
    {
        $data = new \stdClass();
        $data->awaiting_approval = true;
        $data->include_draft_setup = true;
        return $this->doRequest('reseller/retailer/offer/get-all', $data);
    }


    /**
     * @param $search
     * @param $orderBy
     * @param $page
     * @param int $records
     * @param null $retailerId
     * @return bool|object
     */
    public function getPaginatedAwaitingApproval($search, $orderBy, $page, $records = 20, $retailerId = null)
    {

        $data = new \stdClass();
        $data->retailer_id = $this->resolveRetailerId($retailerId);
        $data->search = $search;
        $data->order_by = $orderBy;
        $data->page = $page;
        $data->per_page = $records;
        $data->awaiting_approval = true;
        $data->include_draft_setup = true;
        return $this->doRequest('reseller/retailer/offer/get-paginated', $data);
    }

    /**
     * @param $categoryId
     * @param $search
     * @param $orderBy
     * @param $page
     * @param int $records
     * @param bool $liveSetup
     * @param bool $draftSetup
     * @param null $status
     * @param null $retailerId
     * @return bool|object
     */
    public function getPaginated($categoryId, $search, $orderBy, $page, $records = 20, $liveSetup = false, $draftSetup = false, $status = null, $retailerId = null)
    {
        $data = new \stdClass();
        $data->category_id = $categoryId;
        $data->retailer_id = $this->resolveRetailerId($retailerId);
        $data->search = $search;
        $data->order_by = $orderBy;
        $data->page = $page;
        $data->per_page = $records;
        $data->include_live_setup = $liveSetup;
        $data->include_draft_setup = $draftSetup;
        $data->status = $status;

        return $this->doRequest('reseller/retailer/offer/get-paginated', $data);
    }

    /**
     * @param $categoryId
     * @param $search
     * @return bool|object
     */
    public function getTotalRecords($categoryId, $search, $status = null, $retailerId = null)
    {
        $data = new \stdClass();
        $data->category_id = $categoryId;
        $data->retailer_id = $this->resolveRetailerId($retailerId);
        $data->search = $search;
        $data->status = $status;
        return $this->doRequest('reseller/retailer/offer/get-total', $data);
    }

    /**
     * @param $search
     * @return bool|object
     */
    public function getTotalAwaitingApprovalRecords($search, $retailerId = null)
    {
        $data = new \stdClass();
        $data->search = $search;
        $data->awaiting_approval = true;
        $data->retailer_id = $this->resolveRetailerId($retailerId);
        return $this->doRequest('reseller/retailer/offer/get-total', $data);
    }

    /**
     * @param null $name
     * @param bool $autoCreateDraft
     * @param null $retailerId
     * @return bool|object
     */
    public function createOffer($name = null, $autoCreateDraft = true, $retailerId = null)
    {
        $data = new \stdClass();
        $data->name = $name;
        $data->create_draft_setup = $autoCreateDraft;
        $data->retailer_id = (int)($retailerId??$this->getRetailerId());
        return $this->doRequest('reseller/retailer/offer/create', $data);
    }

    /**
     * @param $retailer
     * @return bool|object
     */
    public function updateOffer($offer, $validateFields = null)
    {
        $data = $this->getDefaultOfferData($validateFields);

        $this->mapData($data, $offer);

        return $this->doRequest('reseller/retailer/offer/update', $data);
    }

    /**
     * @param $offerId
     * @param bool $autoCreate
     * @return bool|object
     */
    function getOfferDraftSetup($offerId, $autoCreate = false)
    {
        $data = new \stdClass();
        $data->auto_create = $autoCreate;
        $data->offer_id = (int)$offerId;

        return $this->doRequest('reseller/retailer/offer/get-draft-setup', $data);
    }

    /**
     * @param $offerId
     * @return bool|object
     */
    public function getOfferLiveSetup($offerId)
    {
        $data = new \stdClass();
        $data->offer_id = (int)$offerId;

        return $this->doRequest('reseller/retailer/offer/get-live-setup', $data);
    }


    /**
     * @param $id
     * @param bool $live
     * @param bool $draft
     * @return bool|object
     */
    public function get($id, $live = false, $draft = false)
    {
        $data = new \stdClass();
        $data->offer_id = (int)$id;
        $data->include_live_setup = $live;
        $data->include_draft_setup = $draft;
        return $this->doRequest('reseller/retailer/offer/get', $data);
    }

    /**
     * @param $id
     * @return bool|object
     */
    public function delete($id)
    {
        $data = new \stdClass();
        $data->offer_id = (int)$id;
        $data->status = 'archived';
        return $this->updateOffer($data);
    }

    /**
     * @param $id
     * @return bool|object
     */
    public function pause($id)
    {
        $data = new \stdClass();
        $data->offer_id = (int)$id;
        $data->status = 'paused';
        return $this->updateOffer($data);
    }

    /**
     * @param $id
     * @return bool|object
     */
    public function makeLive($id)
    {
        $data = new \stdClass();
        $data->offer_id = (int)$id;
        $data->status = 'live';
        return $this->updateOffer($data);
    }

    /**
     * @param bool $withDescription
     * @return bool|object
     */
    public function getOfferTypes($withDescription = false)
    {
        $data = new \stdClass();
        $data->with_description = $withDescription;
        return $this->doRequest('reseller/retailer/offer/get-types', $data);
    }


}