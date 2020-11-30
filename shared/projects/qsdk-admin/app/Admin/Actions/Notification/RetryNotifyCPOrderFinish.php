<?php
namespace App\Admin\Actions\Notification;

use App\Models\NotifyCpOrderFinishLog;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class RetryNotifyCPOrderFinish extends RowAction
{
    public $name = '重发通知';

    public function handle(Model $model)
    {
        $notifyRequestInfo = json_decode($model->notify_request_content, true);
        if (!$notifyRequestInfo) {
            return $this->error('请求信息格式不正确');
        }

        $notifyLog = new NotifyCpOrderFinishLog();
    }

    public function dialog()
    {
        return $this->confirm("确定重发该消息通知?");
    }
}
