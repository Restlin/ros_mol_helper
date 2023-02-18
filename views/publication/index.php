<?php

use app\models\Publication;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\PublicationSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $types */

?>
<div class="publication-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['publication/create', 'projectId' => $searchModel->projectId], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],            
            [
                'attribute' => 'event_id',
                'value' => function(Publication $model) {
                    return $model->event->name;
                }
            ],
            [
                'attribute' => 'type',
                'value' => function(Publication $model) use($types) {
                    return $types[$model->type];
                },
                'filter' => $types,
            ],
            'date_in:date',
            'link',
            'views',
            [
                'class' => ActionColumn::class,
                'controller' => 'publication',
                'template' => '{update} {delete}',
               
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
