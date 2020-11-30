<?php
namespace App\Admin\Actions\PostChannel;

use App\Models\GameAppPostedChannelConfig;
use Encore\Admin\Actions\RowAction;

class ToAddPostChannelArgumentItemJumper extends RowAction
{
    public $name = '添加渠道sdk参数项';

    public function href()
    {
        return "/admin/post-channel-argument/setting/{$this->getKey()}";
    }
}
