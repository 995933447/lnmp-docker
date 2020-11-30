<?php
namespace App\Admin\Controllers\Api;

use Illuminate\Http\Request;

class GameAppController
{
    public function queryGameAppsOfUser(Request $request)
    {
        return \App\Models\GameApp::query()
            ->where('user_id', $request->get('q'))
            ->get(['id', \Illuminate\Support\Facades\DB::raw('name as text')])
            ->toArray();
    }
}
