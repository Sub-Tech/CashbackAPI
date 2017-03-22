<?php
namespace CashbackApi\Resources;

use CashbackApi\BaseApi;
use CashbackApi\Exception\ApiException;


/**
 * Class Resource
 * @package CashbackApi\Restrictions
 */
class Resource extends BaseResource
{
    /**
     * @var null|integer
     */
    protected $id = null;
    /**
     * @var object
     */
    private $resource;

    public function __construct($type, $id, BaseApi $api = null)
    {

        $this->setId($id);

        parent::__construct($type, $api);
    }

    /**
     * @return null
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return bool|object|string
     * @throws ApiException
     */
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

    /**
     * @return string
     */
    public function getName()
    {
        $resource = $this->getResource();

        return $resource->name ?? 'n/a';
    }


}