<?php
namespace CashbackApi\Restrictions\Html;

use CashbackApi\Restrictions\Type;
use Giraffe\Giraffe;

/**
 * Class RestrictionWindow
 * @package CashbackApi\Restrictions
 */
class RestrictionWindow extends BaseHtml
{
    public function __construct($restrictions, $showTrash = true, $showEdit = true)
    {
        ob_start();
        ?>
        <div class="restrictionWindow">

            <h5>Restrictions <a href="#" class="refreshRestrictions"><i class="fa fa-refresh"
                                                                        aria-hidden="true"></i></a></h5>

            <div class="restrictionWindowFrame" style="border-radius: 3px;
            border: solid 1px #ccc;padding:2px;background-color:#f2f2f2;">
                <?php
                if ($showEdit) {
                    ?>
                    <div class="restrictionControl" style="border-radius: 2px;vertical-align:top;display:inline-block;min-height: 24px;line-height: 24px;
                            min-width:100%;overflow: hidden;font-size: 12px;background-color:#999;text-indent: 5px;">
                        <a href="#" style="color:#FFF;" class="restrictionAdd"><i class="fa fa-plus-circle"
                                                                                  aria-hidden="true"></i> Add
                            Restriction</a>
                    </div>
                    <?php
                }

                if (Giraffe::canIterate($restrictions)) {
                    $count = count($restrictions);
                    $i = 0;
                    foreach ($restrictions as $restrict) {
                        $i++;
                        ?>
                        <div class="restrictionWindowRow"
                             style="vertical-align:top;line-height: 30px; <?= ($i != $count) ? 'border-bottom:solid 1px #FFF;' : '' ?>"
                             title="<?= $restrict->Type->getDescription() ?? 'no description' ?>">
                            <div style="vertical-align:top;display:inline-block;min-height: 34px;line-height: 34px;
                    font-size: 14px; min-width:130px; font-weight: bold; text-indent: 10px;">
                                <?= Type::getLabelName($restrict->type) ?>
                            </div>
                            <div style="vertical-align:top;display:inline-block;min-height: 20px;margin-left:15px;line-height: 34px;
                            max-width:200px;overflow: hidden;font-size: 14px;">
                                <?= $restrict->Type->displayValue(); ?>
                            </div>
                            <?php
                            if ($showTrash) {
                                ?>
                                <div style="vertical-align:top;float:right;min-width:30px;">
                                    <a href="#" class="restrictionRemove" data-id="<?= $restrict->id ?>"><i
                                                class="fa fa-trash" aria-hidden="true"></i></a>
                                </div>
                                <?php
                            }
                            ?>
                            <?php
                            if ($showEdit) {
                                ?>
                                <div style="vertical-align:top;float:right;min-width:30px;">
                                    <a href="#" class="restrictionUpdate" data-id="<?= $restrict->id ?>">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <?php
                            }
                            ?>

                        </div>

                        <?php

                    }
                } else {
                    echo 'No Restrictions';
                }
                ?>
            </div>

        </div>
        <?php

        $this->output = ob_get_clean();
    }
}