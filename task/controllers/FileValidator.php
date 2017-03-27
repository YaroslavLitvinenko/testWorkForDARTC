<?php
/**
 * Created by PhpStorm.
 * User: himmel
 * Date: 26.03.17
 * Time: 19:55
 */

namespace app\controllers;


use yii\validators\Validator;

class FileValidator extends Validator
{
    private $maxSize;

    public function init()
    {
        parent::init();
        $this->maxSize = 100 * 1024;
        $this->message = "The file '*.txt' is too big. Its size cannot exceed $this->maxSize B.";
    }

    public function validateAttribute($model, $attribute) {
        if ($model->file != null && $model->file->size > $this->maxSize && end(explode('.', $model->file->name)) === "txt") {
            $model->addError($attribute, $this->message);
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $message = json_encode($this->message);
        $maxSize = json_encode($this->maxSize);
        return <<<JS
var file = document.getElementById(attribute.id).files[0];
if (file && file.name.split('.').pop() === 'txt' && file.size > $maxSize) {
    messages.push($message);
} 

JS;
    }

    public static function className()
    {
        return parent::className();
    }
}