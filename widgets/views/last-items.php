<?php

use yii\helpers\Html;

?>
<?php if (!empty($title)): ?>
    <h3 class="uk-panel-title"><?= Html::encode($title) ?></h3>
<?php endif ?>

<div class="last-items-list">
    <ul<?= $list_class ? ' class="' . $list_class . '"' : '' ?>>
        <?php foreach ($items as $key => $item): ?>
            <li>
                <?= \worstinme\zoo\helpers\TemplateHelper::render($item, 'list-item') ?>
            </li>
        <?php endforeach ?>
    </ul>
</div>