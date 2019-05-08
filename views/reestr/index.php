<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Ifns;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReestrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Реестр проверок органами государственного контроля и надзора';
$this->params['breadcrumbs'][] = $this->title;


$this->registerCss('.grid-view th { white-space: pre-wrap; }');
//$this->registerCss('.grid-view { font-size: 13px; }');

?>
<div class="reestr-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить запись', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Экспорт в Excel', ['excel'], ['class' => 'btn btn-info', 'id' => 'btnExportExcel']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
    	'id' => 'filterReestrGridView',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
            	'attribute' => 'code_no',
            	'filter' => ArrayHelper::map(Ifns::find()->where('disable_no=0')->asArray()->all(), 'code_no', 'code_no'),
            	'headerOptions'=>['style'=>'width:50px;', 'title'=>'asdasdasds'],
        	],            
            'org_inspection',
            'period_inspection',
            'year_inspection',
            'theme_inspection',
            'question_inspection',
            'violations:ntext',
            'answers_no',
            [
                'attribute'=>'mark_elimination_violation',
                'contentOptions' => function($model, $key, $index, $column) {
                    if ($model->mark_elimination_violation == 'не устранено')
                        return ['class'=>'danger'];
                    if ($model->mark_elimination_violation == 'проверка не завершена ')
                        return ['class'=>'warning'];
                    return [];
                },
            ],
            'measures:ntext',
            'description:ntext',
            'listFiles:raw',
            // 'date_create',
            // 'date_edit',
            // 'log_change:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php
/*
	// экспорт в Excel
	$this->registerJs("
		jQuery('#btnExportExcel').on('click', function() {			
			//var strUrlSearch = jQuery('#filterReestrGridView-filters input, #filterReestrGridView-filters select').serialize();
			var url = window.location.toString();
			if (url.indexOf('?') > -1) {
				url = url + '&';
			}
			else
			{
				url = url + '?';
			}
			window.location = url + 'report=1';
			return false;
		});
		
		
");*/ ?>
