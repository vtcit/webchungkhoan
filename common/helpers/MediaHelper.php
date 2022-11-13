<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\helpers;

use Yii;
use yii\helpers\FileHelper;
use yii\imagine\Image;
// use yii\web\UploadedFile;
use common\helpers\VusInflector;

//use Imagine\Gd;
use Imagine\Image\ImageInterface;
use Imagine\Image\Box;
//use Imagine\Image\BoxInterface;

/**
 * MediaHelper provides concrete implementation for [MediaController].
 *
 *
 * @author Gioan Vu <gioanvu07886@gmail.com>
 * @since 2.0
 */
class MediaHelper
{

    /**
     * Upload File
     * $fileUpload  yii\web\UploadedFile::getInstance()
     * return Array
     * 
     */
    public static function upload($fileUpload)
    {
        $kinc = 0;
        $todayPath = date('Y/m');
        do {
            $baseFileName = $todayPath.'/'.VusInflector::slug($fileUpload->baseName).($kinc? "-$kinc" : '');
            $file =  $baseFileName.'.'.$fileUpload->extension; 
            $saveFile = FileHelper::normalizePath(Yii::getAlias('@upload/').$file);
            $kinc++;
        } while (file_exists($saveFile));

        $createdDir = FileHelper::createDirectory(dirname($saveFile));

        $fileUpload->saveAs($saveFile);

        if($fileUpload->hasError)
        {
            return [
                'error' => true,
                'message' => self::message($fileUpload->error),
            ];
        }

        list($width, $height) = getimagesize($saveFile);

        return [
            'title' => $fileUpload->baseName.($kinc>1? " ($kinc)" : ""),
            //'description' => '',
            'file' => $file,
            'width' => round($width),
            'height' => round($height),
            'mime_type' => $fileUpload->type,
            'extension' => $fileUpload->extension,
            'filesize' => $fileUpload->size,
            'error' => false,
            'message' => self::message($fileUpload->error),
        ];
    }
    
    /**
     * createThumbs Create thumbnails for new upload and re-thumbnail
     * $file = 'path/to/file.jpg': file Original
     * 
     * $thumbCfg = [
     *     'thumbnail/medium/large' => [
     *         'width'  => 150/...,
     *         'height' => 150/...,
     *         'crop'   => false/true,
     *     ]
     * ]
     * 
     */
    public static function createThumbs($fileOriginal, $thumbCfg)
    {
        $pathParts = pathinfo($fileOriginal);
        $fileName = $pathParts['filename'];
        $fileExtension = $pathParts['extension'];
        $dirName = dirname($fileOriginal);

        $saveFileOriginal = FileHelper::normalizePath(Yii::getAlias('@upload/').$fileOriginal);

        $imgImagine = Image::getImagine()->open($saveFileOriginal);

        $size = $imgImagine->getSize();
        $ratio = $size->getWidth()/$size->getHeight();
        $thumbs = [];
        foreach($thumbCfg as $k=>$thumb)
        {
            $keepAspectRatio = !$thumb['crop'];
            list($width, $height) = self::dimensions($thumb['width'], $thumb['height'], $ratio, $keepAspectRatio);
            // check if upscale then return original file
            if($width >= $size->getWidth() || $height >= $size->getHeight())
            {
                list($width, $height) = [$size->getWidth(), $size->getHeight()];
                $file = $fileOriginal;
                $saveFile = $saveFileOriginal;
            }
            else
            {
                $file =  $dirName.'/'.$fileName.'_'.round($width).'x'.round($height).'.'.$fileExtension; 
                $saveFile =  FileHelper::normalizePath(Yii::getAlias('@upload/').$file);
                if($keepAspectRatio)
                    $imgImagine->thumbnail(new Box($width, $height))->save($saveFile, ['quality' => 90]); // with default Mode: ImageInterface::THUMBNAIL_INSET
                else
                    $imgImagine->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND)->save($saveFile, ['quality' => 90]);
            }

            // get real sizes of the file
            // list($width, $height) = getimagesize($saveFile);
            $thumbs[$k] = [
                'file' => $file,
                'width' => $width,
                'height' => $height,
                'filesize' => filesize($saveFile),
            ];
        }
        return $thumbs;
    }
    /**
     * 
     */
    public static function dimensions($width, $height, $ratio = 4/3, $keepAspectRatio = true)
    {
        if ($keepAspectRatio === false) {
            if (!$height) {
                $height = ceil($width / $ratio);
            } elseif (!$width) {
                $width = ceil($height * $ratio);
            }
        } else {
            if (!$height) {
                $height = ceil($width / $ratio);
            } elseif (!$width) {
                $width = ceil($height * $ratio);
            } elseif ($width / $height > $ratio) {
                $width = $height * $ratio;
            } else {
                $height = $width / $ratio;
            }
        }
        return [$width, $height];
    }

    // Remove files
    public static function unlink($file)
    {
        $file = FileHelper::normalizePath(Yii::getAlias('@upload/').$file);
        if(file_exists($file))
            return FileHelper::unlink($file);

        return false;
    }

    public static function message($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = Yii::t('app', 'The uploaded file exceeds the upload_max_filesize directive in php.ini');
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = Yii::t('app', 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form');
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = Yii::t('app', 'The uploaded file was only partially uploaded');
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = Yii::t('app', 'No file was uploaded');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = Yii::t('app', 'Missing a temporary folder');
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = Yii::t('app', 'Failed to write file to disk');
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = Yii::t('app', 'File upload stopped by extension');
                break;

            default:
                $message = Yii::t('app', 'Unknown upload error');
                break;
        }
        return $message;
    }
}