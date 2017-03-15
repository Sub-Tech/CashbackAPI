<?php
namespace CashbackApi\Restrictions;

use Giraffe\Giraffe;

/**
 * Class RestrictionWindow
 * @package CashbackApi\Restrictions
 */
class RestrictionWindow
{
    public function __construct($restrictions, $showTrash = true, $showEdit = true)
    {
        ?>
        <div class="restrictionWindow">

            <h5>Restrictions</h5>
            <a href="#" class="refreshRestrictions"><i class="fa fa-refresh" aria-hidden="true"></i></a>
            <div class="restrictionWindowFrame" style="border-radius: 5px;
    border: solid 1px #ccc;padding:5px;background-color:#f2f2f2;">

                <?php
                if (Giraffe::canIterate($restrictions)) {
                    $count = count($restrictions);
                    $i = 0;
                    foreach ($restrictions as $restrict) {
                        $i++;
                        ?>
                        <div class="restrictionWindowRow"
                             style="vertical-align:top;line-height: 30px; <?= ($i != $count) ? 'border-bottom:solid 1px #8c8c8c;' : '' ?>"
                             title="<?= $restrict->Type->getDescription() ?? 'no description' ?>">
                            <div style="vertical-align:top;display:inline-block;min-height: 20px;line-height: 20px;
                    font-size: 14px; min-width:130px;">
                                <?= Type::getLabelName($restrict->type) ?>
                            </div>
                            <div style="vertical-align:top;display:inline-block;min-height: 20px;margin-left:15px;line-height: 20px;
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
                                    <a href="#" class="restrictionEdit" data-id="<?= $restrict->id ?>"><i
                                                class="fa fa-pencil" aria-hidden="true"></i></a>
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
    }
}