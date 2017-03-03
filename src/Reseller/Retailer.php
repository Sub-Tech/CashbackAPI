<?php

namespace CashbackApi\Reseller;


use CashbackApi\BaseApi;
use CashbackApi\Exception\ApiException;

/**
 * Class Retailer
 * @package CashbackApi\Reseller
 */
class Retailer extends BaseApi
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
     * @param object $retailerSetup
     * @param null $validateFields
     * @return bool|object
     */
    public function updateRetailerDraftSetup($retailerSetup, $validateFields = null)
    {
        $data = $this->getDefaultRetailerSetupData($validateFields);

        $this->mapData($data, $retailerSetup);

        return $this->doRequest('reseller/retailer/update-draft-setup', $data);
    }

    /**
     * @param int $retailerId
     * @return bool|object
     */
    public function retailerDraftSetupReadyForLive($retailerId)
    {

        $data = new \stdClass();

        $data->retailer_id = (int)$retailerId;

        return $this->doRequest('reseller/retailer/draft-setup-ready-for-live', $data);
    }

    public function getAll()
    {
        $data = new \stdClass();
        $data->awaiting_approval = true;
        $data->include_draft_setup = true;
        return $this->doRequest('reseller/retailer/get-all', $data);
    }


    /**
     * @param $search
     * @param $orderBy
     * @param $page
     * @param int $records
     * @return bool|object
     */
    public function getPaginatedAwaitingApproval($search, $orderBy, $page, $records = 20)
    {
        $data = new \stdClass();
        $data->search = $search;
        $data->order_by = $orderBy;
        $data->page = $page;
        $data->per_page = $records;
        $data->awaiting_approval = true;
        $data->include_draft_setup = true;
        return $this->doRequest('reseller/retailer/get-paginated', $data);
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
                                 $draftSetup = false, $status = null)
    {
        $data = new \stdClass();
        $data->category_id = $categoryId;
        $data->search = $search;
        $data->order_by = $orderBy;
        $data->page = $page;
        $data->per_page = $records;
        $data->include_live_setup = $liveSetup;
        $data->include_draft_setup = $draftSetup;
        $data->status = $status;
        return $this->doRequest('reseller/retailer/get-paginated', $data);
    }

    /**
     * @param $categoryId
     * @param $search
     * @return bool|object
     */
    public function getTotalRecords($categoryId, $search, $status = null)
    {
        $data = new \stdClass();
        $data->category_id = $categoryId;
        $data->search = $search;
        $data->status = $status;
        return $this->doRequest('reseller/retailer/get-total', $data);
    }

    /**
     * @param $search
     * @return bool|object
     */
    public function getTotalAwaitingApprovalRecords($search)
    {
        $data = new \stdClass();
        $data->search = $search;
        $data->awaiting_approval = true;
        return $this->doRequest('reseller/retailer/get-total', $data);
    }

    /**
     * @param null $name
     * @param bool $autoCreateDraft
     * @return bool|object
     */
    public function createRetailer($name = null, $autoCreateDraft = true)
    {
        $data = new \stdClass();
        $data->name = $name;
        $data->create_draft_setup = $autoCreateDraft;

        return $this->doRequest('reseller/retailer/create', $data);
    }

    /**
     * @param $retailer
     * @return bool|object
     */
    public function updateRetailer($retailer, $validateFields = null)
    {
        $data = $this->getDefaultRetailerData($validateFields);

        $this->mapData($data, $retailer);


        return $this->doRequest('reseller/retailer/update', $data);
    }

    /**
     * @param $retailerId
     * @param bool $autoCreate
     * @return bool|object
     */
    public function getRetailerDraftSetup($retailerId, $autoCreate = false)
    {
        $data = new \stdClass();
        $data->auto_create = $autoCreate;
        $data->retailer_id = (int)$retailerId;

        return $this->doRequest('reseller/retailer/get-draft-setup', $data);
    }

    /**
     * @param $retailerId
     * @return bool|object
     */
    public function getRetailerLiveSetup($retailerId)
    {
        $data = new \stdClass();
        $data->retailer_id = (int)$retailerId;

        return $this->doRequest('reseller/retailer/get-live-setup', $data);
    }

    /**
     * @param $retailerId
     * @param string $type
     * @return bool|object
     */
    public function getRetailerLogos($retailerId, $type = 'dark')
    {
        $path = 'retailer/' . $retailerId . '/logos/' . $type;
        return $this->getMedia()->getImages($path);
    }

    /**
     * @param $retailerId
     * @return bool|object
     */
    public function getRetailerCoverImages($retailerId)
    {
        $path = 'retailer/' . $retailerId . '/cover_images';
        return $this->getMedia()->getImages($path);
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
        $data->retailer_id = (int)$id;
        $data->include_live_setup = $live;
        $data->include_draft_setup = $draft;
        return $this->doRequest('reseller/retailer/get', $data);
    }

    /**
     * @param $retailerId
     * @param $categoryId
     * @return bool|object
     */
    public function addToCategory($retailerId, $categoryId)
    {
        return $this->getApiCategories()->addRetailerToCategory($retailerId, $categoryId);
    }

    /**
     * @param $retailerId
     * @param $categoryId
     * @return bool|object
     */
    public function removeFromCategory($retailerId, $categoryId)
    {
        return $this->getApiCategories()->removeRetailerFromCategory($retailerId, $categoryId);
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
     * @param $type
     * @return bool
     */
    public function uploadLogo($retailerId, $type)
    {
        $allowedTypes = ['light', 'dark'];
        if (!in_array($type, $allowedTypes)) {
            $this->setLastErrorMessage('Only Dark or Light Logo Types accepted!');
            return false;
        }
        $data = new \stdClass();
        $data->type = $type;
        $data->retailer_id = $retailerId;
        $uploaded = false;
        if (isset($_FILES['logo'])) {
            $this->setFiles($_FILES);
            $uploaded = $this->doUpload('reseller/retailer/upload-logo', $data);
        } else {
            $this->setLastErrorMessage('logo not specified as file!');
        }
        return $uploaded;
    }

    /**
     * @param $retailerId
     * @return bool
     */
    public function uploadCoverImage($retailerId)
    {

        $data = new \stdClass();
        $data->retailer_id = $retailerId;
        $uploaded = false;
        if (isset($_FILES['cover_image'])) {
            $this->setFiles($_FILES);
            $uploaded = $this->doUpload('reseller/retailer/upload-cover-image', $data);
        } else {
            $this->setLastErrorMessage('cover_image not specified as file!');
        }
        return $uploaded;
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

    /**
     * @param $search
     * @return bool|object
     */
    public function getArchiveNameSuggestions($search)
    {
        $data = new \stdClass();
        $data->search = $search;
        return $this->doRequest('reseller/retailer/get-archive-name-suggestion', $data);
    }

    public function delete($id)
    {
        $data = new \stdClass();
        $data->retailer_id = (int)$id;
        $data->status = 'archived';
        return $this->updateRetailer($data);
    }

    public function pause($id)
    {
        $data = new \stdClass();
        $data->retailer_id = (int)$id;
        $data->status = 'paused';
        return $this->updateRetailer($data);
    }

    public function makeLive($id)
    {
        $data = new \stdClass();
        $data->retailer_id = (int)$id;
        $data->status = 'live';
        return $this->updateRetailer($data);
    }

}