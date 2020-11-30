<?php

namespace App\Admin\Controllers;

use App\Models\GameApp;
use App\Models\OrderLog;
use App\Models\PostChannel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\QueryFilters\TimestampBetween;

class OrderLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderLog());

        $grid->filter(function ($filter) {
            $filter->where(function ($query) {
                $query->whereHas('gameApp', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });
            }, __('Game app'));

            $filter->startsWith('user_id', __('Uid'));

            $filter->startWith('app_version', __('App version'));

            $filter->equal('order_id', __('Order id'));

            $filter->equal('cp_order_id', __('Cp order id'));

            $filter->equal('posted_channel_order_id', __('Posted channel order id'));

            $filter->startsWith('app_version', __('App version'));

            $filter->where(function ($query) {
                $query->whereHas('sdkVersion', function ($query) {
                    $query->where('sdk_version', 'like', "%{$this->input}%");
                });
            }, __('Sdk version'));

            $filter->date('created_at', __('Created at'))->datetime();
            $filter->between('updated_at', __('Updated at'))->datetime();
            $filter->use(new TimestampBetween('payed_at', __('Payed at')))->datetime();
        });

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $postChannelOptions = [];

            foreach (PostChannel::query()->where('status', 1)->get() as $channel) {
                $postChannelOptions[$channel->id] = $channel->name;
            }

            $selector->select('posted_channel_id', __('Post channel'), $postChannelOptions);

            $selector->select('os', __('Os'), [
                OrderLog::PC_OS => OrderLog::transferOsDefinition(OrderLog::PC_OS),
                OrderLog::IOS_OS => OrderLog::transferOsDefinition(OrderLog::IOS_OS),
                OrderLog::ANDROID_OS => OrderLog::transferOsDefinition(OrderLog::ANDROID_OS),
                OrderLog::WAP_OS => OrderLog::transferOsDefinition(OrderLog::WAP_OS),
            ]);

            $selector->select('status', __('Status'), [
                OrderLog::NOT_PAY_STATUS => OrderLog::transferStatusDefinition(OrderLog::NOT_PAY_STATUS),
                OrderLog::PAYED_STATUS => OrderLog::transferStatusDefinition(OrderLog::PAYED_STATUS),
                OrderLog::CLOSED_STATUS => OrderLog::transferStatusDefinition(OrderLog::CLOSED_STATUS)
            ]);

            $selector->select('is_test', __('Is test'), [
                OrderLog::IS_TEST_RECORD => OrderLog::transferIsTestDefinition(OrderLog::IS_TEST_RECORD),
                OrderLog::IS_NOT_TEST_RECORD => OrderLog::transferIsTestDefinition(OrderLog::IS_NOT_TEST_RECORD)
            ]);
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        $grid->model()->orderBy('created_at', 'DESC');

        $grid->column('id', __('Id'))->sortable();

        $grid->column('game_app_id', __('Game app id'));
        $grid->column('gameApp.name', __('Game app'));
        $grid->column('channel.name', __('Post channel'));
        $grid->column('order_id', __('Order id'))->width(150);
        $grid->column('posted_channel_order_id', __('Posted channel order id'))->width(150);
        $grid->column('cp_order_id', __('Cp order id'))->width(150);
        $grid->column('user_id', __('User id'));
        $grid->column('role_id', __('Role id'));
        $grid->column('product_id', __('Product id'));
        $grid->column('money', __('Money'));
        $grid->column('server_id', __('Server id'));
        $grid->column('os', __('Os'))->display(function ($os) {
            return OrderLog::transferOsDefinition((int) $os);
        })->label([
            OrderLog::PC_OS => 'warning',
            OrderLog::IOS_OS => 'info',
            OrderLog::ANDROID_OS => 'primary',
            OrderLog::WAP_OS => 'success'
        ]);
        $grid->column('device_uuid', __('Device uuid'));
        $grid->column('ip', __('Ip'));
        $grid->column('status', __('Status'))->display(function ($status) {
            return OrderLog::transferStatusDefinition((int) $status);
        })->label([
            OrderLog::NOT_PAY_STATUS => 'warning',
            OrderLog::PAYED_STATUS => 'success',
            OrderLog::CLOSED_STATUS => 'danger'
        ]);
        $grid->column('remark', __('Remark'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('real_pay_money', __('Real pay money'));
        $grid->column('payed_at', __('Payed at'))->display(function ($payedAt) {
            if (!$payedAt) {
                return '';
            }
            return date('Y-m-d H:i:s', $payedAt);
        });
        $grid->column('is_test', __('Is test'))->display(function ($isTest) {
            return OrderLog::transferIsTestDefinition($isTest);
        })->label([
            OrderLog::IS_TEST_RECORD => 'danger',
            OrderLog::IS_NOT_TEST_RECORD => 'info'
        ]);
        $grid->column('extra', __('Extra'));

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
        $show = new Show(OrderLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('game_app_id', __('Game app id'));
        $show->field('gameApp.name', __('Game app'));
        $show->field('channel.name', __('Post channel'));
        $show->field('order_id', __('Order id'));
        $show->field('posted_channel_order_id', __('Posted channel order id'));
        $show->field('cp_order_id', __('Cp order id'));
        $show->field('user_id', __('User id'));
        $show->field('role_id', __('Role id'));
        $show->field('product_id', __('Product id'));
        $show->field('money', __('Money'));
        $show->field('server_id', __('Server id'));
        $show->field('os', __('Os'))->as(function ($os) {
            return OrderLog::transferOsDefinition((int) $os);
        });
        $show->field('device_uuid', __('Device uuid'));
        $show->field('ip', __('Ip'));
        $show->field('status', __('Status'))->as(function ($status) {
            return OrderLog::transferStatusDefinition((int) $status);
        });
        $show->field('remark', __('Remark'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('real_pay_money', __('Real pay money'));
        $show->field('payed_at', __('Payed at'))->as(function ($payedAt) {
            return date('Y-m-d H:i:s', $payedAt);
        });
        $show->field('is_test', __('Is test'))->as(function ($isTest) {
            return OrderLog::transferIsTestDefinition($isTest);
        });
        $show->field('extra', __('Extra'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OrderLog());

        $gameAppIdMapNames = [];
        foreach (GameApp::query()->get() as $gameApp) {
            $gameAppIdMapNames[$gameApp->id] = "{$gameApp->id} -- {$gameApp->name}";
        }

        $form->select('game_app_id', __('Game app id'))->options($gameAppIdMapNames);

        foreach (PostChannel::query()->get() as $postChannel) {
            $channelIdMapNames[$postChannel->id] = $postChannel->name;
        }

        $form->select('posted_channel_id', __('Post channel id'))
            ->options($channelIdMapNames);
        $form->text('order_id', __('Order id'));
        $form->text('posted_channel_order_id', __('Posted channel order id'));
        $form->text('cp_order_id', __('Cp order id'));
        $form->text('user_id', __('User id'));
        $form->text('role_id', __('Role id'));
        $form->text('product_id', __('Product id'));
        $form->text('money', __('Money'));
        $form->text('server_id', __('Server id'));
        $form->select('os', __('Os'))->options([
            OrderLog::PC_OS => 'pc',
            OrderLog::IOS_OS => 'ios',
            OrderLog::ANDROID_OS => '安卓',
            OrderLog::WAP_OS => 'wap'
        ]);
        $form->text('device_uuid', __('Device uuid'));
        $form->ip('ip', __('Ip'));
        $form->select('status', __('Status'))->options([
            OrderLog::NOT_PAY_STATUS => OrderLog::transferStatusDefinition(OrderLog::NOT_PAY_STATUS),
            OrderLog::PAYED_STATUS => OrderLog::transferStatusDefinition(OrderLog::PAYED_STATUS),
            OrderLog::CLOSED_STATUS => OrderLog::transferStatusDefinition(OrderLog::CLOSED_STATUS),
        ]);
        $form->text('remark', __('Remark'));
        $form->text('real_pay_money', __('Real pay money'))->rules('required_if:status,' . OrderLog::PAYED_STATUS);
        $form->datetime('payed_at', __('Payed at'))->rules('required_if:status,' . OrderLog::PAYED_STATUS);
        $form->switch('is_test', __('Is test'));
        $form->textarea('extra', __('Extra'));

        //保存前回调
        $form->saving(function (Form $form) {
            $form->payed_at = strtotime($form->payed_at);
        });

        return $form;
    }
}
