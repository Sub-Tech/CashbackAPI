<?php


namespace CashbackApi\Exception;


class ApiException extends \Exception
{
    public function getApiMessage()
    {

        $str = '<b> - Cashback API - ' . $this->getMessage() .
            '</b><br /> - File: ' . $this->getFile() .
            '<br /> - Line: ' . $this->getLine();
        $str .= '<br /><hr /><b>Stack Trace</b><pre>';
        $str .= $this->getTraceAsString();

        return $str;
    }
}