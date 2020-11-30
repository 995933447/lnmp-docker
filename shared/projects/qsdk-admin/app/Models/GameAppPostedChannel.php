<?php

namespace App\Models;

use App\Models\Connections\BusinessConnectionModel;

class GameAppPostedChannel extends BusinessConnectionModel
{
    public function gameApp()
    {
        return $this->belongsTo(GameApp::class, 'game_app_id', 'id');
    }

    public function channel()
    {
        return $this->belongsTo(PostChannel::class, 'post_channel_id', 'id');
    }

    public function argumentKeys()
    {
        return $this->hasMany(PostChannelArgument::class, 'post_channel_id', 'post_channel_id')
            ->where('status', PostChannelArgument::VALID_STATUS);
    }

    public function channelSdkVersion()
    {
        return $this->hasOne(PostChannelSdkVersion::class, 'id', 'sdk_version_id');
    }

    public function createdAtChannelSdkVersion()
    {
        return $this->hasOne(PostChannelSdkVersion::class, 'id', 'created_at_sdk_version_id');
    }
}
