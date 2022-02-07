<?php

namespace atmaliance\yii2_atm_widget\alert;

use atmaliance\yii2_atm_widget\toastr\ToastrWidget;
use yii\bootstrap\Alert as BootstrapAlert;
use yii\bootstrap\Widget;

class AlertWidget extends Widget
{
    public array $alertTypes = [
        'error' => [
            'class' => 'alert-danger',
            'icon' => '<i class="icon fa fa-ban"></i>',
        ],
        'danger' => [
            'class' => 'alert-danger',
            'icon' => '<i class="icon fa fa-ban"></i>',
        ],
        'success' => [
            'class' => 'alert-success',
            'icon' => '<i class="icon fa fa-check"></i>',
        ],
        'info' => [
            'class' => 'alert-info',
            'icon' => '<i class="icon fa fa-info"></i>',
        ],
        'warning' => [
            'class' => 'alert-warning',
            'icon' => '<i class="icon fa fa-warning"></i>',
        ],
    ];

    public array $closeButton = [];
    public bool $isAjaxRemoveFlash = true;

    public function init()
    {
        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes();
        $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach ($flashes as $type => $flashInfo) {
            switch ($type) {
                case 'error':
                case 'danger':
                case 'success':
                case 'info':
                case 'warning':
                    foreach ((array)$flashInfo as $message) {
                        $this->options['class'] = $this->alertTypes[$type]['class'] . $appendCss;
                        $this->options['id'] = $this->getId() . '-' . $type;

                        echo BootstrapAlert::widget([
                            'body' => $this->alertTypes[$type]['icon'] . $message,
                            'closeButton' => $this->closeButton,
                            'options' => $this->options,
                        ]);
                    }

                    if ($this->isAjaxRemoveFlash && !\Yii::$app->request->isAjax) {
                        $session->removeFlash($type);
                    }

                    break;

                case 'toastr':
                    if (count($flashInfo) === 2) {
                        echo ToastrWidget::widget([
                            'message' => $flashInfo['message'] ?? null,
                            'type' => $flashInfo['type'] ?? null,
                        ]);
                    } else {
                        array_map(static function($flashInfoItem) {
                            echo ToastrWidget::widget([
                                'message' => $flashInfoItem['message'] ?? null,
                                'type' => $flashInfoItem['type'] ?? null,
                            ]);
                        }, (array)$flashInfo);
                    }

                    $session->removeFlash($type);
                    break;
            }
        }
    }
}
