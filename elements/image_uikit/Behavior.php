<?php

namespace worstinme\zoo\elements\image_uikit;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{
    // TODO: доделать настройку количества загружаемых картинок.
    // TODO: добавить настройку BASE_URL для advanced yii

    public $json_field = true;

    public function getMultiple() {
        return true;
    }

    public $field = 'value_text';

    public function rules()
    {
        return [
            [$this->attribute, 'safe'],
        ];
    }

    public function getTempImages($attribute)
    {
        return Yii::$app->session->get('images-' . ($this->owner->id ?? '0') . '-' . $attribute, []);
    }

    public function resetTempImages($attribute)
    {
        return Yii::$app->session->remove('images-' . ($this->owner->id ?? '0') . '-' . $attribute);
    }

    public function afterSave()
    {
        $images = $this->getValue();

        $files = ArrayHelper::getColumn($images, 'source');

        if (count($images)) {

            foreach ($images as $key => &$image) {

                if ($image['tmp'] == 1) {

                    if (is_file(Yii::getAlias($image['source']))) {

                        $pathInfo = pathinfo(Yii::getAlias($image['source']), PATHINFO_FILENAME);
                        $newName = pathinfo(Yii::getAlias($image['source']), PATHINFO_FILENAME);
                        $newExtension = strtolower(pathinfo(Yii::getAlias($image['source']), PATHINFO_EXTENSION));

                        $dir = $this->element->spread ? ($this->element->dir . DIRECTORY_SEPARATOR . $this->owner->id) : $this->element->dir;

                        if (!is_dir(Yii::getAlias($dir))) {
                            mkdir(Yii::getAlias($dir), 0777, true);
                        }

                        $newFile = $dir . DIRECTORY_SEPARATOR . $newName . '.' . $newExtension;

                        if (rename(Yii::getAlias($image['source']), Yii::getAlias($newFile))) {
                            $image['source'] = str_replace(Yii::getAlias($this->element->webroot), "", Yii::getAlias($newFile));
                            $image['tmp'] = 0;
                        } else {
                            unset($images[$key]);
                        }

                    } else {
                        unset($images[$key]);
                    }
                }
            }

        }

        $oldImages = $this->old_value;

        if (is_array($oldImages)) {

            foreach ($oldImages as $oldImage) {

                $oldImage = Json::decode($oldImage);

                $file = Yii::getAlias($oldImage['tmp'] == 1 ? $oldImage['source'] : $element->webroot . $oldImage['source']);

                if (!in_array($oldImage['source'], $files) && is_file($file)) {
                    unlink($file);
                }

            }

        }

        $this->setValue($images);
        $this->resetTempImages($this->attribute);

        parent::afterSave();

    }

    public function afterDelete()
    {
        parent::afterDelete();
    }

}