<?php
namespace CashbackApi\Resources;

use CashbackApi\BaseApi;


/**
 * Class PaginatedType
 * @package CashbackApi\Resources
 */
class PaginatedType extends BaseResource
{
    /**
     * @var null
     */
    protected $retailerId = null;

    /**
     * PaginatedType constructor.
     * @param $type
     * @param BaseApi|null $api
     * @param null $retailerId
     */
    public function __construct($type, BaseApi $api = null, $retailerId = null)
    {

        $this->setRetailerId($retailerId);
        parent::__construct($type, $api);
    }
    
    /**
     * @param $search
     * @param $orderBy
     * @param $page
     * @param int $records
     * @return bool|object
     */
    public function get($search, $orderBy, $page, $records = 20)
    {
        $resultData = false;
        switch ($this->getType()) {
            case 'retailer':
                $resultData = $this->getResourceApi()->getPaginated(null, $search, $orderBy, $page, $records);
                break;
            case 'category':
                $resultData = $this->getResourceApi()->getPaginated($search, $orderBy, $page, $records);
                break;
            case 'offer':
                $resultData = $this->getResourceApi()->getPaginated(null, $search, $orderBy, $page, $records);
                break;
            case 'whitelabel':
                $resultData = $this->getResourceApi()->getPaginated($search, $orderBy, $page, $records);
                break;
        }

        return $resultData;
    }

}