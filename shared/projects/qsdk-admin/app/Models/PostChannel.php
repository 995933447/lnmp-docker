<?php

namespace App\Models;

use App\Models\Connections\BusinessConnectionModel;

class PostChannel extends BusinessConnectionModel
{
    const VALID_STATUS = 1;

    const INVALID_STATUS = 0;

    const ANDROID_POST_PLATFORM = 0;

    const IOS_POST_PLATFORM = 1;

    const H5_POST_PLATFORM = 2;

    const FOREIGN_POST_PLATFORM = 3;

    public function sdkVersion()
    {
        return $this->hasOne(PostChannelSdkVersion::class, 'id', 'sdk_version_id');
    }

    public function argumentKeys()
    {
        return $this->hasMany(PostChannelArgument::class, 'post_channel_id', 'id')
            ->where('status', PostChannelArgument::VALID_STATUS);
    }

    public static function transferStatusDefinition(int $status): string
    {
        switch ($status) {
            case static::VALID_STATUS:
                return '启用';
            case static::INVALID_STATUS:
                return '未启用';
        }
    }

    public static function transferPostPlatformDefinition(int $postPlatform): string
    {
        switch ($postPlatform) {
            case static::ANDROID_POST_PLATFORM:
                return "安卓";
            case static::IOS_POST_PLATFORM:
                return 'IOS';
            case static::H5_POST_PLATFORM:
                return "H5";
            case static::FOREIGN_POST_PLATFORM:
                return "海外";
        }
    }
}
