<?php

namespace atmaliance\yii2_atm_widget\toastr;

use yii\base\Widget;

final class ToastrWidget extends Widget
{
    public ?string $message = null;
    public ?string $type = null;
    private array $notificationTypes = [
        'error',
        'warning',
        'error',
        'success',
    ];

    public function beforeRun(): bool
    {
        if (!parent::beforeRun()) {
            return false;
        }

        if (empty(trim($this->message)) || empty(trim($this->type))) {
            return false;
        }

        return in_array($this->type, $this->notificationTypes, true);
    }

    public function run()
    {
        echo $this->render('toastr', [
            'message' => $this->message,
            'type' => $this->type,
        ]);
    }
}
