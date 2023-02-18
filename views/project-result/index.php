<?php

use app\models\ProjectResult;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\ProjectResultSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>
<div class="project-result-index">

    <h1><?= Html::encode($this->title) ?></h1>    

    <?php Pjax::begin(); ?>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'columns' => [            
            'events',
            'men',
            'publications',
            'views',
            'effect',
            [
                'class' => ActionColumn::class,
                'template' => '{update}',
                'controller' => 'project-result',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
