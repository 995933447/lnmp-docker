<?php
namespace App\Admin\Actions\GameAppPostedChannel;

use App\Models\GameAppPostedChannelConfig;
use Encore\Admin\Actions\RowAction;

class ToEditPostedChannelConfigJumper extends RowAction
{
    public $name = '封禁配置';

    public function href()
    {
        $configId = GameAppPostedChannelConfig::query()->where('game_app_posted_channel_id', $this->getKey())->value('id');
        return "/admin/game-app-posted-channel-configs/{$configId}";
    }
}
