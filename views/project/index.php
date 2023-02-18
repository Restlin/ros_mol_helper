<?php

use app\models\Project;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\ProjectSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $levels */
/** @var array $statuses */

$this->title = 'Проекты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            [
                'attribute' => 'level',
                'value' => function (Project $model) use($levels) {
                    return $levels[$model->level];
                },
                'filter' => $levels,
            ],
            [
                'attribute' => 'author_id',
                'value' => function (Project $model) {
                    return $model->author->fio;
                },
            ],
            'date_start:date',
            'date_end:date',
            [
                'attribute' => 'status',
                'value' => function (Project $model) use($statuses) {
                    return $statuses[$model->status];
                },
                'filter' => $statuses
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Project $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
