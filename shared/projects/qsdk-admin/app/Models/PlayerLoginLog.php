<?php

namespace App\Models;

use App\Models\Connections\BusinessConnectionModel;

class PlayerLoginLog extends BusinessConnectionModel
{
    const PC_OS = 0;
    const IOS_OS = 1;
    const ANDROID_OS = 2;
    const WAP_OS = 3;

    const UNKNOWN_NET_WORK = 4;
    const TWO_G_NET_WORK = 0;
    const THREE_G_NET_WORK = 1;
    const FOUR_G_NET_WORK = 2;
    const FIVE_G_NET_WORK = 3;
    const WIFI_NET_WORK = 4;

    public static function transferOsDefinition(int $os): string
    {
        switch ($os) {
            case static::PC_OS:
                return 'PC';
            case static::IOS_OS:
                return 'IOS';
            case static::ANDROID_OS:
                return '安卓';
            case static::WAP_OS:
                return 'WAP';
        }
    }

    public static function transferNetworkDefinition(int $network)
    {
        switch ($network) {
            case static::TWO_G_NET_WORK:
                return '2G';
            case static::THREE_G_NET_WORK:
                return '3G';
            case static::FOUR_G_NET_WORK:
                return '4G';
            case static::FIVE_G_NET_WORK:
                return '5G';
            case static::WIFI_NET_WORK:
                return 'wifi';
            case static::UNKNOWN_NET_WORK:
            default:
                return '未知';
        }
    }

    public function channel()
    {
        return $this->belongsTo(PostChannel::class, 'post_channel_id', 'id');
    }

    public function gameApp()
    {
        return $this->belongsTo(GameApp::class, 'game_app_id', 'id');
    }

    public function sdkVersion()
    {
        return $this->hasOne(PostChannelSdkVersion::class, 'id', 'sdk_version_id');
    }
}
