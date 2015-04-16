<?php

namespace dee\angular;

/**
 * UrlRule
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class UrlRule extends \yii\web\CompositeUrlRule
{
    /**
     * @var string the common prefix string shared by all patterns.
     */
    public $prefix;

    /**
     * @var string the suffix that will be assigned to [[\yii\web\UrlRule::suffix]] for every generated rule.
     */
    public $suffix;

    /**
     * @var string|array the controller ID (e.g. `user`, `post-comment`) that the rules in this composite rule
     * are dealing with. It should be prefixed with the module ID if the controller is within a module (e.g. `admin/user`).
     *
     * By default, the controller ID will be pluralized automatically when it is put in the patterns of the
     * generated rules. If you want to explicitly specify how the controller ID should appear in the patterns,
     * you may use an array with the array key being as the controller ID in the pattern, and the array value
     * the actual controller ID. For example, `['u' => 'user']`.
     *
     * You may also pass multiple controller IDs as an array. If this is the case, this composite rule will
     * generate applicable URL rules for EVERY specified controller. For example, `['user', 'post']`.
     */
    public $controller;

    public function init()
    {
        parent::init();
    }

    protected function createRules()
    {

    }
}