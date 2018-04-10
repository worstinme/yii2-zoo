<?php

namespace worstinme\zoo\elements\image;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElement
{

    public function rules()
    {
        return [
            [$this->attribute,'string','max'=>255],
        ];
    }


}