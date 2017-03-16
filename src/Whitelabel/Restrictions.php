<?php
namespace CashbackApi\Whitelabel;

use CashbackApi\Restrictions\AddRestrictionTrait;
use CashbackApi\Restrictions\UpdateRestrictionTrait;
use CashbackApi\Restrictions\ListRestrictionTrait;
use CashbackApi\Restrictions\RemoveRestrictionTrait;
use CashbackApi\Restrictions\UpdateRestrictionTrait;

/**
 * Class Restrictions
 * @package CashbackApi\Reseller
 */
class Restrictions extends BaseWhitelabel
{
    use UpdateRestrictionTrait;
    use AddRestrictionTrait;
    use ListRestrictionTrait;
    use RemoveRestrictionTrait;

    public function __construct($apiKey = null, $url = null, $sessionToken = null, $timeSessionGenerated = null)
    {
        parent::__construct($apiKey, $url, $sessionToken, $timeSessionGenerated);
        $this->addRestrictionPath =
        $this->updateRestrictionPath =
        $this->listRestrictionPath =
        $this->removeRestrictionPath = 'whitelabel';
    }
}