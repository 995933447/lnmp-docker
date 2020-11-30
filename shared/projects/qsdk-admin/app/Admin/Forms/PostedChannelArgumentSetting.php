<?php

namespace App\Admin\Forms;

use App\Models\GameAppPostedChannelArgument;
use App\Models\PostChannelArgument;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class PostedChannelArgumentSetting extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = '游戏渠道参数配置';

    private $hiddenInputFields = ['post_channel_sdk_version_id', 'game_app_id', 'post_channel_id', 'back_url', 'post_channel_argument_id' ];

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        foreach ($request->all() as $parameter => $value) {
            if (in_array($parameter, $this->hiddenInputFields)) {
                continue;
            }

            $argumentKeyId = PostChannelArgument::query()
                ->where('post_channel_id', $request->input('post_channel_id'))
                ->where('post_channel_sdk_version_id', $request->input('post_channel_sdk_version_id'))
                ->where('argument_key', $parameter)
                ->value('id');

            if (
                is_null(
                    $argument = GameAppPostedChannelArgument::query()
                        ->where('game_app_id', $request->input('game_app_id'))
                        ->where('post_channel_argument_id', $argumentKeyId)->first()
                )
            ) {
                $argument = new GameAppPostedChannelArgument();
                $argument->post_channel_argument_id = $argumentKeyId;
                $argument->game_app_id = $request->input('game_app_id');
                $argument->post_channel_id = $request->input('post_channel_id');
            }

            $argument->argument_value = $value;

            $argument->save();
        }

        admin_success('Processed successfully.');

        return redirect($request->input('back_url'));
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        foreach ($this->data() as $key => $value) {
            if (in_array($key, $this->hiddenInputFields)) {
                $this->hidden($key);
            } else {
                $this->text($key);
            }
        }
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        $gameAppId = request()->get('game_app_id');
        $channelId = request()->get('post_channel_id');
        $channelSdkVersionId = request()->get('post_channel_sdk_version_id');

        $argumentKeys = PostChannelArgument::with(['values' => function ($query) use ($channelId, $gameAppId) {
            $query->where('post_channel_id', $channelId)->where('game_app_id', $gameAppId);
        }])->where('post_channel_id', $channelId)
            ->where('status', PostChannelArgument::VALID_STATUS)
            ->where('post_channel_sdk_version_id', $channelSdkVersionId)
            ->get();

        $argumentKeyValuesData = [];
        foreach ($argumentKeys as $argumentKey) {
            $argumentKeyValuesData['post_channel_argument_id'] = $argumentKey->id;
            if (!empty($argumentKey->values->toArray()) && isset($argumentKey->values[0])) {
                $argumentKeyValuesData[$argumentKey->argument_key] = $argumentKey->values[0]->argument_value;
            } else {
                $argumentKeyValuesData[$argumentKey->argument_key] = '';
            }
        }

        $argumentKeyValuesData['post_channel_sdk_version_id'] = $channelSdkVersionId;
        $argumentKeyValuesData['game_app_id'] = $gameAppId;
        $argumentKeyValuesData['post_channel_id'] = $channelId;

        $argumentKeyValuesData['back_url'] = back()->getTargetUrl();

        return $argumentKeyValuesData;
    }
}
