<?php

namespace App\Models;

use App\Models\Connections\BusinessConnectionModel;

class SupportGameType extends BusinessConnectionModel
{
    const VALID_STATUS = 1;

    const INVALID_STATUS = 0;

    public static function transferStatusDefinition(int $status)
    {
        switch ($status) {
            case static::VALID_STATUS:
                return '启用';
            case static::INVALID_STATUS:
                return '未启用';
        }
    }
}
