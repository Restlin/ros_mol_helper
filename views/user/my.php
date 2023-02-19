<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var array $roles */

$this->title = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div><?php if($model->photo) {
        echo Html::img(['photo', 'id' => $model->id],['alt' => 'Фото профиля', 'style' => 'width:200px; margin: 10px; border: 1px solid #ddd;']);
    }?></div>
    <p>
        <?php if($model->tg_id) {
            echo Html::a('Тест tg бота', ['tg/test', 'id' => $model->id], ['class' => 'btn btn-primary']),' ';
        } else {
            echo Html::a('Подключить tg бота', ['tg/connect', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
        }
        echo Html::a('Изменить профиль', ['update-my'], ['class' => 'btn btn-success']);
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [            
            'email:email',
            'fio',
            'about:ntext',
            'url:url',
            'tg_id',   
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
