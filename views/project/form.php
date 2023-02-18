<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Project $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $levels */

$this->title = $model->isNewRecord ?  'Создать проект' : 'Изменить проект';
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-create">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 100, 'title' => 'bla bla bla']) ?>

    <?= $form->field($model, 'level')->dropDownList($levels) ?>

    <?= $form->field($model, 'date_start')->widget(DatePicker::class) ?>

    <?= $form->field($model, 'date_end')->widget(DatePicker::class) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


</div>
