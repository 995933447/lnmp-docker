<?php

namespace App\Models;

use App\Models\Connections\BusinessConnectionModel;

class GameAppPostedChannelArgument extends BusinessConnectionModel
{
    public function argumentKey()
    {
        return $this->belongsTo($key = (new PostChannelArgument),'post_channel_argument_id', 'id')
            ->where($key->getTable() . '.status', PostChannel::VALID_STATUS);
    }

    public function gameApp()
    {
        return $this->belongsTo(GameApp::class, 'game_app_id', 'id');
    }

    public function channel()
    {
        return $this->hasOne(PostChannel::class, 'id', 'post_channel_id');
    }
}
