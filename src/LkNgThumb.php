<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 22.05.17
 * Time: 13:27
 */

namespace Larakit\Thumb;

use Illuminate\Support\Arr;

class LkNgThumb {

    protected static $filters = [];

    static function filters() {
        return self::$filters;
    }

    static function filter($name) {
        return Arr::get(self::$filters, $name);
    }

    static function registerFilter($name, $callback) {
        static::$filters[$name] = $callback;
    }


}