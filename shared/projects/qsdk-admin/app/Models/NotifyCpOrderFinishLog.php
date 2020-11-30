<?php

namespace App\Models;

use App\Models\Connections\BusinessConnectionModel;

class NotifyCpOrderFinishLog extends BusinessConnectionModel
{
    const SUCCESS_NOTIFY_STATUS = 1;

    const FAILED_NOTIFY_STATUS = 0;

    public static function transferNotifyStatusDefinition(int $notifyStatus): string
    {
        switch ($notifyStatus) {
            case static::SUCCESS_NOTIFY_STATUS:
                return '成功';
            case static::FAILED_NOTIFY_STATUS:
                return '失败';
        }
    }
}
