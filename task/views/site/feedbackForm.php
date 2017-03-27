<?php
/**
 * Created by PhpStorm.
 * User: himmel
 * Date: 25.03.17
 * Time: 14:48
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\captcha\Captcha;
use yii\imagine\Image;

$this->title = 'Feedback';
?>

<div class="row">

    <div class="col-lg-5">
        <?php if (!Yii::$app->session->hasFlash('feedbackFormSubmit')): ?>

            <?php $form = ActiveForm::begin(['id' => 'feedback-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'homepage') ?>

                <?= $form->field($model, 'message', ['enableAjaxValidation' => true])->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'file')->fileInput() ?>

                <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>

        <?php else: ?>

            <div class="alert alert-success">
                Thank's for you feedback.
            </div>

            <?php
            if ($img != null) {
                if ($miniature) {
                    echo Html::img("files/$img", ['width' => '100px']);
                } else {
                    echo Html::img("files/$img", ['height' => '100px']);
                }
            }
            ?>

        <?php endif; ?>
    </div>
</div>
