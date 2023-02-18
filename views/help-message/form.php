<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HelpMessage $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $models */
/** @var array $attrs */

$this->title = $model->isNewRecord ? 'Добавить подсказку' : 'Изменить подсказку';
$this->params['breadcrumbs'][] = ['label' => 'Подсказки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="help-message-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="help-message-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'model')->dropDownList($models) ?>

        <?= $form->field($model, 'attr')->dropDownList($attrs) ?>

        <?= $form->field($model, 'content')->textArea(['rows' => 3]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
