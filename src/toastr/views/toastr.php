<?php

use common\widgets\toastr\assets\ToastrAsset;
use yii\helpers\Json;

ToastrAsset::register($this);

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