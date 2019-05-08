<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use app\models\Files;

/* @var $this yii\web\View */
/* @var $model app\models\Reestr */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Реестр', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reestr-view">

    <h1>#<?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code_no',
            'org_inspection',
            'period_inspection',
            'year_inspection',
        	[
        		'label'=>'Материалы проверки',
        		'value'=>Files::filesByReestrId($model->id),
        		'format'=>'raw',
    		],
            'theme_inspection',
        	'data_akt_ref',
            'question_inspection',
            'violations:ntext',
            'answers_no',
            'mark_elimination_violation',
            'measures:ntext',
            'description:ntext',
            'date_create',
            'date_edit',
            'log_change:ntext',
        ],
    ]) ?>

</div>
