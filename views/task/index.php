<?php

use app\models\Task;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\TaskSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var boolean $canEdit */

?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if($canEdit) {
            echo Html::a('Создать', ['task/create', 'projectId' => $searchModel->project_id], ['class' => 'btn btn-success']);
        }
        ?>
    </p>

    <?php Pjax::begin(); ?>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],                        
            'name',
            [
                'attribute' => 'user_id',
                'value' => function(Task $model) {
                    return $model->user ? $model->user->fio : null;
                }
            ],
            'date_plan:date',
            'is_complete:boolean',
            [
                'class' => ActionColumn::class,
                'controller' => 'task',
                'template' => '{update} {delete}',
                'visible' => $canEdit,
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
