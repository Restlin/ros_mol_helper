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
/** @var boolean $canEdit */
/** @var array $types */

?>
<div class="project-team-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if($canEdit) {
            echo Html::a('Добавить', ['project-team/create', 'projectId' => $searchModel->project_id], ['class' => 'btn btn-success']);
        }?>
    </p>

    <?php Pjax::begin(); ?>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'user.photo',
                'value' => function(ProjectTeam $model) {
                    return $model->user->photo ?  Html::img(['user/photo', 'id' => $model->user_id],['alt' => 'Фото профиля', 'style' => 'width:100px;']) : null;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'user_id',
                'value' => function(ProjectTeam $model) {
                    return $model->user->fio;
                }
            ],
            [
                'attribute' => 'type',
                'value' => function(ProjectTeam $model) use($types) {
                    return $types[$model->type];
                },
                'filter' => $types,
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
                'visible' => $canEdit,
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
