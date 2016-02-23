<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use backend\assets\AppAsset;
use yii\widgets\Breadcrumbs;
/**
 * @var $content string
 */

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    [
                        'label'=>'Настройки',
                        'items'=> [
                            ['label' => 'Статистика', 'url' => ['/main/index']],
                            ['label' => 'Общие настройки', 'url' => ['/main/general']],
                            ['label' => 'Администраторы', 'url' => ['/admin']],
                        ],
                    ],
                    [
                        'label' => 'Списки',
                        'items' => [
                            ['label'=>'Города' , 'url'=>['/city']],
                            ['label'=>'Фирмы', 'url'=>['/firm']],
                            ['label'=>'Аптеки', 'url'=>['/pharmacy']],
                            ['label'=>'Образование', 'url'=>['/education']],
                            ['label'=>'Должности', 'url'=>['/position']],
                            ['label' => 'Баннеры', 'url' => ['/banner']],
                            ['label' => 'Вещества', 'url' => ['/substance']],
                            ['label' => 'Запросы', 'url' => ['/substances/request']],
                        ],
                    ],
                    ['label' => 'Страницы',
                        'items'=>[
                            ['label' => 'Страницы', 'url' => ['/block']],
                            ['label' => 'Комментарии', 'url' => ['/blocks/comment']],
                            ['label' => 'Оценки', 'url' => ['/blocks/mark']],
                        ],
                    ],
                    ['label' => 'Пользователи',
                        'items'=>[
                            ['label' => 'Пользователи', 'url' => ['/user']],
                            ['label' => 'Оповещения', 'url' => ['/user/push']],
                            ['label' => 'Подарки', 'url' => ['/users/present']],
                        ],
                    ],
                    ['label'=>'Анкеты',
                        'items'=>[
                            ['label'=>'Анкеты', 'url'=>['/survey']],
                            ['label'=>'Ответы', 'url'=>['/surveys/answer']],
                         ],
                    ],
                    ['label'=>'Презентации',
                        'items'=>[
                            ['label'=>'Презентации', 'url'=>['/presentation']],
                            ['label'=>'Комментарии', 'url'=>['/presentations/comment']],
                            ['label'=>'Ответы', 'url'=>['/presentations/answer']],
                        ],
                    ],
                    ['label'=>'Фабрики',
                        'items'=>[
                            ['label'=>'Фабрики', 'url'=>['/factory']],
                            ['label'=>'Акции', 'url'=>['/factories/stock']],
                            ['label'=>'Продукты', 'url'=>['/factories/product']],
                            ['label'=>'Ответы', 'url'=>['/factories/stocks/answer']],
                        ],
                    ],

                    ['label'=>'Семинары',
                        'items'=>[
                            ['label'=>'Семинары', 'url'=>['/seminar']],
                            ['label'=>'Записи', 'url'=>['/seminars/sign']],
                            ['label'=>'Комментарии', 'url'=>['/seminars/comment']],
                        ],
                    ],

                    ['label'=>'Подарки',
                        'items'=>[
                            ['label'=>'Подарки', 'url'=>['/present']],
                            ['label'=>'Поставщики', 'url'=>['/presents/vendor']],
                        ],
                    ],
                    ['label' => 'Выход', 'url' => ['/auth/logout']],
                ],
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?=
        Breadcrumbs::widget([
            'homeLink' => [
                'label' => 'Презентации',
                'url' => ['/presentation'],
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]);
        ?>
            <?= $content ?>
        </div>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
