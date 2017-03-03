<?php
namespace CashbackApi\Reseller;

/**
 * Class Restrictions
 * @package CashbackApi\Reseller
 */
class Restrictions extends BaseReseller
{

    /**
     * @param bool $withDescription
     * @return bool|object
     */
    public function getOfferTypes($withDescription = false)
    {
        $data = new \stdClass();
        $data->with_description = $withDescription;
        return $this->doRequest('reseller/restrictions/get-types', $data);
    }

}