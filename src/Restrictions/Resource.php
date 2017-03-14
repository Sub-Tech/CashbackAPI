<?php
namespace CashbackApi\Restrictions;

use CashbackApi\BaseApi;
use CashbackApi\Reseller\BaseReseller;
use CashbackApi\Whitelabel\BaseWhitelabel;

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
}