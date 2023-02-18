<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $roles */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-register">

    <h1><?= Html::encode($this->title) ?></h1>    

    <div class="user-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => 100]) ?>

        <?= $form->field($model, 'fio')->textInput(['maxlength' => 50]) ?>                

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Регистрация', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
