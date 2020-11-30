<?php

namespace App\Admin\Controllers;

use App\Models\GameApp;
use App\Models\PostChannel;
use App\Models\PostChannelSdkVersion;
use App\Models\RegisterPlayerLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RegiterPlayerLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '玩家注册记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new RegisterPlayerLog());

        $grid->filter(function ($filter) {
            $filter->where(function ($query) {
                $query->whereHas('gameApp', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });
            }, __('Game app'));

            $filter->startsWith('uid', __('Uid'));

            $filter->startsWith('app_version', __('App version'));

            $filter->where(function ($query) {
                $query->whereHas('sdkVersion', function ($query) {
                    $query->where('sdk_version', 'like', "%{$this->input}%");
                });
            }, __('Sdk version'));
        });

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $postChannelOptions = [];

            foreach (PostChannel::query()->where('status', 1)->get() as $channel) {
                $postChannelOptions[$channel->id] = $channel->name;
            }

            $selector->select('post_channel_id', __('Post channel'), $postChannelOptions);

            $selector->select('os', __('Os'), [
                RegisterPlayerLog::PC_OS => RegisterPlayerLog::transferOsDefinition(RegisterPlayerLog::PC_OS),
                RegisterPlayerLog::IOS_OS => RegisterPlayerLog::transferOsDefinition(RegisterPlayerLog::IOS_OS),
                RegisterPlayerLog::ANDROID_OS => RegisterPlayerLog::transferOsDefinition(RegisterPlayerLog::ANDROID_OS),
                RegisterPlayerLog::WAP_OS => RegisterPlayerLog::transferOsDefinition(RegisterPlayerLog::WAP_OS),
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
        $grid->column('uid', __('Uid'));
        $grid->column('os', __('Os'))->display(function ($os) {
            return RegisterPlayerLog::transferOsDefinition((int) $os);
        })->label([
            RegisterPlayerLog::PC_OS => 'warning',
            RegisterPlayerLog::IOS_OS => 'info',
            RegisterPlayerLog::ANDROID_OS => 'primary',
            RegisterPlayerLog::WAP_OS => 'success'
        ]);
        $grid->column('ip', __('Ip'));
        $grid->column('device_uuid', __('Device uuid'));
        $grid->column('device_brand', __('Device brand'));
        $grid->column('device_os_version', __('Device os version'));
        $grid->column('network', __('Network'))->display(function ($network) {
            return RegisterPlayerLog::transferNetworkDefinition(is_null($network)? -1: (int) $network);
        })->label([
            RegisterPlayerLog::UNKNOWN_NET_WORK => 'default',
            RegisterPlayerLog::TWO_G_NET_WORK => 'info',
            RegisterPlayerLog::THREE_G_NET_WORK => 'info',
            RegisterPlayerLog::FOUR_G_NET_WORK => 'primary',
            RegisterPlayerLog::FIVE_G_NET_WORK => 'success',
            RegisterPlayerLog::WIFI_NET_WORK => 'warning',
        ]);
        $grid->column('app_version', __('App version'));
        $grid->column('sdkVersion.sdk_version', __('Sdk version'));
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
        $show = new Show(RegisterPlayerLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('game_app_id', __('Game app id'));
        $show->field('gameApp.name', __('Game app'));
        $show->field('channel.name', __('Post channel'));
        $show->field('uid', __('Uid'));
        $show->field('os', __('Os'))->as(function ($os) {
            return RegisterPlayerLog::transferOsDefinition((int) $os);
        });
        $show->field('ip', __('Ip'));
        $show->field('device_uuid', __('Device uuid'));
        $show->field('device_brand', __('Device brand'));
        $show->field('device_os_version', __('Device os version'));
        $show->field('network', __('Network'))->as(function ($network) {
            return RegisterPlayerLog::transferNetworkDefinition(is_null($network)? -1: (int) $network);
        });
        $show->field('app_version', __('App version'));
        $show->field('sdkVersion.sdk_version', __('Sdk version'));
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
        $form = new Form(new RegisterPlayerLog());

        $gameAppIdMapNames = [];
        foreach (GameApp::query()->get() as $gameApp) {
            $gameAppIdMapNames[$gameApp->id] = "{$gameApp->id} -- {$gameApp->name}";
        }

        $form->select('game_app_id', __('Game app id'))->options($gameAppIdMapNames);

        foreach (PostChannel::query()->get() as $postChannel) {
            $channelIdMapNames[$postChannel->id] = $postChannel->name;
        }

        $form->select('post_channel_id', __('Post channel id'))
            ->options($channelIdMapNames)
            ->load('sdk_version_id', '/admin/api/post-channel/sdk-version');
        $form->text('uid', __('Uid'));
        $form->select('os', __('Os'))->options([
            RegisterPlayerLog::PC_OS => RegisterPlayerLog::transferOsDefinition(RegisterPlayerLog::PC_OS),
            RegisterPlayerLog::IOS_OS => RegisterPlayerLog::transferOsDefinition(RegisterPlayerLog::IOS_OS),
            RegisterPlayerLog::ANDROID_OS => RegisterPlayerLog::transferOsDefinition(RegisterPlayerLog::ANDROID_OS),
            RegisterPlayerLog::WAP_OS => RegisterPlayerLog::transferOsDefinition(RegisterPlayerLog::WAP_OS)
        ]);
        $form->ip('ip', __('Ip'));
        $form->text('device_uuid', __('Device uuid'));
        $form->text('device_brand', __('Device brand'));
        $form->text('device_os_version', __('Device os version'));
        $form->select('network', __('Network'))->options([
            RegisterPlayerLog::UNKNOWN_NET_WORK => RegisterPlayerLog::transferNetworkDefinition(RegisterPlayerLog::UNKNOWN_NET_WORK),
            RegisterPlayerLog::TWO_G_NET_WORK => RegisterPlayerLog::transferNetworkDefinition(RegisterPlayerLog::TWO_G_NET_WORK),
            RegisterPlayerLog::THREE_G_NET_WORK => RegisterPlayerLog::transferNetworkDefinition(RegisterPlayerLog::THREE_G_NET_WORK),
            RegisterPlayerLog::FOUR_G_NET_WORK => RegisterPlayerLog::transferNetworkDefinition(RegisterPlayerLog::FOUR_G_NET_WORK),
            RegisterPlayerLog::FIVE_G_NET_WORK => RegisterPlayerLog::transferNetworkDefinition(RegisterPlayerLog::FIVE_G_NET_WORK),
            RegisterPlayerLog::WIFI_NET_WORK => RegisterPlayerLog::transferNetworkDefinition(RegisterPlayerLog::WIFI_NET_WORK),
        ]);
        $form->text('app_version', __('App version'));

        $defaultSdkVersionIdMapNames = [];
        if (!is_null($registerPlayerLogId = request()->route('register_player_log'))) {
            $registerPlayerLog = RegisterPlayerLog::findOrFail($registerPlayerLogId);
            foreach (PostChannelSdkVersion::query()->where('post_channel_id', $registerPlayerLog->post_channel_id)->get() as $defaultSdkVersion) {
                $defaultSdkVersionIdMapNames[$defaultSdkVersion->id] = $defaultSdkVersion->sdk_version;
            }
        }

        $form->select('sdk_version_id', __('Sdk version id'))->options($defaultSdkVersionIdMapNames);

        return $form;
    }
}
