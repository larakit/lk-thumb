<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 27.07.16
 * Time: 8:32
 */

namespace Larakit\Thumb;

class ThumbSize {
    
    protected $default;
    protected $filters            = [];
    protected $h                  = null;
    protected $name;
    protected $w                  = null;
    protected $is_round           = false;
    protected $watermark_h        = 50;
    protected $watermark_opacity  = 50;
    protected $watermark_position = 'top-left';
    protected $watermark_src      = null;
    protected $watermark_w        = 50;
    
    static function factory($name) {
        return new static($name);
    }
    
    function __construct($name) {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getWatermarkPosition() {
        return $this->watermark_position;
    }
    
    /**
     * @param string $watermark_position
     *
     * @return ThumbSize;
     */
    public function setWatermarkPositionTopLeft() {
        $this->watermark_position = 'top-left';
        
        return $this;
    }
    
    public function setWatermarkPositionTop() {
        $this->watermark_position = 'top';
        
        return $this;
    }
    
    public function setIsRound($val = true) {
        $this->is_round = (bool) $val;
        
        return $this;
    }
    
    public function setWatermarkPositionTopRight() {
        $this->watermark_position = 'top-right';
        
        return $this;
    }
    
    public function setWatermarkPositionLeft() {
        $this->watermark_position = 'left';
        
        return $this;
    }
    
    public function setWatermarkPositionCenter() {
        $this->watermark_position = 'center';
        
        return $this;
    }
    
    public function setWatermarkPositionRight() {
        $this->watermark_position = 'right';
        
        return $this;
    }
    
    public function setWatermarkPositionBottomLeft() {
        $this->watermark_position = 'bottom-left';
        
        return $this;
    }
    
    public function setWatermarkPositionBottom() {
        $this->watermark_position = 'bottom';
        
        return $this;
    }
    
    public function setWatermarkPositionBottomRight() {
        $this->watermark_position = 'bottom-right';
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getFilters() {
        return $this->filters;
    }
    
    /**
     * @return mixed
     */
    public function getDefault() {
        return $this->default;
    }
    
    /**
     * @param mixed $default
     *
     * @return ThumbSize;
     */
    public function setDefault($default) {
        $this->default = $default;
        
        return $this;
    }
    
    /**
     * @return null
     */
    public function getW() {
        return $this->w;
    }
    
    /**
     * @param null $w
     *
     * @return ThumbSize;
     */
    public function setW($w) {
        $this->w = (int) $w;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * @return null
     */
    public function getH() {
        return $this->h;
    }
    
    /**
     * @param null $h
     *
     * @return ThumbSize;
     */
    public function setH($h) {
        $this->h = (int) $h;
        
        return $this;
    }
    
    /**
     * @return null
     */
    public function getWatermarkSrc() {
        return $this->watermark_src;
    }
    
    /**
     * @param null $watermark_src
     *
     * @return ThumbSize;
     */
    public function setWatermarkSrc($watermark_src) {
        $this->watermark_src = $watermark_src;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getWatermarkOpacity() {
        return max(1, min(100, abs((int) $this->watermark_opacity)));
    }
    
    /**
     * @param int $watermark_opacity
     *
     * @return ThumbSize;
     */
    public function setWatermarkOpacity($watermark_opacity) {
        $this->watermark_opacity = $watermark_opacity;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getWatermarkW() {
        
        return $this->watermark_w;
    }
    
    /**
     * @param int $watermark_w
     *
     * @return ThumbSize;
     */
    public function setWatermarkW($watermark_w) {
        $this->watermark_w = $watermark_w;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getWatermarkH() {
        return $this->watermark_h;
    }
    
    /**
     * @param int $watermark_h
     *
     * @return ThumbSize;
     */
    public function setWatermarkH($watermark_h) {
        $this->watermark_h = $watermark_h;
        
        return $this;
    }
    
    /**
     * @param $filter
     *
     * @return $this
     */
    function filterAdd($filter) {
        $this->filters[$filter] = $filter;
        
        return $this;
    }
    
    /**
     * @param $filter
     *
     * @return $this
     */
    function filterRemove($filter) {
        unset($this->filters[$filter]);
        
        return $this;
    }
    
    function toArray() {
        return [
            'name'     => $this->name,
            'w'        => $this->w,
            'h'        => $this->h,
            'is_round' => $this->is_round,
            'filters'  => array_values($this->filters),
        ];
    }
}