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
        return isset($this->owner->params['dir'])?$this->owner->params['dir']:'@webroot/images/';
    }

    public function setDir($a)
    {
        $params = $this->owner->params;
        $params['dir'] = $a;
        return $this->owner->params = $params;
    }

    public function getMaxFileSize()
    {
        return isset($this->owner->params['maxFileSize'])?$this->owner->params['maxFileSize']:4;
    }

    public function setMaxFileSize($a)
    {
        $params = $this->owner->params;
        $params['maxFileSize'] = $a;
        return $this->owner->params = $params;
    }

    public function getRename()
    {
        return isset($this->owner->params['rename'])?$this->owner->params['rename']:0;
    }

    public function setRename($a)
    {
        $params = $this->owner->params;
        $params['rename'] = $a;
        return $this->owner->params = $params;
    }

    public function getHorizontalResizeWidth()
    {
        return isset($this->owner->params['horizontalResizeWidth'])?$this->owner->params['horizontalResizeWidth']:0;
    }

    public function setHorizontalResizeWidth($a)
    {
        $params = $this->owner->params;
        $params['horizontalResizeWidth'] = $a;
        return $this->owner->params = $params;
    }

    public function getVerticalResizeWidth()
    {
        return isset($this->owner->params['verticalResizeWidth'])?$this->owner->params['verticalResizeWidth']:0;
    }

    public function setVerticalResizeWidth($a)
    {
        $params = $this->owner->params;
        $params['verticalResizeWidth'] = $a;
        return $this->owner->params = $params;
    }

    public function getSpread()
    {
        return isset($this->owner->params['spread'])?$this->owner->params['spread']:1;
    }

    public function setSpread($a)
    {
        $params = $this->owner->params;
        $params['spread'] = $a;
        return $this->owner->params = $params;
    }

    public function getTemp()
    {
        return isset($this->owner->params['temp'])?$this->owner->params['temp']:'@app/runtime/uploads';
    }

    public function setTemp($a)
    {
        $params = $this->owner->params;
        $params['temp'] = $a;
        return $this->owner->params = $params;
    }


}