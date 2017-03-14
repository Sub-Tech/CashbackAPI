<?php

namespace CashbackApi\Whitelabel;
/**
 * Class Offer
 * @package CashbackApi\Whitelabel
 */
class Offer extends BaseWhitelabel
{
    /**
     * @var int|null
     */
    private $retailerId = null;


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


    protected function resolveRetailerId($retailerId = null)
    {
        if ($retailerId == 'all') {
            $retailerId = null;
        } else {
            $retailerId = (int)$this->getRetailerId();
        }
        return $retailerId;
    }


    public function getAll($restrictions = false, $restricted = false, $retailerId = null)
    {
        $data = new \stdClass();
        $data->retailer_id = $this->resolveRetailerId($retailerId);
        $data->include_live_setup = true;
        $data->include_restrictions = $restrictions;
        $data->include_restricted = $restricted;
        return $this->doRequest('whitelabel/retailer/offer/get-all', $data);
    }


    /**
     * @param $id
     * @param bool $live
     * @param bool $draft
     * @param bool $restrictions
     * @param bool $restricted
     * @return bool|object
     */
    public function get($id, $live = false, $draft = false, $restrictions = false)
    {
        $data = new \stdClass();
        $data->offer_id = (int)$id;
        $data->include_live_setup = $live;
        $data->include_draft_setup = $draft;
        $data->include_restrictions = $restrictions;

        return $this->doRequest('whitelabel/retailer/offer/get', $data);
    }


}