<?php

namespace common\caching;

/**
 * Class CallbackDependency
 *
 * @package common\caching
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class CallbackDependency extends \yii\caching\Dependency
{
    /**
     * @var string|array
     */
    public $callback;

    /**
     * @inheritdoc
     */
    protected function generateDependencyData($cache)
    {
        $callback = $this->callback;

        if (!is_array($callback)) {
            $callback = [$callback];
        }

        return array_map(function ($callback) use ($cache) {
            return call_user_func($callback, $cache);
        }, $callback);
    }
}
