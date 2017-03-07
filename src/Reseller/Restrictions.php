<?php
namespace CashbackApi\Reseller;

use CashbackApi\Restrictions\AddRestrictionTrait;
use CashbackApi\Restrictions\ListRestrictionTrait;
use CashbackApi\Restrictions\RemoveRestrictionTrait;

/**
 * Class Restrictions
 * @package CashbackApi\Reseller
 */
class Restrictions extends BaseReseller
{
    use AddRestrictionTrait;
    use ListRestrictionTrait;
    use RemoveRestrictionTrait;

    /**
     * Restrictions constructor.
     * @param null $apiKey
     * @param null $url
     * @param null $sessionToken
     * @param null $timeSessionGenerated
     */
    public function __construct($apiKey = null, $url = null, $sessionToken = null, $timeSessionGenerated = null)
    {
        parent::__construct($apiKey, $url, $sessionToken, $timeSessionGenerated);
        $this->addRestrictionPath =
        $this->listRestrictionPath =
        $this->removeRestrictionPath = 'reseller';
    }

}