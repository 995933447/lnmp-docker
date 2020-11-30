<?php

namespace App\Models;

use App\Models\Connections\BusinessConnectionModel;

class PostChannelArgument extends BusinessConnectionModel
{
    const VALID_STATUS = 1;

    const INVALID_STATUS = 0;

    const TEXT_ARGUMENT_TYPE = 0;

    public static function transferStatusDefinition(int $status)
    {
        switch ($status) {
            case static::VALID_STATUS:
                return '启用';
            case static::INVALID_STATUS:
                return '未启用';
        }
    }

    public static function transferArgumentTypeDefinition(int $argumentType) {
        switch ($argumentType) {
            case static::TEXT_ARGUMENT_TYPE:
                return '文本';
            default:
                return '未知';
        }
    }

    public function sdkVersion()
    {
        return $this->hasOne(PostChannelSdkVersion::class, 'id', 'post_channel_sdk_version_id');
    }

    public function channel()
    {
        return $this->belongsTo(PostChannel::class, 'post_channel_id', 'id');
    }

    public function values()
    {
        return $this->hasMany(GameAppPostedChannelArgument::class, 'post_channel_argument_id', 'id');
    }
}
