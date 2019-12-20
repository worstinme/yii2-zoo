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

    public function afterSave($insert)
    {

        if ($this->isAttributeActive) {

            $images = $this->getValue();

            $files = ArrayHelper::getColumn($images, 'source');

            if (count($images)) {

                foreach ($images as $key => &$image) {

                    if ($image['tmp'] == 1) {

                        $source = Yii::getAlias($image['source']);

                        if (is_file($source)) {

                            $pathInfo = pathinfo($source, PATHINFO_FILENAME);
                            $newName = pathinfo($source, PATHINFO_FILENAME);
                            $newExtension = strtolower(pathinfo($source, PATHINFO_EXTENSION));

                            $dir = $this->element->spread ? $this->element->uploadDir . DIRECTORY_SEPARATOR . $this->owner->id : $this->element->uploadDir;

                            if (!is_dir($dir)) {
                                mkdir($dir, 0757, true);
                            }

                            $newFile = $dir . DIRECTORY_SEPARATOR . $newName . '.' . $newExtension;

                            if (rename(Yii::getAlias($image['source']), Yii::getAlias($newFile))) {
                                $image['source'] = str_replace(Yii::getAlias($this->element->webroot), "", $newFile);
                                $image['tmp'] = 0;
                            } else {
                                unset($images[$key]);
                            }

                        }
                    }
                }

            }

            $oldImages = $this->old_value;

            if (is_array($oldImages)) {

                foreach ($oldImages as $oldImage) {

                    $oldImage = Json::decode($oldImage);

                    $file = Yii::getAlias($oldImage['tmp'] == 1 ? $oldImage['source'] : $this->element->webroot . DIRECTORY_SEPARATOR. ltrim($oldImage['source'],DIRECTORY_SEPARATOR));

                    if (!in_array($oldImage['source'], $files) && is_file($file)) {
                        unlink($file);
                    }

                }

            }

            $this->setValue($images);
            $this->resetTempImages($this->attribute);

            if ($insert) {
                Yii::$app->session->remove('images-0-' . $this->attribute);
            }

        }

        parent::afterSave($insert);

    }

    public function afterDelete()
    {
        parent::afterDelete();
    }

}
