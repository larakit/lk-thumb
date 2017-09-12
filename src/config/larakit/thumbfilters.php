<?php
use Larakit\Helpers\HelperImage;
use Intervention\Image\Image;
use \Larakit\Thumb\Thumb;

return [
    Thumb::FILTER_RESIZE_BY_WIDTH              => function (Image $img, $w) {
        return HelperImage::resizeByWidth($img, $w);
    },
    Thumb::FILTER_RESIZE_IGNORING_ASPECT_RATIO => function (Image $img, $w, $h) {
        return HelperImage::resizeIgnoringAspectRatio($img, $w, $h);
    },
    Thumb::FILTER_RESIZE_IMG_IN_BOX            => function (Image $img, $w, $h) {
        return HelperImage::resizeImgInBox($img, $w, $h);
    },
    Thumb::FILTER_RESIZE_BOX_IN_IMG            => function (Image $img, $w, $h) {
        return HelperImage::resizeBoxInImg($img, $w, $h);
    },
    Thumb::FILTER_CROP_IMG_IN_BOX              => function (Image $img, $w, $h) {
        return HelperImage::cropImgInBox($img, $w, $h);
    },
    Thumb::FILTER_CROP_BOX_IN_IMG              => function (Image $img, $w, $h) {
        return HelperImage::cropBoxInImg($img, $w, $h);
    },
    Thumb::FILTER_GREYSCALE                    => function (Image $img) {
        return $img->greyscale();
    },
];