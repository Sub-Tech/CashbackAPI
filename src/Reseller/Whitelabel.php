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
        $data->whitelabel_id = (int)$whiteLabelId;
        return $this->doRequest('reseller/whitelabel/get', $data);
    }

    /**
     * @param $search
     * @param $orderBy
     * @param $page
     * @param int $records
     * @return bool|object
     */
    public function getPaginated($search, $orderBy, $page, $records = 20)
    {
        $data = new \stdClass();
        $data->search = $search;
        $data->order_by = $orderBy;
        $data->page = $page;
        $data->per_page = $records;
        return $this->doRequest('reseller/whitelabel/get-paginated', $data);
    }

    /**
     * @param $search
     * @return bool|object
     */
    public function getTotalRecords($search)
    {
        $data = new \stdClass();
        $data->search = $search;
        return $this->doRequest('reseller/whitelabel/get-total', $data);
    }
}