<?php

namespace dee\angular;

use yii\web\View;

/**
 * JsBlock
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class JsBlock extends \yii\base\Widget
{
    public $key;
    public $pos = View::POS_READY;
    public $viewFile;
    public $viewParams = [];

    /**
     * Starts recording a block.
     */
    public function init()
    {
        if ($this->viewFile === null) {
            ob_start();
            ob_implicit_flush(false);
        }
    }

    /**
     * Ends recording a block.
     * This method stops output buffering and saves the rendering result as a named block in the view.
     */
    public function run()
    {
        if ($this->viewFile === null) {
            $block = ob_get_clean();
        } else {
            $block = $this->view->render($this->viewFile, $this->viewParams);
        }
        $block = trim($block);

        /**
         * Thanks to yiqing
         */
        $jsBlockPattern = '|^<script[^>]*>(?P<block_content>.+?)</script>$|is';
        if (preg_match($jsBlockPattern, $block, $matches)) {
            $block = $matches['block_content'];
        }

        $this->view->registerJs($block, $this->pos, $this->key);
    }
}