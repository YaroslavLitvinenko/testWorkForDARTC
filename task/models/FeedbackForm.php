<?php
/**
 * Created by PhpStorm.
 * User: himmel
 * Date: 25.03.17
 * Time: 13:44
 */

namespace app\models;


use app\controllers\FileValidator;
use xj\ua\UserAgent;
use Yii;
use yii\base\Model;
use yii\bootstrap\Html;
use yii\imagine\Image;

class FeedbackForm extends Model
{
    public $username;
    public $email;
    public $homepage;
    public $message;
    public $file;
    public $captcha;

    public function rules()
    {
        return [
            [['username', 'email', 'message'], 'required'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9]*$/i'],
            ['email', 'email'],
            ['homepage', 'url', 'defaultScheme' => 'http'],
            ['homepage', 'default'],
            ['message', 'validateMessage'],
            ['file', 'file', 'extensions' => ['jpg', 'gif', 'png', 'txt']],
            ['file', FileValidator::className()],
            ['captcha', 'captcha'],
        ];
    }

    public function upload()
    {
        if ($this->file !== null) {
            if ($this->file->extension !== 'txt') {
                $img = Image::getImagine()->open($this->file->tempName);
                $width = $img->getSize()->getWidth();
                $height = $img->getSize()->getHeight();
                if ($this->file->extension !== 'txt') {
                    //Determine the larger side
                    if ($width > $height) {
                        $moreSide = &$width;
                        $lessSide = &$height;
                    } else {
                        $moreSide = &$height;
                        $lessSide = &$width;
                    }

                    if ($moreSide > 320) {//If you need to cut on the larger side
                        $ratio = $moreSide/$lessSide;

                        $moreSide = 320;
                        $lessSide = round($moreSide/$ratio);
                    } elseif ($lessSide > 240) {//If you need to cut off on the smaller side
                        $ratio = $lessSide/$moreSide;

                        $lessSide = 240;
                        $moreSide = round($lessSide/$ratio);
                    }
                }

                //Save file with new size
                Image::thumbnail($this->file->tempName, $width, $height)
                    ->save(Yii::getAlias("files/$this->username." . $this->file->extension));
            } else {
                $this->file->saveAs("files/$this->username." . $this->file->extension);
            }
            return true;
        } else {
            return false;
        }
    }

    public function validateMessage()
    {
        if ($this->message !== Html::encode($this->message)) {
            $this->addError('message', 'Incorrect field');
        }
    }

    public function sendMail($email)
    {
        Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->username])
            ->setSubject('New feedback')
            ->setTextBody($this->message)
            ->send();
    }

    public function getModel() {
        $model = new Feedback();
        $model->username = $this->username;
        $model->email = $this->email;
        $model->homepage = $this->homepage;
        $model->message = $this->message;
        $model->date = date('Y-m-d');
        if ($this->file != null) {
            $model->file = $this->username . "." . $this->file->extension;
        }
        $model->user_ip = Yii::$app->request->userIP;
        $userAgent = UserAgent::model();
        $model->browser = $userAgent->browser . " " . $userAgent->version;

        return $model;
    }
}
