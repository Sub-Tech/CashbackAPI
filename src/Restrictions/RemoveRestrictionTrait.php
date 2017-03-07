<?php
namespace CashbackApi\Restrictions;


use CashbackApi\Exception\ApiException;


/**
 * Class RemoveRestrictionTrait
 * @package CashbackApi\Restrictions
 */
trait RemoveRestrictionTrait
{
    public $removeRestrictionPath;

    /**
     * @param $restriction
     * @param $type
     * @param $id
     * @return mixed
     * @throws ApiException
     */
    protected function doRestrictionRemoval($restriction, $type, $id)
    {

        $allowed = ['retailer', 'offer', 'category', 'whitelabel'];
        if (!in_array($type, $allowed)) {
            throw new ApiException('Incorrect Type used!');
        }
        $data = new \stdClass();
        $typeField = $type . '_id';
        $data->{$typeField} = $id;

        if (is_numeric($restriction)) {
            $data->restriction_id = $restriction;
            $pathEnd = '-by-id';
        } else {
            $data->restriction_type = $restriction;
            $pathEnd = '-by-type';
        }

        return $this->doRequest($this->removeRestrictionPath . '/restrictions/' . $type . '/remove' . $pathEnd, $data);
    }

    /**
     * @param $retailerId
     * @param $restriction int|string|array (restriction type| restriction id)
     * @return mixed
     */
    public function removeRestrictionFromRetailer($retailerId, $restriction)
    {
        return $this->doRestrictionRemoval($restriction, 'retailer', $retailerId);
    }

    /**
     * @param $offerId
     * @param $restriction int|string|array (restriction type| restriction id)
     * @return mixed
     */
    public function removeRestrictionFromOffer($offerId, $restriction)
    {
        return $this->doRestrictionRemoval($restriction, 'offer', $offerId);
    }

    /**
     * @param $categoryId
     * @param $restriction int|string|array (restriction type| restriction id)
     * @return mixed
     */
    public function removeRestrictionFromCategory($categoryId, $restriction)
    {
        return $this->doRestrictionRemoval($restriction, 'category', $categoryId);
    }

    /**
     * @param $whitelabelId
     * @param $restriction  int|string|array (restriction type| restriction id)
     * @return mixed
     */
    public function removeRestrictionFromWhitelabel($whitelabelId, $restriction)
    {
        return $this->doRestrictionRemoval($restriction, 'whitelabel', $whitelabelId);
    }
}