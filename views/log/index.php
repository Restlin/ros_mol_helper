<?php

use app\models\Log;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\LogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
?>
<div class="log-index">

    <h1><?= Html::encode($this->title) ?></h1>    

    <?php Pjax::begin(); ?>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],            
            [
                'attribute' => 'user_id',
                'value' => function(Log $model) {
                    return $model->user->fio;
                }
            ],
            'date_in:datetime',
            'content:ntext',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
