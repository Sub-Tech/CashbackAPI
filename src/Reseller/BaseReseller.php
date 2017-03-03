<?php
namespace CashbackApi\Reseller;

use CashbackApi\BaseApi;

/**
 * Class BaseReseller
 * @package CashbackApi\Reseller
 */
class BaseReseller extends BaseApi
{

    /**
     * @var null|Categories
     */
    private static $apiCategories = null;
    /**
     * @var null|Restrictions
     */
    private static $apiRestrictions = null;

    /**
     * @return Categories|null
     */
    public function getApiCategories()
    {
        if (isset(self::$apiCategories)) {
            return self::$apiCategories;
        }
        $apiCategories = new Categories();
        self::$apiCategories = $apiCategories;
        return self::$apiCategories;
    }

    public function getApiRestrictions()
    {
        if (isset(self::$apiRestrictions)) {
            return self::$apiRestrictions;
        }
        $apiRestrictions = new Restrictions();
        self::$apiRestrictions = $apiRestrictions;
        return self::$apiRestrictions;
    }
}