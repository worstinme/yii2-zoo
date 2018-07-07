<?php

namespace worstinme\zoo\elements\image_uikit;

use worstinme\zoo\backend\models\Items;
use worstinme\zoo\helpers\ImageHelper;
use Yii;
use worstinme\zoo\elements\BaseCallbackAction;
use yii\helpers\Json;
use yii\imagine\Image;
use yii\web\UploadedFile;

class CallbackAction extends BaseCallbackAction
{
    public function run($app, $model_id = null, $element, $act)
    {
        if (($model = $this->findModel($model_id)) === null) {
            $model = new Items(['app_id' => $app]);
            $model->regBehaviors();
        }

        if ($act == 'remove') {

            if (($image = Yii::$app->request->post('image')) !== null) {

                if (is_file(Yii::getAlias($image))) {

                    $sessionName = 'images-' . ($model->id ?? '0') . '-' . $this->element->attributeName;
                    $images = Yii::$app->session->get($sessionName, []);

                    foreach ($images as $key => $img) {
                        if ($img['source'] == $image) {
                            unset($images[$key]);
                            unlink(Yii::getAlias($image));
                            Yii::$app->session->set($sessionName, $images);
                            return [
                                'code' => 200,
                                'message' => 'Удалено!',
                            ];
                        }
                    }
                }

                return [
                    'code' => 0,
                    'message' => "Файл $image не найден.",
                ];

            }

            return [
                'code' => 0,
                'message' => 'Не указано изображение!',
            ];

        }

        $upload = \yii\base\DynamicModel::validateData(['file' => UploadedFile::getInstanceByName('file')], [
            [['file'], 'file', 'extensions' => 'jpg, png, jpeg', 'maxSize' => $this->element->maxFileSize * 1024 * 1024, 'mimeTypes' => 'image/jpeg, image/png', 'message' => Yii::t('zoo', 'Изображение должно быть до ' . $this->element->maxFileSize . 'мб jpg, jpeg, png не больше 10 шт')],
        ]);

        if (!$upload->hasErrors()) {

            $tmpDir = $this->element->temp . DIRECTORY_SEPARATOR;

            if (!is_dir(Yii::getAlias($tmpDir))) {
                mkdir(Yii::getAlias($tmpDir), 0777, true);
            }

            if ($this->element->rename) {
                $filename = md5(file_get_contents($upload->file->tempName));
            } else {
                $filename = $upload->file->baseName;
            }

            $filename = $this->checkFileName(($model->id?$model->id.'-':'').$filename, $upload->file->extension, Yii::getAlias($tmpDir));

            $upload->file->saveAs(Yii::getAlias($tmpDir) . $filename);

            $image = \yii\imagine\Image::getImagine()->open(Yii::getAlias($tmpDir) . $filename);

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

                $image->thumbnail(new \Imagine\Image\Box($newWidth, $newHeight))->save(Yii::getAlias($tmpDir) . $filename);

            }

            $sessionName = 'images-' . ($model->id ?? '0') . '-' . $this->element->attributeName;

            $images = Yii::$app->session->get($sessionName, []);

            $images[] = [
                'tmp' => true,
                'source' => $tmpDir . $filename,
                'width' => $newWidth ?? $width,
                'height' => $newHeight ?? $height,
                'caption' => '',
                'alt' => '',
            ];

            Yii::$app->session->set($sessionName, $images);

            return [
                'image' => $this->controller->renderPartial('@worstinme/zoo/elements/image_uikit/_input', [
                    'model' => $model,
                    'element' => $this->element,
                    'image' => [
                        'source' => $tmpDir . $filename,
                        'tmp' => 1,
                        'caption' => '',
                        'width' => $newWidth ?? $width,
                        'height' => $newHeight ?? $height,
                        'alt' => '',
                    ],
                ]),
                'code' => 200,
            ];

        } else {
            return [
                'code' => 0,
                'message' => implode(" ", array_map(function ($a) {
                    return implode(" ", $a);
                }, $upload->errors)),
            ];
        }

    }

    protected function checkFileName($name, $extension, $dir, $m = '')
    {
        if (!is_file($dir . $name . $m . '.' . $extension)) {
            return $name . $m . '.' . $extension;
        }

        return $this->checkFileName($name, $extension, $dir, (int)$m + 1);

    }

    protected function remove($model)
    {

    }

}