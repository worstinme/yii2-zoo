<?php

namespace worstinme\zoo\elements\image_uikit;

use worstinme\zoo\elements\BaseConfigBehavior;
use Yii;

class Config extends BaseConfigBehavior
{

    public $iconClass = 'uk-icon-image';
    public $_multiple = true;


    public function getRules()
    {
        return [
            [['dir','temp'], 'string','max'=>255],
            ['spread','integer'],
            ['rename','integer'],
            ['horizontalResizeWidth','integer'],
            ['verticalResizeWidth','integer'],
            ['maxFileSize','integer'],
        ];
    }

    public function getLabels()
    {
        return [
            'dir' => Yii::t('zoo/image_uikit', 'DIR'),
            'spread' => Yii::t('zoo/image_uikit', 'SPREAD'),
            'rename' => Yii::t('zoo/image_uikit', 'RENAME'),
            'temp'=>Yii::t('zoo/image_uikit', 'TEMP_DIR'),
            'maxFileSize'=>Yii::t('zoo/image_uikit', 'MAX_FILE_SIZE'),
            'horizontalResizeWidth'=>Yii::t('zoo/image_uikit', 'HORIZONTAL_RESIZE_WIDTH'),
            'verticalResizeWidth'=>Yii::t('zoo/image_uikit', 'VERTICAL_RESIZE_WIDTH'),
        ];
    }

    public function getConfigView() {
        return '@worstinme/zoo/elements/image_uikit/_settings';
    }

    public function getDir()
    {
        return isset($this->owner->paramsArray['dir'])?$this->owner->paramsArray['dir']:'@webroot/images/';
    }

    public function setDir($a)
    {
        $params = $this->owner->paramsArray;
        $params['dir'] = $a;
        return $this->owner->paramsArray = $params;
    }

    public function getMaxFileSize()
    {
        return isset($this->owner->paramsArray['maxFileSize'])?$this->owner->paramsArray['maxFileSize']:4;
    }

    public function setMaxFileSize($a)
    {
        $params = $this->owner->paramsArray;
        $params['maxFileSize'] = $a;
        return $this->owner->paramsArray = $params;
    }

    public function getRename()
    {
        return isset($this->owner->paramsArray['rename'])?$this->owner->paramsArray['rename']:0;
    }

    public function setRename($a)
    {
        $params = $this->owner->paramsArray;
        $params['rename'] = $a;
        return $this->owner->paramsArray = $params;
    }

    public function getHorizontalResizeWidth()
    {
        return isset($this->owner->paramsArray['horizontalResizeWidth'])?$this->owner->paramsArray['horizontalResizeWidth']:0;
    }

    public function setHorizontalResizeWidth($a)
    {
        $params = $this->owner->paramsArray;
        $params['horizontalResizeWidth'] = $a;
        return $this->owner->paramsArray = $params;
    }

    public function getVerticalResizeWidth()
    {
        return isset($this->owner->paramsArray['verticalResizeWidth'])?$this->owner->paramsArray['verticalResizeWidth']:0;
    }

    public function setVerticalResizeWidth($a)
    {
        $params = $this->owner->paramsArray;
        $params['verticalResizeWidth'] = $a;
        return $this->owner->paramsArray = $params;
    }

    public function getSpread()
    {
        return isset($this->owner->paramsArray['spread'])?$this->owner->paramsArray['spread']:1;
    }

    public function setSpread($a)
    {
        $params = $this->owner->paramsArray;
        $params['spread'] = $a;
        return $this->owner->paramsArray = $params;
    }

    public function getTemp()
    {
        return isset($this->owner->paramsArray['temp'])?$this->owner->paramsArray['temp']:'@app/runtime/uploads';
    }

    public function setTemp($a)
    {
        $params = $this->owner->paramsArray;
        $params['temp'] = $a;
        return $this->owner->paramsArray = $params;
    }


}