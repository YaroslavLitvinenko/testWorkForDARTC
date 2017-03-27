<?php
/**
 * Created by PhpStorm.
 * User: himmel
 * Date: 25.03.17
 * Time: 17:09
 */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Admin area';
?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'username',
        'email',
        'homepage',
        [
            'attribute' => 'message',
            'value' => function ($data) {
                return Html::encode($data->message);
            }
        ],
        'user_ip',
        'browser',
        [
            'attribute' => 'date',
            'value' => function ($data) {
                return Yii::$app->formatter->asDate($data->date, 'long');
            }
        ],
        [
            'attribute'=>'file',
            'format' => 'raw',
            'value'=>function ($data) {
                if ($data->file != null) {
                    if (end(explode(".", $data->file)) !== "txt") {
                        return Html::a("$data->file", "files/$data->file", ['class' => 'popup-link']);
                    } else {
                        return Html::a("$data->file", "files/$data->file");
                    }
                } else {
                    return null;
                }
            },
        ],
    ]
]);
?>
