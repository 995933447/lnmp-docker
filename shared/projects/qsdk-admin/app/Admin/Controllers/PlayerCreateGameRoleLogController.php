<?php

namespace App\Admin\Controllers;

use App\Models\GameApp;
use App\Models\PlayerCreateGameRoleLog;
use App\Models\PostChannel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PlayerCreateGameRoleLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '创角记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PlayerCreateGameRoleLog());

        $grid->filter(function ($filter) {
            $filter->where(function ($query) {
                $query->whereHas('gameApp', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });
            }, __('Game app'));

            $filter->startsWith('uid', __('Uid'));

            $filter->startsWith('app_version', __('App version'));

            $filter->equal('role_id', __('Role id'));

            $filter->startsWith('role_name', __('Role name'));

            $filter->equal('server_id', __('Server id'));

            $filter->where(function ($query) {
                $query->whereHas('sdkVersion', function ($query) {
                    $query->where('sdk_version', 'like', "%{$this->input}%");
                });
            }, __('Sdk version'));

            $filter->date('created_at', __('Created at'))->datetime();
            $filter->between('updated_at', __('Updated at'))->datetime();
        });

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $postChannelOptions = [];

            foreach (PostChannel::query()->where('status', 1)->get() as $channel) {
                $postChannelOptions[$channel->id] = $channel->name;
            }

            $selector->select('post_channel_id', __('Post channel'), $postChannelOptions);

            $selector->select('os', __('Os'), [
                PlayerCreateGameRoleLog::PC_OS => PlayerCreateGameRoleLog::transferOsDefinition(PlayerCreateGameRoleLog::PC_OS),
                PlayerCreateGameRoleLog::IOS_OS => PlayerCreateGameRoleLog::transferOsDefinition(PlayerCreateGameRoleLog::IOS_OS),
                PlayerCreateGameRoleLog::ANDROID_OS => PlayerCreateGameRoleLog::transferOsDefinition(PlayerCreateGameRoleLog::ANDROID_OS),
                PlayerCreateGameRoleLog::WAP_OS => PlayerCreateGameRoleLog::transferOsDefinition(PlayerCreateGameRoleLog::WAP_OS),
            ]);
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        $grid->model()->orderBy('created_at', 'DESC');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('role_id', __('Role id'));
        $grid->column('role_name', __('Role name'));
        $grid->column('uid', __('Uid'));
        $grid->column('game_app_id', __('Game app id'));
        $grid->column('gameApp.name', __('Game app'));
        $grid->column('channel.name', __('Post channel'));
        $grid->column('server_id', __('Server id'));
        $grid->column('os', __('Os'))->display(function ($os) {
            return PlayerCreateGameRoleLog::transferOsDefinition((int) $os);
        })->label([
            PlayerCreateGameRoleLog::PC_OS => 'warning',
            PlayerCreateGameRoleLog::IOS_OS => 'info',
            PlayerCreateGameRoleLog::ANDROID_OS => 'primary',
            PlayerCreateGameRoleLog::WAP_OS => 'success'
        ]);
        $grid->column('ip', __('Ip'));
        $grid->column('device_uuid', __('Device uuid'));
        $grid->column('device_brand', __('Device brand'));
        $grid->column('device_os_version', __('Device os version'));
        $grid->column('network', __('Network'))->display(function ($network) {
            return PlayerCreateGameRoleLog::transferNetworkDefinition(is_null($network)? -1: (int) $network);
        })->label([
            PlayerCreateGameRoleLog::UNKNOWN_NET_WORK => 'default',
            PlayerCreateGameRoleLog::TWO_G_NET_WORK => 'info',
            PlayerCreateGameRoleLog::THREE_G_NET_WORK => 'info',
            PlayerCreateGameRoleLog::FOUR_G_NET_WORK => 'primary',
            PlayerCreateGameRoleLog::FIVE_G_NET_WORK => 'success',
            PlayerCreateGameRoleLog::WIFI_NET_WORK => 'warning',
        ]);
        $grid->column('app_version', __('App version'));
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
        $show = new Show(PlayerCreateGameRoleLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('role_id', __('Role id'));
        $show->field('uid', __('Uid'));
        $show->field('game_app_id', __('Game app id'));
        $show->field('gameApp.name', __('Game app'));
        $show->field('channel.name', __('Post channel'));
        $show->field('server_id', __('Server id'));
        $show->field('os', __('Os'))->as(function ($os) {
            return PlayerCreateGameRoleLog::transferOsDefinition((int) $os);
        });
        $show->field('ip', __('Ip'));
        $show->field('device_uuid', __('Device uuid'));
        $show->field('device_brand', __('Device brand'));
        $show->field('device_os_version', __('Device os version'));
        $show->field('network', __('Network'))->as(function ($network) {
            return PlayerCreateGameRoleLog::transferNetworkDefinition(is_null($network)? -1: (int) $network);
        });
        $show->field('app_version', __('App version'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('role_name', __('Role name'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PlayerCreateGameRoleLog());

        $form->text('role_id', __('Role id'));
        $form->text('uid', __('Uid'));

        $gameAppIdMapNames = [];
        foreach (GameApp::query()->get() as $gameApp) {
            $gameAppIdMapNames[$gameApp->id] = "{$gameApp->id} -- {$gameApp->name}";
        }

        $form->select('game_app_id', __('Game app id'))->options($gameAppIdMapNames);

        foreach (PostChannel::query()->get() as $postChannel) {
            $channelIdMapNames[$postChannel->id] = $postChannel->name;
        }

        $form->select('post_channel_id', __('Post channel id'))
            ->options($channelIdMapNames);
        $form->text('server_id', __('Server id'));
        $form->select('os', __('Os'))->options([
            PlayerCreateGameRoleLog::PC_OS => PlayerCreateGameRoleLog::transferOsDefinition(PlayerCreateGameRoleLog::PC_OS),
            PlayerCreateGameRoleLog::IOS_OS => PlayerCreateGameRoleLog::transferOsDefinition(PlayerCreateGameRoleLog::IOS_OS),
            PlayerCreateGameRoleLog::ANDROID_OS => PlayerCreateGameRoleLog::transferOsDefinition(PlayerCreateGameRoleLog::ANDROID_OS),
            PlayerCreateGameRoleLog::WAP_OS => PlayerCreateGameRoleLog::transferOsDefinition(PlayerCreateGameRoleLog::WAP_OS)
        ]);
        $form->ip('ip', __('Ip'));
        $form->text('device_uuid', __('Device uuid'));
        $form->text('device_brand', __('Device brand'));
        $form->text('device_os_version', __('Device os version'));
        $form->select('network', __('Network'))->options([
            PlayerCreateGameRoleLog::UNKNOWN_NET_WORK => PlayerCreateGameRoleLog::transferNetworkDefinition(PlayerCreateGameRoleLog::UNKNOWN_NET_WORK),
            PlayerCreateGameRoleLog::TWO_G_NET_WORK => PlayerCreateGameRoleLog::transferNetworkDefinition(PlayerCreateGameRoleLog::TWO_G_NET_WORK),
            PlayerCreateGameRoleLog::THREE_G_NET_WORK => PlayerCreateGameRoleLog::transferNetworkDefinition(PlayerCreateGameRoleLog::THREE_G_NET_WORK),
            PlayerCreateGameRoleLog::FOUR_G_NET_WORK => PlayerCreateGameRoleLog::transferNetworkDefinition(PlayerCreateGameRoleLog::FOUR_G_NET_WORK),
            PlayerCreateGameRoleLog::FIVE_G_NET_WORK => PlayerCreateGameRoleLog::transferNetworkDefinition(PlayerCreateGameRoleLog::FIVE_G_NET_WORK),
            PlayerCreateGameRoleLog::WIFI_NET_WORK => PlayerCreateGameRoleLog::transferNetworkDefinition(PlayerCreateGameRoleLog::WIFI_NET_WORK),
        ]);
        $form->text('app_version', __('App version'));
        $form->text('role_name', __('Role name'));

        return $form;
    }
}
