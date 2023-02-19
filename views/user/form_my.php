<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $roles */

$this->title = 'Изменить информацию о себе';
$this->params['breadcrumbs'][] = ['label' => 'Профиль', 'url' => ['my']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>    

    <div class="user-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => 100]) ?>

        <?= $form->field($model, 'fio')->textInput(['maxlength' => 50]) ?>

        <?= $form->field($model, 'photoFile')->fileInput() ?>

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'about')->textArea(['rows' => 6]) ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => 50]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
