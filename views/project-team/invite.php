<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProjectTeam $model */
/** @var yii\widgets\ActiveForm $form */
/** @var $users array */

$this->title = 'Пригласить в проект';
$this->params['breadcrumbs'][] = ['label' => 'Витрина пользователей', 'url' => ['user/list']];
$this->params['breadcrumbs'][] = ['label' => $model->user->fio, 'url' => ['user/profile', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-team-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="project-team-form">

        <?php $form = ActiveForm::begin(); ?>        

        <?= $form->field($model, 'project_id')->dropDownList($projects) ?>

        <div class="form-group">
            <?= Html::submitButton('Пригласить в проект', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
