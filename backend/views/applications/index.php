<?php

use yii\helpers\Html;

$this->title = Yii::t('zoo','APPLICATIONS_INDEX_TITLE');

?>

<div class="applications uk-margin-top">

    <div class="uk-grid uk-child-width-1-3@s uk-grid-match" uk-grid>

		<?php foreach ($applications as $app): ?>

            <div>
                <div class="uk-card uk-card-small uk-card-primary uk-card-hover uk-card-body">
                    <h3 class="uk-card-title">
                        <?=Html::a('<i class="uk-icon-cog"></i> '.$app->title,['/zoo/items/index','app'=>$app->id],
                            ['class'=>'uk-panel uk-border-rounded uk-panel-box uk-text-center'])?>
                    </h3>
                    <p><?=$app->description?></p>
                </div>
            </div>
		
		<?php endforeach ?>
		
	</div>

</div>