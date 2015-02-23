<?php

use yii\helpers\Html;
use dee\adminlte\AdminlteAsset;
use dee\angular\assets\AngularRouteAsset;


/* @var $this \yii\web\View */
/* @var $content string */
AdminlteAsset::register($this);
AngularRouteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" ng-app="dApp">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <?php $this->beginBody() ?>
    <body class="skin-blue">
        <header class="header">
            <?php echo $this->render('heading'); ?>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <aside class="right-side">
                <section class="content-header">
                    <h1>
                        <?= '&nbsp;' . Html::encode($this->title) ?>
                        <small></small>
                    </h1>
                </section>
                <section class="content">
                    <div ng-view></div>
                </section>
            </aside>            
            <aside class="left-side sidebar-offcanvas">
                <?php echo $this->render('sidebar'); ?>
            </aside>
        </div>

        <!--        <footer class="footer">
                    <div class="container">
                        <p class="pull-left">&copy; My Company <?= ''//date('Y')              ?></p>
                        <p class="pull-right"><?= ''//Yii::powered()              ?></p>
                    </div>
                </footer>-->
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
