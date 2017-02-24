<?php

namespace CashbackApi\Reseller;


use CashbackApi\BaseApi;

/**
 * Class Whitelabel
 * @package CashbackApi\Reseller
 */
class Whitelabel extends BaseApi
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

    public function getAll(){

        return $this->doRequest('reseller/whitelabel/get-all');
    }

    public function get($whiteLabelId=null){
        $data = new \stdClass();
        $data->white_label_id = $whiteLabelId;
        return $this->doRequest('reseller/whitelabel/get', $data);
    }
}