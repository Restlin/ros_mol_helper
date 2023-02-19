<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $roles */

$this->title = 'Витрина пользователей';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>    

    <?php Pjax::begin(); ?>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'photo',
                'value' => function(User $model) {
                    return $model->photo ?  Html::img(['photo', 'id' => $model->id],['alt' => 'Фото профиля', 'style' => 'width:100px;']) : null;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'fio',
                'value' => function(User $model) {
                    return Html::a($model->fio, ['user/profile', 'id' => $model->id], ['data-pjax' => 0]);
                },
                'format' => 'raw',
            ],            
            [
                'attribute' => 'role',
                'value' => function(User $model) use($roles) {
                    return $roles[$model->role];
                },
                'filter' => $roles,
            ],
            'about:ntext',
            'url:url:Резюме',
            [
                'label' => 'Уровень',
                'value' => function(User $model) {
                    return $model->getGameTitles();
                },
                'format' => 'raw',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
