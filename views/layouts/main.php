<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use kartik\icons\FontAwesomeAsset;
use app\models\Notice;
use app\models\HelpMessage;

FontAwesomeAsset::register($this);
AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$user = Yii::$app->user->isGuest ? null : Yii::$app->user->getIdentity()->user;
$notices = $user ? Notice::actualCount($user) : 0;


$this->registerJsVar('helpMessages', HelpMessage::getList());

$js = "$(function(){
        $('body').on('focus', 'input, checkbox, select', function() {
            console.log(helpMessages);
            let name = this.name;
            if(helpMessages[name]) {
                $('#help-message-content').html(helpMessages[name]);
                $('#help-message').show();
            } else {
                $('#help-message').hide();
            }
        });
        $('body').on('click', '#btn-close-help-message', function() {
            $('#help-message').hide();
        });
    }
)";
$this->registerJs($js);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandImage' => '@web/images/logo.svg',
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-warning fixed-top']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Меню', 'url' => ['/site/index']],
            ['label' => "Уведомления [$notices]", 'url' => ['/notice/index'], 'visible' => $user ? true : false],
            ['label' => 'Профиль', 'url' => ['/user/my'], 'visible' => $user ? true : false],
            Yii::$app->user->isGuest
                ? ['label' => 'Вход', 'url' => ['/site/login']]
                : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Выход (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
        ]
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <div id="help-message">
            <input type="button" value="X" id="btn-close-help-message" class="btn btn-sm">
            <div id="help-message-content"></div>            
        </div>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; IT-animals, хакатон "Молодежный бит"  <?= date('Y') ?></div>            
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
