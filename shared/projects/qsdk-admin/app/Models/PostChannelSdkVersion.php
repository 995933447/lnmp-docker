<?php

namespace App\Models;

use App\Models\Connections\BusinessConnectionModel;

class PostChannelSdkVersion extends BusinessConnectionModel
{
    const VALID_STATUS = 1;

    const INVALID_STATUS = 0;

    public function channel()
    {
        return $this->belongsTo(PostChannel::class, 'post_channel_id', 'id');
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
}
