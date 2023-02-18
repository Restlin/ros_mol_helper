<?php

use app\models\Event;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\EventSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>
<div class="event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['event/create', 'projectId' => $searchModel->project_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],            
            'name',
            'date_plan:date',
            'is_complete:boolean',
            [
                'label' => 'Кол-во публикаций',
                'value' => function(Event $model) {
                    return count($model->publications);
                }
            ],
            [
                'label' => 'Кол-во просмотров',
                'value' => function(Event $model) {
                    $views = 0;
                    foreach($model->publications as $publication) {
                        $views += $publication->views;
                    }
                    return $views;
                }
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
                'controller' => 'event'
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
