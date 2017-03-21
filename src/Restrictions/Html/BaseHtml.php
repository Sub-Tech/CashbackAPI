<?php
namespace CashbackApi\Restrictions\Html;


/**
 * Class BaseHtml
 * @package CashbackApi\Restrictions\Html
 */
abstract class BaseHtml
{
    protected $output = '';

    public function html()
    {
        echo $this->output;
    }
}