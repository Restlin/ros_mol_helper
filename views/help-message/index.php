<?php

use app\models\HelpMessage;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\HelpMessageSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $models */
/** @var array $attrs */

$this->title = 'Подсказки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="help-message-index">

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
                'attribute' => 'model',
                'value' => function(HelpMessage $model) use($models) {
                    return $models[$model->model];
                },
                'filter' => $models,
            ],
            [
                'attribute' => 'attr',
                'value' => function(HelpMessage $model) use($attrs) {
                    return $attrs[$model->attr];
                },
                'filter' => $attrs,
            ],            
            'content',
            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
