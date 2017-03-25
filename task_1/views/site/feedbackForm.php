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

$this->title = 'Feedback';
?>

<div class="row">

    <div class="col-lg-5">
        <?php if (!Yii::$app->session->hasFlash('feedbackFormSubmit')): ?>

            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'homepage') ?>

                <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>

        <?php else: ?>

            <div class="alert alert-success">
                Thank you for feedback.
            </div>

        <?php endif; ?>
    </div>
</div>
