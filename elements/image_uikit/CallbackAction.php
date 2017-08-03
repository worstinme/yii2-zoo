<?php

namespace worstinme\zoo\elements\image_uikit;


use worstinme\zoo\helpers\ImageHelper;
use Yii;
use worstinme\zoo\elements\BaseCallbackAction;
use yii\helpers\Json;
use yii\imagine\Image;
use yii\web\UploadedFile;

class CallbackAction extends BaseCallbackAction
{
    public function run($model_id, $element)
    {
        $model = $this->findModel($model_id);

        $upload = \yii\base\DynamicModel::validateData(['file' => UploadedFile::getInstanceByName('file')], [
            [['file'], 'file', 'extensions' => 'jpg, png, jpeg', 'maxSize' => $this->element->maxFileSize * 1024 * 1024, 'mimeTypes' => 'image/jpeg, image/png', 'message' => Yii::t('zoo/image_uikit', 'Изображение должно быть до ' . $this->element->maxFileSize . 'мб jpg, jpeg, png не больше 10 шт')],
        ]);

        if (!$upload->hasErrors()) {

            $tmpDir = '/' . $this->element->temp . DIRECTORY_SEPARATOR . ($model === null ? '' : $model->id);

            if (!is_dir(Yii::getAlias('@app') . $tmpDir)) {
                mkdir(Yii::getAlias('@app') . $tmpDir, 0777, true);
            }

            if ($this->element->rename) {
                $filename = md5(file_get_contents($upload->file->tempName)) . '.' . $upload->file->extension;
            } else {
                $filename = $upload->file->baseName . '.' . $upload->file->extension;
            }

            $filename = $this->checkFileName($filename, Yii::getAlias('@app') . $tmpDir);

            $upload->file->saveAs(Yii::getAlias('@app') . $tmpDir . $filename);

            $image = \yii\imagine\Image::getImagine()->open(Yii::getAlias('@app') . $tmpDir . $filename);

            $width = $image->getSize()->getWidth();
            $height = $image->getSize()->getHeight();

            if ($this->element->horizontalResizeWidth && $this->element->verticalResizeWidth) {

                if ($width > $height && $width > $this->element->horizontalResizeWidth) {
                    $newWidth = (int)$this->element->horizontalResizeWidth;
                } elseif ($width < $height && $width > $this->element->verticalResizeWidth) {
                    $newWidth = (int)$this->element->verticalResizeWidth;
                } else {
                    $newWidth = $width;
                }

                $newHeight = round($newWidth / $width * $height);

                $image->thumbnail(new \Imagine\Image\Box($newWidth, $newHeight))->save(Yii::getAlias('@app') . $tmpDir . $filename);

            }

            $sessionName = 'images-' . $this->element->name;

            $images = Yii::$app->session->get($sessionName, []);

            $images[] = [
                'tmp' => true,
                'source' => $tmpDir . $filename,
                'width' => $newWidth ?? $width,
                'height' => $newHeight ?? $height,
            ];

            Yii::$app->session->set($sessionName, $images);

            return [
                'image' => $this->controller->renderPartial('@worstinme/zoo/elements/image_uikit/_input', [
                    'model' => $model,
                    'attribute' => $this->element->name,
                    'image' => [
                        'source' => $tmpDir . $filename,
                        'tmp' => 1,
                        'caption' => '',
                        'alt' => '',
                    ]
                ]),
                'code' => 200,
            ];

        }
        else {
            return [
                'code'=>0,
                'message'=> implode(" ",array_map(function($a) { return implode(" ", $a); }, $upload->errors)),
            ];
        }

    }

    protected function checkFileName($file, $dir, $m = '')
    {
        if (!is_file($dir . $m . $file)) {
            return $m . $file;
        }

        return $this->checkFileName($file, $dir, (int)$m + 1);

    }

    protected function remove($model)
    {

    }

}