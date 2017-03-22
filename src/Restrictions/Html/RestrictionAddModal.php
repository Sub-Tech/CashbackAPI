<?php
namespace CashbackApi\Restrictions\Html;

use CashbackApi\BaseApi;
use CashbackApi\Restrictions\Type;
use Giraffe\Giraffe;

/**
 * Class RestrictionUpdateModal
 * @package CashbackApi\Restrictions\Html
 */
class RestrictionAddModal extends BaseHtml
{
    /**
     * @var null
     */
    protected $api = null;

    public function __construct(BaseApi $api = null, $currentResourceType = 'whitelabel', $currentResourceId = 0)
    {
        parent::__construct($api);
        if (!isset($this->api)) {
            return;
        }

        ob_start();
        ?>
        <style>
            .search-result {
                padding: 3px;
                border-bottom: solid 1px #cccc;
                border-radius: 2px;
            }

            .search-result.active {
                background: #1b3047;
                color: #fff;
                padding: 3px;
            }

        </style>
        <select class="restrictSelect">
            <option>Choose</option>
            <?php
            $typesList = $this->getTypesList();
            if ($typesList) {
                foreach ($typesList as $type) {

                    ?>
                    <option value="<?= $type->type ?>"
                            data-description="<?= addslashes($type->description) ?>"
                            data-show="<?= addslashes($type->type) ?>-add-outer"
                    >
                        <?= Type::getLabelName($type->type) ?>
                    </option>
                    <?php
                }
            }
            ?>
        </select>
        <?php
        foreach ($typesList as $type) {
            $type->Type = new Type($type, $api);
            $resource_blacklist = ($type->type == 'resource_blacklist') ? true : false;
            ?>
            <div id="<?= $type->type ?>-add-outer" class="add-type-form"
                 style="display:none;">
                <form id="<?= $type->type ?>-add-form">
                    <input type="hidden" name="restriction_type" value="<?= $type->type ?>"/>
                    <?php
                    if ($resource_blacklist) {
                        echo '<h5>' . $type->Type->getTitle() . '</h5>';
                        echo '<h6>' . $type->Type->getDescription() . '</h6>';
                        ?>

                        <select class="resourceTypeSelect">
                            <option>Choose</option>
                            <option value="retailer">Retailer</option>
                            <option value="category">Category</option>
                            <?php
                            if (is_a($api, 'CashbackApi\\Reseller\\BaseReseller')) {
                                ?>
                                <option value="whitelabel">Whitelabel</option>
                                <?php
                            }
                            ?>
                        </select>
                        <input style="display:none;" type="text" name="search" id="search-resources"
                               placeholder="Search"/>
                        <input type="hidden" name="retailer_id" id="retailer_id-search"/>
                        <div id="search-resources-results-1"></div>
                        <input style="display:none;" type="text" name="search_offer" id="search-offers"
                               placeholder="Search Offer"/>
                        <div id="search-resources-results-2"></div>
                        <?php
                    }
                    ?>
                    <?= $type->Type->getInputsHtml($resource_blacklist); ?>
                    <input type="hidden" name="set_to_resource_type"
                           value="<?= $currentResourceType ?>"/>
                    <input type="hidden" name="set_to_resource_id"
                           value="<?= $currentResourceId ?>"/>
                    <hr/>
                    <input class="btn-sm" type="submit" value="Add Restriction"/>
                </form>
            </div>
            <?php

        }
        $this->output = ob_get_clean();
    }


    public function searchResultsHtml($resultData, $retailerId = null)
    {
        $retailerId = $retailerId ?? 0;
        if (!Giraffe::canIterate($resultData)) {
            return 'no results';
        }
        $returnValue = '';
        foreach ($resultData as $data) {
            $id = 0;
            $ret = '';
            $type = '';
            if (isset($data->whitelabel_id)) {
                $id = $data->whitelabel_id;
                $type = 'whitelabel';
            }
            if (isset($data->category_id)) {
                $id = $data->category_id;
                $type = 'category';
            }
            if (isset($data->retailer_id)) {
                $id = $data->retailer_id;
                $ret = ' data-retailer_id="' . $data->retailer_id . '" ';
                $type = 'retailer';
            }
            if (isset($data->offer_id)) {
                $id = $data->offer_id;
                $ret = ' data-retailer_id="' . $retailerId . '" ';
                $type = 'offer';
            }
            $name = $data->name;
            $returnValue .= '<div class="search-result" data-type="' . $type .
                '" data-id="' . $id .
                '" ' . $ret . ' >' .
                "<i class=\"fa fa-plus-square-o\" aria-hidden=\"true\"></i> {$name}" . '</div>';
        }

        return $returnValue;
    }


}