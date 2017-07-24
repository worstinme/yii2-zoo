<?php

namespace worstinme\zoo\elements\image_uikit;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

    public function rules($attributes)
    {
        return [
            [$attributes, 'safe'],
        ];
    }

    public $multiple = true;
    public $value_field = 'value_text';

    public function getTempImages($attribute)
    {
        return Yii::$app->session->get('images-' . $attribute, []);
    }

    public function resetTempImages($attribute)
    {
        return Yii::$app->session->remove('images-' . $attribute);
    }

    public function getValue($attribute)
    {
        if (!isset($this->owner->values[$attribute])) {
            $this->loadAttributesFromElements($attribute);
        }

        $v = \yii\helpers\ArrayHelper::getColumn($this->owner->values[$attribute], function ($a) {
            return Json::decode($a[$this->value_field]);
        });

        return $v;
    }

    public function setValue($attribute, $value)
    {
        if (!isset($this->owner->values[$attribute])) {
            $this->loadAttributesFromElements($attribute);
            $this->owner->setOldValue($attribute, $this->owner->values[$attribute]);
        }

        $va = [];;

        if (is_array($value)) {

            foreach ($value as $key => $v) {
                $a = [
                    'value_text' => null,
                    'value_int' => null,
                    'value_string' => null,
                    'value_float' => null,
                ];


                if (!empty($v)) {
                    $a[$this->value_field] = Json::encode($v);
                    $va[] = $a;
                }

            }

        }

        $this->owner->values[$attribute] = $va;


        return true;
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    public function afterFind()
    {
        $attributes = $this->owner->getElementsByType('image_uikit');
        foreach ($attributes as $attribute) {
            $this->owner->setOldValue($attribute, $this->owner->{$attribute});
        }

    }

    public function afterSave()
    {
        $attributes = $this->owner->getElementsByType('image_uikit');

        foreach ($attributes as $attribute) {

            $element = $this->owner->elements[$attribute];

            $images = $this->owner->{$attribute};

            $files = ArrayHelper::getColumn($images, 'source');

            if (count($images)) {

                foreach ($images as $key => &$image) {

                    if ($image['tmp']) {

                        if (is_file(Yii::getAlias('@app') . $image['source'])) {
                            $pathInfo = pathinfo('_' . $image['source'], PATHINFO_FILENAME);
                            $newName = mb_substr($pathInfo, 1, mb_strlen($pathInfo, '8bit'), '8bit');
                            $newExtension = strtolower(pathinfo($image['source'], PATHINFO_EXTENSION));

                            $dir = $element->spread ? '/' . $element->dir . $this->owner->id . '/' : '/' . $element->dir;

                            if (!is_dir(Yii::getAlias('@webroot') . $dir)) {
                                mkdir(Yii::getAlias('@webroot') . $dir, 0777, true);
                            }

                            $newFile = $dir . $newName . '.' . $newExtension;

                            if (rename(Yii::getAlias('@app') . $image['source'], Yii::getAlias('@webroot') . $newFile)) {
                                $image['source'] = $newFile;
                                $image['tmp'] = 0;
                            } else {
                                unset($images[$key]);
                            }

                        } else {
                            unset($images[$key]);
                        }
                    }
                }

            } else {

                Yii::$app->db->createCommand()->delete('{{%zoo_items_elements}}', ['item_id' => $this->owner->id, 'element' =>
                    $attribute])->execute();
            }

            $oldImages = $this->owner->getOldValue($attribute);

            if (is_array($oldImages)) {
                foreach ($oldImages as $oldImage) {

                    $file = Yii::getAlias($oldImage['tmp'] ? '@app' : '@webroot') . $oldImage['source'];

                    if (!in_array($oldImage['source'], $files) && is_file($file)) {
                        unlink($file);
                    }

                }
            }

            $this->owner->{$attribute} = $images;
            $this->resetTempImages($attribute);

        }
    }

    public function afterDelete()
    {
    }

}