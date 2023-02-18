<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProjectResult $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Ввести результат проект';
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['project/index']];
$this->params['breadcrumbs'][] = ['label' => $model->project->name, 'url' => ['project/view', 'id' => $model->project->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-result-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="project-result-form">

        <?php $form = ActiveForm::begin(); ?>        

        <?= $form->field($model, 'events')->textInput() ?>

        <?= $form->field($model, 'men')->textInput() ?>

        <?= $form->field($model, 'publications')->textInput() ?>

        <?= $form->field($model, 'views')->textInput() ?>

        <?= $form->field($model, 'effect')->textInput(['maxlength' => 200]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>

