<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('zoo', 'MENU_INDEX_TITLE');
$this->params['breadcrumbs'][] = $this->title;


?>
    <p>
        <?= Html::a(Yii::t('zoo', 'MENU_CREATE_ITEM_BUTTON'), ['update'], ['class' => 'uk-button uk-button-small uk-button-primary']); ?>
    </p>

<?php foreach ($group as $menu => $g) : ?>

    <h2><?= $menu ?></h2>

    <ul uk-tab>
        <?php foreach (array_keys($g) as $lang) : ?>
            <li><a href="#"><?= $lang ?></a></li>
        <?php endforeach; ?>
    </ul>
    <ul class="uk-switcher">
    <?php foreach ($g as $lang => $items) : ?>
        <table class="uk-table uk-table-striped uk-table-small">
            <?php foreach ($items as $item) : ?>
                <tr data-item-id="<?=$item->id?>">
                    <td><?= Html::a($item->name,['update','id'=>$item->id])?></td>
                    <td><?= \yii\helpers\Url::to($item->url) ?></td>
                    <td class="uk-text-right">
                        <a data-up href="#"><span uk-icon="icon: chevron-up"></span></a>
                    </td>
                    <td class="uk-text-left">
                        <a data-down href="#"><span uk-icon="icon: chevron-down"></span></a>
                    </td>
                    <td class="uk-text-right">
                        <?= Html::a(null, ['delete', 'id' => $item->id],
                            ["uk-icon"=>"icon: trash; ratio: 1", 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
    </ul>

<?php endforeach; ?>

<?php

$url = \yii\helpers\Url::to(['sort']);

$script = <<<JS
    $(document).on("click","[data-up]", function(e) {
        e.preventDefault();
        var row = $(this).parents("tr");
        row.prev().before(row);
        var data = [];
        row.parents("table").find("tr").each(function(index, item) {
          data[$(this).data("item-id")] = index;
        })
        $.post("$url",{sort:data}).then(function(data) {
            UIkit.notification(data.message);
        });
    });
    $(document).on("click","[data-down]", function(e) {
        e.preventDefault();
        var row = $(this).parents("tr");
        row.next().after(row);
        var data = [];
        row.parents("table").find("tr").each(function(index, item) {
          data[$(this).data("item-id")] = index;
        })
        $.post("$url",{sort:data}).then(function(data) {
            UIkit.notification(data.message);
        });
    });
JS;

$this->registerJs($script, $this::POS_READY);
