<?php
namespace App\Admin\Controllers\Api;

use App\Models\PostChannelSdkVersion;
use Illuminate\Http\Request;

class PostChannelSdkVersionController
{
    public function querySdkVersionsOfPostChannel(Request $request)
    {
        return PostChannelSdkVersion::query()
            ->where('post_channel_id', $request->get('q'))
            ->get(['id', \Illuminate\Support\Facades\DB::raw('sdk_version as text')])
            ->toArray();
    }
}
