<?php

namespace itshkacomua\blog;

/**
 * blog module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'itshkacomua\blog\controllers';
    public $defaultRoute = 'blog';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
