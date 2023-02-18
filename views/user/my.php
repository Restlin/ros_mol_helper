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

    <p>
        <?= Html::a('Подключить tg бота', ['tg/connect', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [            
            'email:email',
            'fio',
            'tg_id',            
            [
                'attribute' => 'role',
                'value' => $roles[$model->role],
            ],
            'photo',
        ],
    ]) ?>

</div>
