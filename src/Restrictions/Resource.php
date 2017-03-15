<?php
namespace CashbackApi\Restrictions;

use CashbackApi\BaseApi;
use CashbackApi\Exception\ApiException;
use CashbackApi\Reseller\BaseReseller;
use CashbackApi\Whitelabel\BaseWhitelabel;
use CashbackApi\Reseller\Retailer as ResellerRetailer;
use CashbackApi\Whitelabel\Retailer as WhitelabelRetailer;
use CashbackApi\Reseller\Offer as ResellerOffer;
use CashbackApi\Whitelabel\Offer as WhitelabelOffer;
use CashbackApi\Reseller\Whitelabel;


/**
 * Class Resource
 * @package CashbackApi\Restrictions
 */
class Resource
{
    protected $type = null;
    protected $id = null;
    protected $reseller = false;
    /**
     * @var null|BaseReseller|BaseWhitelabel
     */
    protected $api = null;
    /**
     * @var null
     */
    protected $resourceApi = null;

    private $resource;

    public function __construct($type, $id, BaseApi $api = null)
    {
        $this->setApi($api);
        $this->setId($id);
        $this->setType($type);
        $this->setReseller((is_a($api, 'CashbackApi\\Reseller\\BaseReseller') ? true : false));
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
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function setApi($api)
    {
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
                    return $this->resourceApi = new ResellerOffer();
                } else {
                    return $this->resourceApi = new WhitelabelOffer();
                }
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

    public function getResource()
    {
        if (isset($this->resource)) {
            return $this->resource;
        }
        $api = $this->getResourceApi();
        if ($this->api == null || !$api) {
            return 'n/a';
        }
        try {
            return $this->resource = $api->get($this->getId());
        } catch (\Exception $e) {
            throw new ApiException('Get Method Not Available');
        }

    }

    public function getName()
    {
        $resource = $this->getResource();

        return $resource->name ?? 'n/a';
    }
}