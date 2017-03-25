<?php
/**
 * Created by PhpStorm.
 * User: himmel
 * Date: 25.03.17
 * Time: 17:09
 */

use yii\grid\GridView;
use yii\helpers\Html;

?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'username',
        'email',
        'homepage',
        [
            'attribute' => 'date',
            'attribute' => 'date',
            'value' => function ($data) {
                return Html::encode($data->message);
            }
        ],
        [
            'attribute' => 'date',
            'value' => function ($data) {
                return Yii::$app->formatter->asDate($data->date, 'long');
            }
        ]
    ]
]);
?>