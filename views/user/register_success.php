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
<div class="register-success">

    <h1><?= Html::encode($this->title) ?></h1>    

    <p>Вы успешно зарегистрировались и можете <?= Html::a('зайти в систему', ['site/login']) ?></p>


</div>
