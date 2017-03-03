<?php

namespace CashbackApi\Reseller;

/**
 * Class Whitelabel
 * @package CashbackApi\Reseller
 */
class Whitelabel extends BaseReseller
{


    /**
     * @param null $name
     * @return bool
     */
    public function create($name = null)
    {
        $data = new \stdClass();
        $data->name = $name;
        return $this->doRequest('reseller/whitelabel/create', $data);
    }

    public function getAll()
    {

        return $this->doRequest('reseller/whitelabel/get-all');
    }

    public function get($whiteLabelId = null)
    {
        $data = new \stdClass();
        $data->white_label_id = $whiteLabelId;
        return $this->doRequest('reseller/whitelabel/get', $data);
    }
}