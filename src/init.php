<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 07.08.16
 * Time: 22:38
 */
use \Larakit\Helpers\HelperImage;
use \Larakit\Thumb\Thumb;
use \Intervention\Image\Image;
\Larakit\Boot::register_config(__DIR__.'/config', true);


\Larakit\Thumb\LkNgThumb::registerFilter(Thumb::FILTER_RESIZE_BY_WIDTH, function (Image $img, $w) {
    return HelperImage::resizeByWidth($img, $w);
});
\Larakit\Thumb\LkNgThumb::registerFilter(Thumb::FILTER_RESIZE_IGNORING_ASPECT_RATIO, function (Image $img, $w, $h) {
    return HelperImage::resizeIgnoringAspectRatio($img, $w, $h);
});
\Larakit\Thumb\LkNgThumb::registerFilter(Thumb::FILTER_RESIZE_IMG_IN_BOX, function (Image $img, $w, $h) {
    return HelperImage::resizeImgInBox($img, $w, $h);
});
\Larakit\Thumb\LkNgThumb::registerFilter(Thumb::FILTER_RESIZE_BOX_IN_IMG, function (Image $img, $w, $h) {
    return HelperImage::resizeBoxInImg($img, $w, $h);
});
\Larakit\Thumb\LkNgThumb::registerFilter(Thumb::FILTER_CROP_IMG_IN_BOX, function (Image $img, $w, $h) {
    return HelperImage::cropImgInBox($img, $w, $h);
});
\Larakit\Thumb\LkNgThumb::registerFilter(Thumb::FILTER_CROP_BOX_IN_IMG, function (Image $img, $w, $h) {
    return HelperImage::cropBoxInImg($img, $w, $h);
});
\Larakit\Thumb\LkNgThumb::registerFilter(Thumb::FILTER_GREYSCALE, function (Image $img) {
    return $img->greyscale();
});