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

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'email',
                'value' => function(User $model) {
                    return $model->email;
                },
            ],            
            'fio',
            [
                'attribute' => 'role',
                'value' => function(User $model) use($roles) {
                    return $roles[$model->role];
                },
                'filter' => $roles,
            ],            
            //'tg_id',
            [
                'class' => ActionColumn::class,
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
