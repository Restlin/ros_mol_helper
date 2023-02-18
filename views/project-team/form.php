<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProjectTeam $model */
/** @var yii\widgets\ActiveForm $form */
/** @var $users array */

$this->title = $model->isNewRecord? 'Позвать в команду' : 'Изменить';
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['project/index']];
$this->params['breadcrumbs'][] = ['label' => $model->project->name, 'url' => ['project/view', 'id' => $model->project_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-team-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="project-team-form">

        <?php $form = ActiveForm::begin(); ?>        

        <?= $form->field($model, 'user_id')->dropDownList($users) ?>

        <?= $form->field($model, 'role')->textInput(['maxlength' => 100]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
