<?php
namespace CashbackApi\Resources;

use CashbackApi\BaseApi;
use CashbackApi\Reseller\BaseReseller;
use CashbackApi\Whitelabel\BaseWhitelabel;
use CashbackApi\Reseller\Retailer as ResellerRetailer;
use CashbackApi\Whitelabel\Retailer as WhitelabelRetailer;
use CashbackApi\Reseller\Offer as ResellerOffer;
use CashbackApi\Whitelabel\Offer as WhitelabelOffer;
use CashbackApi\Reseller\Whitelabel;


abstract class BaseResource
{
    /**
     * @var bool
     */
    protected $reseller = false;
    /**
     * @var null|BaseReseller|BaseWhitelabel
     */
    protected $api = null;
    /**
     * @var null
     */
    protected $resourceApi = null;
    /**
     * @var null|int
     */
    protected $retailerId = null;

    public function __construct($type, BaseApi $api = null)
    {
        $this->setApi($api);
        $this->setType($type);

    }

    /**
     * @param int|null $retailerId
     */
    public function setRetailerId($retailerId)
    {
        $this->retailerId = $retailerId;
    }

    /**
     * @return int|null
     */
    public function getRetailerId()
    {
        return $this->retailerId;
    }


    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    /**
     * @return bool
     */
    public function isReseller()
    {
        return $this->reseller;
    }

    /**
     * @param bool $isReseller
     */
    public function setReseller(bool $isReseller)
    {
        $this->reseller = $isReseller;
    }

    /**
     * @return BaseReseller|BaseWhitelabel|null
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param BaseReseller|BaseWhitelabel|null $api
     */
    public function setApi(BaseApi $api)
    {
        $this->setReseller((is_a($api, 'CashbackApi\\Reseller\\BaseReseller') ? true : false));
        $this->api = $api;
    }

    protected function getResourceApi()
    {
        if (isset($this->resourceApi)) {
            return $this->resourceApi;
        }

        switch ($this->getType()) {
            case 'retailer':
                if ($this->isReseller()) {
                    return $this->resourceApi = new ResellerRetailer();
                } else {
                    return $this->resourceApi = new WhitelabelRetailer();
                }
                break;
            case 'offer':
                if ($this->isReseller()) {
                    $offer = $this->resourceApi = new ResellerOffer();
                } else {
                    $offer = $this->resourceApi = new WhitelabelOffer();
                }

                if (isset($this->retailerId)) {
                    $offer->setRetailerId($this->getRetailerId());
                }

                return $offer;
                break;
            case 'whitelabel':
                if ($this->isReseller()) {
                    return $this->resourceApi = new Whitelabel();
                } else {
                    return $this->resourceApi = false;
                }
                break;

            case 'category':
                if ($this->api !== null) {
                    return $this->resourceApi = $this->getApi()->getApiCategories();
                }

                break;
        }
    }


}