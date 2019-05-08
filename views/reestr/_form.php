<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Ifns;
use app\models\Files;
use yii\bootstrap\Modal;
use yii\helpers\Url;



/* @var $this yii\web\View */
/* @var $model app\models\Reestr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reestr-form">
	
	<?php Modal::begin(['id'=>'modal-body', 'header'=>'Список']); ?>
		<div id="modal-content"></div>
	<?php Modal::end(); ?>
	
	
	
    <?php $form = ActiveForm::begin([
    	'options'=>['enctype'=>'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'code_no')->dropDownList(ArrayHelper::map(Ifns::find()->where('disable_no=0')->asArray()->all(),'code_no', 'name_no')) ?>
	
	<?= $form->field($model, 'type_check_organization')->textarea(['rows'=>'3', 'id'=>'input-check-organization']) ?>
	<?= \yii\bootstrap\Html::button('Выбрать...', [
        'data' => [
            'toggle' => 'modal',
            'target' => '#modal-body',
        ],
        'class' => 'btn btn-primary',
        'onclick' => 'js:$.get(\'' . Url::to('checkorganization') . '\').done(function(data) { $(\'#modal-content\').html(data); });',
        
    ]) ?><hr />

    <?= $form->field($model, 'org_inspection')->textInput(['spellCheck'=>'true']) ?>

    <?= $form->field($model, 'period_inspection')->dropDownList([
        '1 кв. 2017' => '1 кв. 2017',
        '2 кв. 2017' => '2 кв. 2017',
        '3 кв. 2017' => '3 кв. 2017',
        '4 кв. 2017' => '4 кв. 2017',
        '1 кв. 2018' => '1 кв. 2018',
        '2 кв. 2018' => '2 кв. 2018',
        '3 кв. 2018' => '3 кв. 2018',
        '4 кв. 2018' => '4 кв. 2018',
        
    ]) ?>

    <?= $form->field($model, 'year_inspection')->textInput(['spellCheck'=>'true']) ?>
   
    <div class="well">
    	<h3>Загрузка файлов</h3>
    	<?= $form->field($model, 'documentFiles[]')->fileInput(['multiple'=>true]) ?>
    	<hr />
    	
    	<?php if (!$model->isNewRecord): ?>
    	<div class="alert alert-info">    		
    		Отметьте файлы для удаления
    	</div>
    	<?= $form->field($model, 'documentFilesDelete')->checkboxList(ArrayHelper::map(Files::find()->where('id_reestr=:id_reestr', 
    				[':id_reestr'=>$model->id])->asArray()->all(), 'id', 'name_file'),
    			['separator'=>'<br />']
    		) ?>
    	<?php endif; ?>
    </div>

    <?= $form->field($model, 'theme_inspection')->textarea(['rows'=>3, 'spellCheck'=>'true', 'id'=>'input-theme']) ?>
    <?= \yii\bootstrap\Html::button('Выбрать...', [
        'data' => [
            'toggle' => 'modal',
            'target' => '#modal-body',
        ],
        'class' => 'btn btn-primary',
        'onclick' => 'js:$.get(\'' . Url::to('theme') . '\').done(function(data) { $(\'#modal-content\').html(data); });',
        
    ]) ?><hr />
            
    <?= $form->field($model, 'data_akt_ref')->textarea(['rows' => 6, 'spellCheck'=>'true']) ?>

    <?= $form->field($model, 'question_inspection')->textarea(['rows' => 6, 'spellCheck'=>'true']) ?>

    <?= $form->field($model, 'violations')->textarea(['rows' => 6, 'spellCheck'=>'true']) ?>

    <?= $form->field($model, 'answers_no')->textInput(['spellCheck'=>'true']) ?>

    <?= $form->field($model, 'mark_elimination_violation')->dropDownList([
        'устранено' => 'устранено',
        'не устранено' => 'не устранено',
        'неустранимое' => 'неустранимое',
        'нарушения не установлены' => 'нарушения не установлены',
        'проверка не завершена ' => 'проверка не завершена',
    ]) ?>

    <?= $form->field($model, 'measures')->textarea(['rows' => 6, 'spellCheck'=>'true']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6, 'spellCheck'=>'true']) ?>    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', 
        	['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
