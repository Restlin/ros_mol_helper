<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var array $roles */

$this->title = 'Профиль';
$this->params['breadcrumbs'][] = ['label' => 'Витрина пользователей', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div><?php if($model->photo) {
        echo Html::img(['photo', 'id' => $model->id],['alt' => 'Фото профиля', 'style' => 'width:200px; margin: 10px; border: 1px solid #ddd;']);
    }?></div>

    <p>
        <?= Html::a('Пригласить в проект', ['project-team/invite', 'userId' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [            
            'fio',
            'about:ntext',
            'url:url',            
            [
                'attribute' => 'role',
                'value' => $roles[$model->role],
            ],
            [
                'label' => 'Уровень',
                'value' => $model->getGameTitles(),
                'format' => 'raw'
            ],
        ],
    ]) ?>

</div>
