<?php
/* @var $this yii\web\View */
?>
<div class="btn-group">
    <a class="btn btn-primary btn-sm" ng-click="save()">Save</a>
    <a class="btn btn-danger btn-sm" href="#/list">Discard</a>
</div>

<?= $this->render('_form'); ?>
