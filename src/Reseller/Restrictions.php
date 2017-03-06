<?php
namespace CashbackApi\Reseller;

use CashbackApi\Restrictions\AddRestrictionTrait;
use CashbackApi\Restrictions\ListRestrictionTrait;

/**
 * Class Restrictions
 * @package CashbackApi\Reseller
 */
class Restrictions extends BaseReseller
{

    use AddRestrictionTrait;
    public $AddRestrictionBaseUrl = 'reseller';
    use ListRestrictionTrait;
    public $listRestrictionBaseUrl = 'reseller';
}