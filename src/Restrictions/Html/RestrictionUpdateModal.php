<?php
namespace CashbackApi\Restrictions\Html;

use CashbackApi\BaseApi;
use CashbackApi\Restrictions\Type;

/**
 * Class RestrictionUpdateModal
 * @package CashbackApi\Restrictions\Html
 */
class RestrictionUpdateModal
{
    /**
     * @var null
     */
    protected $api = null;

    public function __construct(BaseApi $api = null, $currentResourceType = 'whitelabel', $currentResourceId = 0)
    {
        if (isset($api)) {
            $this->setApi($api);
        }
        $api = $this->getApi();
        if ($api == null) {
            return;
        }
        $typesList = $api->getApiRestrictions()->getRestrictionTypesForList(true);

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
            ?>
        </select>
        <?php
        foreach ($typesList as $type) {
            $type->Type = new Type($currentResourceType, $type, null, $api);
            $resource_blacklist = ($type->type == 'resource_blacklist') ? true : false;
            ?>
            <div id="<?= $type->type ?>-add-outer" class="add-type-form"
                 style="display:none;">
                <form id="<?= $type->type ?>-add-form">
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
                        <input style="display:none;" type="text" name="search_offer" id="search-offers"
                               placeholder="Search Offer"/>
                        <input type="hidden" name="retailer_id" id="retailer_id-search"/>
                        <div id="search-resources-results-1"></div>
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

    }

    /**
     * @return null
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param null $api
     */
    public function setApi(BaseApi $api)
    {
        $this->api = $api;
    }

}