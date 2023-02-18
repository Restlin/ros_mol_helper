<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Publication $model */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\Project $project */
/** @var array $events */

$this->title = $model->isNewRecord ? 'Создать публикацию' : 'Изменить публикацию';
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['project/index']];
$this->params['breadcrumbs'][] = ['label' => $project->name, 'url' => ['project/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="publication-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="publication-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'event_id')->dropDownList($events) ?>

        <?= $form->field($model, 'type')->dropDownList($types) ?>

        <?= $form->field($model, 'date_in')->widget(DatePicker::class) ?>

        <?= $form->field($model, 'link')->textInput(['maxlength' => 100]) ?>

        <?= $form->field($model, 'views')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
