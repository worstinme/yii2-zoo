<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('zoo', 'Элементы');
$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo', 'Приложения'), 'url' => ['/' . Yii::$app->controller->module->id . '/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/' . Yii::$app->controller->module->id . '/items/index', 'app' => Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;


?>


<table class="uk-table uk-table-divider uk-table-striped uk-table-hover uk-table-small elements-table">

    <?php if (count($app->systemElements)) : ?>
    <tfoot>
        <?php foreach ($app->systemElements as $key => $element): ?>
            <tr>
                <td><?= $element->label ?></td>
                <td><?= $element->name ?></td>
            </tr>
        <?php endforeach ?>
    </tfoot>
    <?php endif; ?>
    <?php if (count($app->elements)): ?>
        <tbody>
        <?php foreach ($app->elements as $key => $element): ?>
            <tr data-item-id="<?=$element->id?>">
                <td>
                    <?= Html::a($element->label, ['update', 'element' => $element->id, 'app' => $app->id]) ?></td>
                <td><?= $element->name ?></td>
                <td><?= $element->type ?></td>
                <td class="uk-text-right">
                    <a data-up href="#"><span uk-icon="icon: chevron-up"></span></a>
                </td>
                <td class="uk-text-left">
                    <a data-down href="#"><span uk-icon="icon: chevron-down"></span></a>
                </td>
                <td class="uk-text-right">
                    <?= Html::a(null, ['delete', 'element' => $element->id, 'app' => $app->id],
                        ["uk-icon"=>"icon: trash; ratio: 1", 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post']) ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    <?php endif ?>
</table>

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