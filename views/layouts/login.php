<?php

use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
$asset = app\assets\AppAsset::register($this);
$baseurl = $asset->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<?php $this->beginBody() ?>
<body  class="bg-black">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <div class="form-box" id="login-box">
        <div class="header"><?= Html::encode($this->title) ?></div>
        <?= $content ?>
    </div>
</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
