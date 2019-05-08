<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReestrSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reestr-search well">
	<h2>Сортировка</h2><hr />
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'sort1')->dropDownList([''=>'- не сотировать'] + $model->attributeLabels()) ?>

    <?= $form->field($model, 'sort2')->dropDownList([''=>'- не сотировать'] + $model->attributeLabels()) ?>

    <?= $form->field($model, 'sort3')->dropDownList([''=>'- не сотировать'] + $model->attributeLabels()) ?>

    <?= $form->field($model, 'sort4')->dropDownList([''=>'- не сотировать'] + $model->attributeLabels()) ?>

    <?= $form->field($model, 'sort5')->dropDownList([''=>'- не сотировать'] + $model->attributeLabels()) ?>

    

    <div class="form-group">
        <?= Html::submitButton('Сортировать', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
