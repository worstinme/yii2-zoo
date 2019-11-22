<?php
/**
 * @link https://github.com/worstinme/yii2-zoo
 * @copyright Copyright (c) 2017 Eugene Zakirov
 * @license https://github.com/worstinme/yii2-zoo/LICENSE
 */

namespace worstinme\zoo\widgets;

use worstinme\zoo\elements\BaseElement;
use worstinme\zoo\elements\system\Element;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class ActiveField extends \yii\widgets\ActiveField
{
    public $options = ['class' => 'uk-margin'];

    public $labelOptions = ['class' => 'uk-form-label'];

    public $wrapperOptions = ['class' => 'uk-form-controls'];

    public $hintOptions = ['class' => 'uk-form-controls-text hint'];

    public $template = "{label}\n{beginWrapper}\n{hint}\n{input}\n{error}{endWrapper}\n{hidden}";

    public $inputOptions = [];

    public function render($content = null)
    {
        if ($content === null) {
            if (!isset($this->parts['{beginWrapper}'])) {
                $options = $this->wrapperOptions;
                $tag = ArrayHelper::remove($options, 'tag', 'div');
                $this->parts['{beginWrapper}'] = Html::beginTag($tag, $options);
                $this->parts['{endWrapper}'] = Html::endTag($tag);
            }
            if (!isset($this->parts['{hidden}'])) {
                $this->parts['{hidden}'] = '';
            }
        }

        return parent::render($content);
    }

    public function element($options = [])
    {
        $element = $this->model->app->getElement($this->attribute);

        if (!$element->isAvailable) {
            return null;
        }

        $options = ArrayHelper::merge($this->inputOptions, $options);

        if (is_a($element, BaseElement::className()) && !$element->all_categories) {

            $show = $element->isActiveForModel($this->model);

            $opts = [
                'data' => [
                    'categories' => $element->categories,
                ],
                'hidden' => $show ? false : "hidden",
            ];
            $this->options = ArrayHelper::merge($this->options, $opts);

            $this->parts['{hidden}'] = Html::activeHiddenInput($this->model, $element->attributeName,
                ['value' => '', 'class' => 'caegories-active', 'disabled' => $show]);

        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        $element_class = $this->attribute;
        $this->options['class'] .= ' '.$element_class;

        if (is_a($element, Element::className())) {
            $viewFile = '@worstinme/zoo/elements/system/'.$element->type.'/form.php';
        } else {
            $viewFile = $element->formView;
        }

        if ($element->hint) {
            $this->labelOptions["title"] = $element->hint;
            $this->labelOptions["uk-tooltip"] = "pos: top-left; offset: 0";
        }

        $this->parts['{input}'] = $this->form->view->render($viewFile, [
            'options' => $options,
            'element' => $element,
            'model' => $this->model,
            'form' => $this->form,
        ]);

        return $this;
    }

}
