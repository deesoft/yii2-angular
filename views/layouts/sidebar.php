<?php

use app\components\SideMenu;
use mdm\admin\components\MenuHelper;

/* @var $this yii\web\View */
?>
<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="/img/avatar04.png" class="img-circle" alt="User Image" />
        </div>
        <div class="pull-left info">
            <p>Hello, <?php echo (!Yii::$app->user->isGuest) ? Yii::$app->user->identity->username : 'Guest'; ?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <?php
    $menuCallback = function($menu) {
        $item = [
            'label' => $menu['name'],
            'url' => MenuHelper::parseRoute($menu['route']),
        ];
        if (!empty($menu['data'])) {
            $item['icon'] = 'fa ' . $menu['data'];
        } else {
            $item['icon'] = 'fa fa-angle-double-right';
        }
        if ($menu['children'] != []) {
            $item['items'] = $menu['children'];
        }
        return $item;
    };

    $items = MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $menuCallback);
    //$items = [];
    echo SideMenu::widget([
        'items' => $items,
    ]);
    ?>

</section>
<!-- /.sidebar -->
