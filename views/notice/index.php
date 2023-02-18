<?php

use app\models\Notice;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\NoticeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Уведомления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-index">

    <h1><?= Html::encode($this->title) ?></h1>
    

    <?php Pjax::begin(); ?>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function(Notice $model) {
            return $model->is_viewed ? [] : ['style' => 'font-weight: bold;'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'project_id',
                'value' => function(Notice $model) {
                    return $model->project->name;
                }
            ],            
            'date_in:datetime',
            'content:raw',
            //'is_viewed:boolean',
            [
                'class' => ActionColumn::class,
                'template' => '{view}',

            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
