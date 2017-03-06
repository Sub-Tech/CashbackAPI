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
    public $addRestrictionPath = 'reseller';
    use ListRestrictionTrait;
    public $listRestrictionPath = 'reseller';
    use RemoveRestrictionTrait;
    public $removeRestrictionPath = 'reseller';
}