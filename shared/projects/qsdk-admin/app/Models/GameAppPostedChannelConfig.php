<?php

namespace App\Models;

use App\Models\Connections\BusinessConnectionModel;

class GameAppPostedChannelConfig extends BusinessConnectionModel
{
    const OPENED_LOGIN = 1;

    const CLOSED_LOGIN = 0;

    const NEW_ROLE_CAN_ENTER_GAME = 1;

    const NEW_ROLE_CAN_NOT_ENTER_GAME = 0;

    const OPENED_PAY = 1;

    const CLOSED_PAY = 0;

    public function gameAppPostedChannel()
    {
        return $this->belongsTo(GameAppPostedChannel::class, 'game_app_posted_channel_id', 'id');
    }

    public static function transferCanLoginDefinition(int $isOpenedLogin): string
    {
        switch ($isOpenedLogin) {
            case static::OPENED_LOGIN:
                return '允许';
            case static::CLOSED_LOGIN:
                return '封禁';
        }
    }

    public static function transferCanPayDefinition(int $isOpenedPay): string
    {
        switch ($isOpenedPay) {
            case static::OPENED_PAY:
                return '允许';
            case static::CLOSED_PAY:
                return '封禁';
        }
    }

    public static function transferCanNewRoleEnterGameDefinition(int $canNewRoleEnterGame)
    {
        switch ($canNewRoleEnterGame) {
            case static::NEW_ROLE_CAN_ENTER_GAME:
                return '允许';
            case static::NEW_ROLE_CAN_NOT_ENTER_GAME:
                return '封禁';
        }
    }
}
