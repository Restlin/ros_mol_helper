<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\Tabs;
use app\models\Project;

/** @var yii\web\View $this */
/** @var app\models\Project $model */
/** @var array $levels */
/** @var array $statuses */
/** @var string $teamIndex */
/** @var string $resultIndex */
/** @var string $taskIndex */
/** @var string $eventIndex */
/** @var string $publicationIndex */
/** @var string $logIndex */
/** @var boolean $canEdit */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$percent = $model->readyPercent();
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if($canEdit && $percent == 100) {
            echo Html::a('Отправить на проверку', ['check', 'id' => $model->id], ['class' => 'btn btn-success']),' ';
        }
        if($canEdit) {
            echo Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']),' ',            
            Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить проект?',
                    'method' => 'post',
                ],
            ]),' ';
        }
        if($isAdmin && $model->status == Project::STATUS_CHECK) {
            echo Html::a('Принять заявку', ['accept', 'id' => $model->id], ['class' => 'btn btn-warning']);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'level',
                'value' => $levels[$model->level],
            ],
            'date_start:date',
            'date_end:date',
            [
                'attribute' => 'author_id',
                'value' => $model->author->fio,
            ],
            [
                'attribute' => 'status',
                'value' => $statuses[$model->status],
            ],
            [
                'label' => 'Процент заполненности',
                'value' => $percent,
            ],
            [
                'label' => 'Достижение вашего проекта',
                'value' => $model->gameTitle($percent),
            ],
        ],
    ]) ?>

</div>
<?= Tabs::widget([
    'items' => [
        [
            'label' => 'Команда',
            'content' => $teamIndex,
            'active' => $tab == 'team'
        ],
        [
            'label' => 'Результат',
            'content' => $resultIndex,
            'active' => $tab == 'result'
        ],
        [
            'label' => 'Задачи',
            'content' => $taskIndex,
            'active' => $tab == 'task'
        ],
        [
            'label' => 'Мероприятия',
            'content' => $eventIndex,
            'active' => $tab == 'event'
        ],
        [
            'label' => 'Публикации',
            'content' => $publicationIndex,
            'active' => $tab == 'publication'
        ],
        [
            'label' => 'Изменения',
            'content' => $logIndex,
            'active' => $tab == 'log',
            'visible' => $canEdit
        ],
    ],
]) ?>
