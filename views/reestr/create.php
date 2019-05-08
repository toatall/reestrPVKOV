<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Reestr */

$this->title = 'Добавить запись';
$this->params['breadcrumbs'][] = ['label' => 'Реестр', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reestr-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
