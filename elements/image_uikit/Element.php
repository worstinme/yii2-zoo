<?php

namespace worstinme\zoo\elements\image_uikit;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElement
{

    public $iconClass = 'uk-icon-image';
    public $_multiple = true;
    public $temp = '@app/runtime/uploads';

    public function getRules()
    {
        return [
            [['dir', 'webroot'], 'string', 'max' => 255],
            ['spread', 'integer'],
            ['rename', 'integer'],
            ['horizontalResizeWidth', 'integer'],
            ['verticalResizeWidth', 'integer'],
            ['maxFileSize', 'integer'],
        ];
    }

    public function getLabels()
    {
        return [
            'dir' => Yii::t('zoo', 'DIR'),
            'webroot' => Yii::t('zoo', 'WEBROOT'),
            'spread' => Yii::t('zoo', 'SPREAD'),
            'rename' => Yii::t('zoo', 'RENAME'),
            'maxFileSize' => Yii::t('zoo', 'MAX_FILE_SIZE'),
            'horizontalResizeWidth' => Yii::t('zoo', 'HORIZONTAL_RESIZE_WIDTH'),
            'verticalResizeWidth' => Yii::t('zoo', 'VERTICAL_RESIZE_WIDTH'),
        ];
    }

    public function getConfigView()
    {
        return '@worstinme/zoo/elements/image_uikit/_settings';
    }

    public function getUploadDir()
    {
        return Yii::getAlias(rtrim($this->app->basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR. trim($this->dir, "\\/"));
    }

    public function getDir() {
        return isset($this->paramsArray['dir']) ? $this->paramsArray['dir'] : 'images';
    }

    public function setDir($a)
    {
        $params = $this->paramsArray;
        $params['dir'] = $a;
        return $this->paramsArray = $params;
    }

    public function getMaxFileSize()
    {
        return isset($this->paramsArray['maxFileSize']) ? $this->paramsArray['maxFileSize'] : 4;
    }

    public function setMaxFileSize($a)
    {
        $params = $this->paramsArray;
        $params['maxFileSize'] = $a;
        return $this->paramsArray = $params;
    }

    public function getRename()
    {
        return isset($this->paramsArray['rename']) ? $this->paramsArray['rename'] : 0;
    }

    public function setRename($a)
    {
        $params = $this->paramsArray;
        $params['rename'] = $a;
        return $this->paramsArray = $params;
    }

    public function getHorizontalResizeWidth()
    {
        return isset($this->paramsArray['horizontalResizeWidth']) ? $this->paramsArray['horizontalResizeWidth'] : 0;
    }

    public function setHorizontalResizeWidth($a)
    {
        $params = $this->paramsArray;
        $params['horizontalResizeWidth'] = $a;
        return $this->paramsArray = $params;
    }

    public function getVerticalResizeWidth()
    {
        return isset($this->paramsArray['verticalResizeWidth']) ? $this->paramsArray['verticalResizeWidth'] : 0;
    }

    public function setVerticalResizeWidth($a)
    {
        $params = $this->paramsArray;
        $params['verticalResizeWidth'] = $a;
        return $this->paramsArray = $params;
    }

    public function getSpread()
    {
        return isset($this->paramsArray['spread']) ? $this->paramsArray['spread'] : 1;
    }

    public function setSpread($a)
    {
        $params = $this->paramsArray;
        $params['spread'] = $a;
        return $this->paramsArray = $params;
    }

    public function getWebroot()
    {
        return isset($this->paramsArray['webroot']) ? $this->paramsArray['webroot'] : $this->app->basePath;
    }

    public function setWebroot($a)
    {
        $params = $this->paramsArray;
        $params['webroot'] = trim($a, "\\/");
        return $this->paramsArray = $params;
    }


}
