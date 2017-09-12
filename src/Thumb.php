<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 27.07.16
 * Time: 8:32
 */

namespace Larakit\Thumb;

use Illuminate\Support\Arr;

abstract class Thumb {
    
    const         FILTER_RESIZE_BY_WIDTH              = 'resize_by_width';
    const         FILTER_RESIZE_IGNORING_ASPECT_RATIO = 'resize_ignoring_aspect_ratio';
    const         FILTER_RESIZE_IMG_IN_BOX            = 'resize_img_in_box';
    const         FILTER_RESIZE_BOX_IN_IMG            = 'resize_box_in_img';
    const         FILTER_CROP_IMG_IN_BOX              = 'crop_img_in_box';
    const         FILTER_CROP_BOX_IN_IMG              = 'crop_box_in_img';
    const         FILTER_ORIGINAL                     = 'original';
    const         FILTER_GREYSCALE                    = 'greyscale';
    const         DEFAULT_SIZE                        = 100;
    
    /**
     * Цвет фона, если изображение меньше контейнера, по-умолчанию "белый" или "прозрачный" (для PNG)
     *
     * @var null
     */
    protected $bg = null;
    
    /**
     * @return null
     */
    public function getBg() {
        return $this->bg;
    }
    
    /**
     * Расширение файла
     *
     * @var string
     */
    protected $ext = 'jpg';
    /**
     * Идентификатор иллюстрации галереи
     *
     * @var int|null
     */
    protected $item_id = null;
    
    /**
     * Идентификатор модели для которой делается иллюстрация либо элемент галереи
     *
     * @var int
     */
    protected $model_id = null;
    
    function __construct($model_id, $item_id = null) {
        $this->model_id = (int) $model_id;
        $this->item_id  = (int) $item_id;
    }
    
    static function factory($model_id, $item_id = null) {
        return new static($model_id, $item_id);
    }
    
    function getKey($size) {
        return md5(get_called_class() . $this->item_id . $this->model_id . $size);
    }
    
    function getExt() {
        return $this->ext;
    }
    
    /**
     * @return array
     */
    abstract function getSizesList();
    
    function getSizes() {
        $ret = array_merge(
            [
                '_' => ThumbSize::factory('Превьюшка для админки')
                                ->setW(static::DEFAULT_SIZE)
                                ->setH(static::DEFAULT_SIZE)
                                ->filterAdd(Thumb::FILTER_CROP_IMG_IN_BOX),
            ], $this->getSizesList()
        );
        
        return ($ret);
    }
    
    /**
     *     function getPrefix() {
     *          return 'logo/sizename.ru';
     *     }
     *
     * @return string
     */
    abstract function getPrefix();
    
    static function getName() {
        return 'Иллюстрация';
    }
    
    function toArray($is_thumb_hashed = false) {
        $sizes = [];
        foreach($this->getSizes() as $name => $size) {
            $sizes[$name]             = Arr::only($size->toArray(), ['name', 'w', 'h', 'is_round']);
            $sizes[$name]['url']      = $this->getUrl($name) . ($is_thumb_hashed ? '?' . microtime(true) : '');
            $sizes[$name]['make_url'] = $this->makeUrl($name) . ($is_thumb_hashed ? '?' . microtime(true) : '');
            //            $ret[$name]['key'] = $this->getKey($name);
            //            $ret[$name]['file'] = $this->getKey($name);
        }
        
        return $sizes;
    }
    
    /**
     * Формирование ссылки на загруженное изображение с проверкой наличия на диске
     *
     * @param null $size
     *
     * @return mixed|null|string
     */
    function getUrl($size = null) {
        $url = $this->makeUrl($size);
        //на случай получения картинок с другого сервера
        $prefix = (string) env('LARAKIT_THUMB_PREFIX');
        if(!$prefix) {
            //если со своего - проверяем их наличие
            $file = public_path($url);
            if(file_exists($file)) {
                return $url;
                //                return $url . '?' . microtime(true);
            }
        }
        else {
            return $url;
            //            return $url . '?' . microtime(true);
        }
        
        return $size ? $this->getSize($size)
                            ->getDefault() : null;
    }
    
    function makePath($size = null) {
        return public_path($this->makeUrl($size));
    }
    
    function getDefault() {
        return $this->getUrl('_');
    }
    
    /**
     * Формирование ссылки на загруженное изображение
     *
     * @param null $size
     *
     * # /!/thumbs/<getPrefix()>/3/2/123/<hashids_model>.<ext>
     * # /!/thumbs/<getPrefix()>/3/2/123/<size>.<ext>
     * # /!/thumbs/<getPrefix()>/3/2/123/g-<id_larakit_image>.<ext>
     * # /!/thumbs/<getPrefix()>/3/2/123/g-<id_larakit_image>-<size>.<ext>
     *
     * @return string
     */
    function makeUrl($size = null) {
        $prefix   = [];
        $prefix[] = '!';
        $prefix[] = 'thumbs';
        $prefix[] = $this->getPrefix();
        $prefix[] = mb_substr($this->model_id, -1);
        $prefix[] = mb_substr($this->model_id, -2, 1);
        $prefix[] = $this->model_id;
        $link     = '/' . implode('/', $prefix) . '/';
        if($this->item_id) {
            $link .= 'glk-';
            if($size) {
                $link .= $this->item_id . '-' . $size;
            }
            else {
                $link .= hashids_encode($this->item_id);
            }
        }
        else {
            if($size) {
                $link .= $size;
            }
            else {
                $link .= hashids_encode($this->model_id);
            }
        }
        $link .= '.' . $this->ext;
        $prefix = (string) env('LARAKIT_THUMB_PREFIX');
        
        return $prefix . $link;
    }
    
    /**
     * @param $size
     *
     * @return ThumbSize
     */
    function getSize($size) {
        return Arr::get($this->getSizes(), $size);
    }
    
    //массовое обновление всех превьюшек
    
    function delete() {
        $sizes   = array_keys($this->getSizes());
        $sizes[] = null;
        $sizes[] = '_';
        foreach($sizes as $size) {
            $f = $this->makePath($size);
            if($f) {
                \File::delete($f);
            }
        }
    }
    
    function processing($source) {
        $original = \Image::make($source);
        //сохраним оригинал в размере не более 1920х1920
        $file = public_path() . $this->makeUrl();
        $file = str_replace('\\', '/', $file);
        $dir  = dirname($file);
        if(!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        if($original->width() > 1920) {
            $original->resize(
                1920, 1920, function ($constraint) {
                $constraint->aspectRatio();
            }
            );
        }
        $original->save($file, 100);
        //сделаем превьюшки для всех размеров
        foreach($this->getSizes() as $name => $size) {
            $this->processingSize($file, $name);
        }
        
        return true;
    }
    
    function processingSize($source, $name) {
        $size = $this->getSize($name);
        //получили куда положить превьюшку после обработки
        $file = public_path() . $this->makeUrl($name);
        $dir  = dirname($file);
        if(!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        if(!count($size->getFilters())) {
            throw new \Exception('Не назначены фильтры');
        }
        $img = \Image::make($source);
        foreach($size->getFilters() as $filter) {
            $callback = \Config::get('larakit.thumbfilters.' . $filter);
            if(!is_callable($callback)) {
                throw  new \Exception('Фильтр для THUMB с названием "' . $filter . '" не найден!');
            }
            $img = call_user_func_array(
                $callback, [
                             $img,
                             $size->getW(),
                             $size->getH(),
                         ]
            );
        }
        if($this->bg) {
            $image = (string) $img->encode('png');
            $img->fill($this->bg)
                ->fill($image)
            ;
        }
        $watermark_src = $size->getWatermarkSrc();
        //добавляем водяной знак
        if($watermark_src) {
            $watermark = \Image::make($watermark_src);
            //уменьшаем его до нужного размера
            $watermark = \Larakit\Helpers\HelperImage::resizeImgInBox(
                $watermark, $size->getWatermarkW(), $size->getWatermarkH()
            );
            //устанавливаем его прозрачность
            $watermark->opacity($size->getWatermarkOpacity());
            //позиционируем
            $img->insert($watermark, $size->getWatermarkPosition());
        }
        $img->save($file, 100);
        //        Event::notify('THUMB',
        //            [
        //                'entity' => $this->entity,
        //                'vendor' => $this->vendor,
        //                'name'   => $this->name,
        //                'size'   => $size,
        //                'w'      => $img->width(),
        //                'h'      => $img->height(),
        //                'file'   => $file,
        //                'id'     => $this->id,
        //            ]);
        
        return $img;
    }
    
}