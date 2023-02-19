<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var boolean $isGuest */

$this->title = 'Цифровой помощник';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Цифровой помощник</h1>

        <p>
        <?php if($isGuest) {
            echo Html::a('Регистрация', ['user/register'], ['class' => 'btn btn-success']),' ',
            Html::a('Вход', ['site/login'], ['class' => 'btn btn-success']);
        } else {
            echo Html::a('Проекты', ['project/index'], ['class' => 'btn btn-warning']),' ',
            Html::a('Витрина пользователей', ['user/list'], ['class' => 'btn btn-warning']),' ',
            Html::a('Пользователи', ['user/index'], ['class' => 'btn btn-warning']),' ',
            Html::a('Пояснения', ['help-message/index'], ['class' => 'btn btn-warning']);
        }
        ?>
        </p>
    </div>
    
</div>
