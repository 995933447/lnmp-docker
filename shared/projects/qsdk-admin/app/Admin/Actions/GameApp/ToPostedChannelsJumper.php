<?php

namespace App\Admin\Actions\GameApp;

use Encore\Admin\Actions\RowAction;

class ToPostedChannelsJumper extends RowAction
{
    public $name = '关联渠道管理';

    public function href()
    {
        return "/admin/game-app-posted-channels?game_app_id={$this->getKey()}";
    }
}
