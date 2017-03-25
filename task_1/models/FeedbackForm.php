<?php
/**
 * Created by PhpStorm.
 * User: himmel
 * Date: 25.03.17
 * Time: 13:44
 */

namespace app\models;


use yii\base\Model;
use yii\bootstrap\Html;

class FeedbackForm extends Model
{
    public $username;
    public $email;
    public $homepage;
    public $message;
    public $captcha;

    public function rules()
    {
        return [
            [['username', 'email', 'message'], 'required'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9]*$/i'],
            ['email', 'email'],
            ['homepage', 'url', 'defaultScheme' => 'http'],
            ['message', 'validateMessage'],
            ['captcha', 'captcha'],
        ];
    }

    public function validateMessage()
    {
        if ($this->message !== Html::encode($this->message)) {
            $this->addError('message', 'Incorrect field');
        }
    }

    public function sendMail($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom($this->email)
                ->setSubject('New feedback')
                ->setTextBody($this->message)
                ->send();

            return true;
        }
        return false;
    }

    public function getModel() {
        $model = new Feedback();
        $model->username = $this->username;
        $model->email = $this->email;
        $model->homepage = $this->homepage;
        $model->message = $this->message;
        $model->date = date('Y-m-d');

        return $model;
    }
}