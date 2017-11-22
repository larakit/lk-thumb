<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 14.06.17
 * Time: 14:10
 */

namespace Larakit\Thumb;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Larakit\NgAdminlte\LkNgThumb;

trait TraitModelThumb {
    
    //    function thumbsConfig() {
    //        return [
    //            'base' => ColorThumb::class,
    //        ];
    //    }
    
    static function getThumbKey() {
        $r = new \ReflectionClass(static::class);
        
        return Str::snake($r->getShortName(), '-');
    }
    
    protected $is_thumb_hashed = false;
    
    function thumbHashed() {
        $this->is_thumb_hashed = true;
    }
    
    function getThumbsAttribute() {
        $ret    = [];
        $model  = LkNgThumb::getKey(static::class);
        $id     = $this->id;
        $config = (array) $this->thumbsConfig();
        if('galleries' == $this->table) {
            $tmp    = $config;
            $config = [];
            foreach($tmp as $k => $v) {
                $val        = Arr::get($v, 'thumb');
                $config[$k] = $val ? $val : $v;
            }
            
        }
        foreach((array) $config as $type => $class) {
            $t = new $class($this->id);
            /** @var Thumb $t */
            $ret[$type]['name']       = $t->getName();
            $ret[$type]['sizes']      = $t->toArray($this->is_thumb_hashed);
            $ret[$type]['original']   = $t->getUrl();
            $ret[$type]['url_thumb']  = route('thumb-data', compact('model', 'id'));
            $ret[$type]['url_upload'] = route('thumb-upload', compact('model', 'id', 'type'));
            $ret[$type]['url_clear']  = route('thumb-clear', compact('model', 'id', 'type'));
            foreach($t->toArray() as $size => $data) {
                $ret[$type]['sizes'][$size]['url_crop'] = route('thumb-crop', compact('model', 'id', 'type', 'size'));
            }
        }
        
        //        dd($ret);
        
        return $ret;
    }
    
    function thumbClear($type) {
        $class = Arr::get($this->thumbsConfig(), $type);
        if(class_exists($class)) {
            $thumb = new $class($this->id);
            /** @var \Larakit\Thumb\Thumb $thumb */
            $thumb->delete();
            
            return true;
        }
        
        return false;
    }
    
    function thumbUpload($type) {
        $class = Arr::get($this->thumbsConfig(), $type);
        if(is_array($class)) {
            $class = Arr::get($class, 'thumb');
        }
        if(class_exists($class)) {
            $thumb = new $class($this->id);
            
            return $thumb->processing(\Request::file('file'));
        }
        
        return false;
        
    }
}