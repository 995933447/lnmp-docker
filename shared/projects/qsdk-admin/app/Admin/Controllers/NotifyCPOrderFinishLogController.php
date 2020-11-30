<?php

namespace App\Admin\Controllers;

use App\Models\NotifyCpOrderFinishLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class NotifyCPOrderFinishLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '通知研发玩家完成充值日志';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new NotifyCpOrderFinishLog());

        $grid->filter(function ($filter) {
            $filter->equal('order_id', __('Order id'));
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        $grid->model()->orderBy('created_at', 'DESC');

        $grid->column('id', __('Id'))->sortable();

        $grid->column('order_id', __('Order id'))->width(200);

        $grid->column('try_num', __('Try num'))->display(function ($tryNum) {
            return "第{$tryNum}次";
        });
        $grid->column('notify_status', __('Notify status'))->display(function ($notifyStatus) {
            return NotifyCpOrderFinishLog::transferNotifyStatusDefinition($notifyStatus);
        })->label([
            NotifyCpOrderFinishLog::SUCCESS_NOTIFY_STATUS => 'success',
            NotifyCpOrderFinishLog::FAILED_NOTIFY_STATUS => 'danger'
        ]);
        $grid->column('exception', __('Exception'))->width(300);
        $grid->column('execute_time', __('Execute time'))->display(function ($executeTime) {
            return "{$executeTime}s";
        });
        $grid->column('notify_request_content', __('Notify request content'))->width(300);
        $grid->column('notify_response_content', __('Notify response content'))->width(300);
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(NotifyCpOrderFinishLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_id', __('Order id'));
        $show->field('try_num', __('Try num'))->as(function ($tryNum) {
            return "第{$tryNum}次";
        });
        $show->field('notify_status', __('Notify status'))->as(function ($notifyStatus) {
            return NotifyCpOrderFinishLog::transferNotifyStatusDefinition($notifyStatus);
        });
        $show->field('exception', __('Exception'));
        $show->field('execute_time', __('Execute time'))->as(function ($executeTime) {
            return "{$executeTime}s";
        });
        $show->field('notify_request_content', __('Notify request content'));
        $show->field('notify_response_content', __('Notify response content'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new NotifyCpOrderFinishLog());

        $form->text('order_id', __('Order id'));
        $form->number('try_num', __('Try num'));
        $form->select('notify_status', __('Notify status'))->options([
            0 => '失败',
            1 => '成功'
        ]);
        $form->textarea('exception', __('Exception'));
        $form->text('execute_time', __('Execute time'));
        $form->textarea('notify_request_content', __('Notify request content'));
        $form->textarea('notify_response_content', __('Notify response content'));

        return $form;
    }
}
