<?php

use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $message string */
/* @var $type string */

$this->registerJs(
    'toastrModalWindow.init(' . Json::encode(
        [
            'message' => $message,
            'type' => $type,
        ]
    ) . ');'
);