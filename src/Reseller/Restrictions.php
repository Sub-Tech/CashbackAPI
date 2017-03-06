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
    public $addRestrictionBaseUrl = 'reseller';
    use ListRestrictionTrait;
    public $listRestrictionBaseUrl = 'reseller';
    use RemoveRestrictionTrait;
    public $removeRestrictionBaseUrl = 'reseller';
}