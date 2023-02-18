<?php

use app\models\ProjectTeam;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\ProjectTeamSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>
<div class="project-team-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['project-team/create', 'projectId' => $searchModel->project_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],                        
            [
                'attribute' => 'user_id',
                'value' => function(ProjectTeam $model) {
                    return $model->user->fio;
                }
            ],            
            'role',
            [
                'label' => 'Достижения',
                'value' => function(ProjectTeam $model) {
                    return $model->user->getGameTitles();
                },
                'format' => 'raw',
            ],
            [
                'class' => ActionColumn::class,
                'controller' => 'project-team',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
