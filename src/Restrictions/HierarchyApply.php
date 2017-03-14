<?php

namespace CashbackApi\Restrictions;

use CashbackApi\Reseller\BaseReseller;
use CashbackApi\Whitelabel\BaseWhitelabel;

/**
 * Class HierarchyApply
 * @package CashbackApi\Restrictions
 * @property BaseReseller|BaseWhitelabel $restrictionApi
 */
class HierarchyApply
{
    /**
     * @var null|Resource;
     */
    protected $restrictionAgainst = null;
    /**
     * @var null|Resource;
     */
    protected $restriction = null;
    /**
     * @var array
     */
    public $peckingOrder = ['whitelabel' => 4, 'category' => 3, 'retailer' => 2, 'offer' => 1];

    /**
     * @param Resource $resourceOne
     * @param Resource $resourceTwo
     */
    public function __construct(Resource $resourceOne, Resource $resourceTwo, $restrictionType = 'resource_blacklist')
    {
        $one = $this->peckingOrder[$resourceOne->getType()]??0;
        $two = $this->peckingOrder[$resourceTwo->getType()]??0;

        if ($one > $two) {
            $this->setRestrictionAgainst($resourceOne);
            $this->setRestriction($resourceTwo);
        } else {
            $this->setRestrictionAgainst($resourceTwo);
            $this->setRestriction($resourceOne);
        }

        $this->restrictionApi = $this->getRestrictionAgainst()->getApi()->getApiRestrictions();

        $restrictMethod = 'addRestrictionTo' . ucwords($this->getRestrictionAgainst()->getType());

        $restriction = new \stdClass();
        $restriction->restriction_type = $restrictionType;
        $restriction->resource_type = $this->getRestriction()->getType();
        $restriction->resource_id = $this->getRestriction()->getId();
        return call_user_func_array(
            array($this->restrictionApi, $restrictMethod),
            array($this->getRestrictionAgainst()->getId(), $restriction)
        );

    }

    /**
     * @return null
     */
    public function getRestrictionAgainst()
    {
        return $this->restrictionAgainst;
    }

    /**
     * @param null $restrictionAgainst
     */
    public function setRestrictionAgainst($restrictionAgainst)
    {
        $this->restrictionAgainst = $restrictionAgainst;
    }

    /**
     * @return null|Resource
     */
    public function getRestriction()
    {
        return $this->restriction;
    }

    /**
     * @param null|Resource $restriction
     */
    public function setRestriction($restriction)
    {
        $this->restriction = $restriction;
    }


}