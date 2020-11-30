<?php
namespace App\Admin\Actions\GameAppPostedChannel;

use App\Models\GameAppPostedChannel;
use App\Models\PostChannel;
use App\Models\PostChannelSdkVersion;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Actions\RowAction;
use Illuminate\Http\Request;

class ToEditPostedChannelArugumentJumper extends RowAction
{
    public $name = '游戏渠道参数配置';

    public function handle(Model $model, Request $request)
    {
        return $this->response()
            ->redirect(
                "/admin/game-app-posted-channel/setting?game_app_id={$model->game_app_id}" .
                "&post_channel_id={$model->post_channel_id}&post_channel_sdk_version_id={$request->input('post_channel_sdk_version_id')}&_ifraem_id_="
            );
    }

    public function form()
    {
        $gameAppPostedChannelId = $this->getKey();
        $channelId = GameAppPostedChannel::query()->where('id', $gameAppPostedChannelId)->value('post_channel_id');
        $channelSdkVersions = PostChannelSdkVersion::query()->where('post_channel_id', $channelId)->get();

        $channelSdkVersionIdMapNames = [];
        foreach ($channelSdkVersions as $channelSdkVersion) {
            $channelSdkVersionIdMapNames[$channelSdkVersion->id] = $channelSdkVersion->sdk_version;
        }

        $this->radio('post_channel_sdk_version_id', __('Please choose channel sdk version'))
            ->options($channelSdkVersionIdMapNames)
            ->default(PostChannel::query()->where('id', $channelId)->value('sdk_version_id'));
    }
}
