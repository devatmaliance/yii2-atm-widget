<?php

namespace atmaliance\yii2_atm_widget\alert\bootstrap4;

use atmaliance\yii2_atm_widget\toastr\Toastr;
use yii\bootstrap\Widget;
use yii\bootstrap4\Alert as Bootstrap4Alert;

class Alert extends Widget
{
    private const ERROR_ALERT_TYPE = 'error';
    private const DANGER_ALERT_TYPE = 'danger';
    private const SUCCESS_ALERT_TYPE = 'success';
    private const WARNING_ALERT_TYPE = 'warning';
    private const INFO_ALERT_TYPE = 'info';
    private const TOASTR_ALERT_TYPE = 'toastr';

    public array $alertTypes = [
        self::ERROR_ALERT_TYPE => [
            'class' => 'alert-danger',
            'icon' => '<i class="icon fa fa-ban"></i>',
        ],
        self::DANGER_ALERT_TYPE => [
            'class' => 'alert-danger',
            'icon' => '<i class="icon fa fa-ban"></i>',
        ],
        self::SUCCESS_ALERT_TYPE => [
            'class' => 'alert-success',
            'icon' => '<i class="icon fa fa-check"></i>',
        ],
        self::INFO_ALERT_TYPE => [
            'class' => 'alert-info',
            'icon' => '<i class="icon fa fa-info"></i>',
        ],
        self::WARNING_ALERT_TYPE => [
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
                case self::ERROR_ALERT_TYPE:
                case self::DANGER_ALERT_TYPE:
                case self::SUCCESS_ALERT_TYPE:
                case self::INFO_ALERT_TYPE:
                case self::WARNING_ALERT_TYPE:
                    foreach ((array)$flashInfo as $message) {
                        $this->options['class'] = $this->alertTypes[$type]['class'] . $appendCss;
                        $this->options['id'] = $this->getId() . '-' . $type;

                        echo Bootstrap4Alert::widget([
                            'body' => $this->alertTypes[$type]['icon'] . $message,
                            'closeButton' => $this->closeButton,
                            'options' => $this->options,
                        ]);
                    }

                    if ($this->isAjaxRemoveFlash && !\Yii::$app->request->isAjax) {
                        $session->removeFlash($type);
                    }

                    break;

                case self::TOASTR_ALERT_TYPE:
                    array_map(static function($flashInfoItem) {
                        echo Toastr::widget([
                            'message' => $flashInfoItem['message'] ?? null,
                            'type' => $flashInfoItem['type'] ?? null,
                        ]);
                    }, (array)$flashInfo);

                    $session->removeFlash($type);
                    break;
            }
        }
    }
}
