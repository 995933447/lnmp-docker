<?php
namespace App\Admin\Requests;

use App\Models\PostChannel;

class CreateGameAppPostedChannelRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules()
    {
        return [
            'post_channel_id' => [
                function ($attribute, $value, $fail) {
                    $channel = PostChannel::query()->find($value);
                    if (!$channel->sdk_version_id) {
                        return $fail("渠道 {$channel->name} 未配置版本号");
                    }
                }
            ]
        ];
    }
}
