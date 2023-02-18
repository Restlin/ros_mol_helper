<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\Tabs;

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

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Отправить на проверку', ['check', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Принять заявку', ['accept', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить проект?',
                'method' => 'post',
            ],
        ]) ?>
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
                'label' => 'Процент заполненности',
                'value' => $model->readyPercent(),
            ],
            [
                'attribute' => 'status',
                'value' => $statuses[$model->status],
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
            'active' => $tab == 'log'
        ],
    ],
]) ?>
