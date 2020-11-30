<?php

namespace App\Admin\Controllers;

use App\Models\GameApp;
use App\Models\PostChannel;
use App\Models\PostChannelSdkVersion;
use App\Models\PlayerLoginLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PlayerLoginLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '玩家登录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PlayerLoginLog());

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
                PlayerLoginLog::PC_OS => PlayerLoginLog::transferOsDefinition(PlayerLoginLog::PC_OS),
                PlayerLoginLog::IOS_OS => PlayerLoginLog::transferOsDefinition(PlayerLoginLog::IOS_OS),
                PlayerLoginLog::ANDROID_OS => PlayerLoginLog::transferOsDefinition(PlayerLoginLog::ANDROID_OS),
                PlayerLoginLog::WAP_OS => PlayerLoginLog::transferOsDefinition(PlayerLoginLog::WAP_OS),
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
            return PlayerLoginLog::transferOsDefinition((int) $os);
        })->label([
            PlayerLoginLog::PC_OS => 'warning',
            PlayerLoginLog::IOS_OS => 'info',
            PlayerLoginLog::ANDROID_OS => 'primary',
            PlayerLoginLog::WAP_OS => 'success'
        ]);
        $grid->column('ip', __('Ip'));
        $grid->column('device_uuid', __('Device uuid'));
        $grid->column('device_brand', __('Device brand'));
        $grid->column('device_os_version', __('Device os version'));
        $grid->column('network', __('Network'))->display(function ($network) {
            return PlayerLoginLog::transferNetworkDefinition(is_null($network)? -1: (int) $network);
        })->label([
            PlayerLoginLog::UNKNOWN_NET_WORK => 'default',
            PlayerLoginLog::TWO_G_NET_WORK => 'info',
            PlayerLoginLog::THREE_G_NET_WORK => 'info',
            PlayerLoginLog::FOUR_G_NET_WORK => 'primary',
            PlayerLoginLog::FIVE_G_NET_WORK => 'success',
            PlayerLoginLog::WIFI_NET_WORK => 'warning',
        ]);
        $grid->column('app_version', __('App version'));
        $grid->column('sdk_version_id', __('Sdk version id'));
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
        $show = new Show(PlayerLoginLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('game_app_id', __('Game app id'));
        $show->field('gameApp.name', __('Game app'));
        $show->field('channel.name', __('Post channel'));
        $show->field('uid', __('Uid'));
        $show->field('os', __('Os'))->as(function ($os) {
            return PlayerLoginLog::transferOsDefinition((int) $os);
        });
        $show->field('ip', __('Ip'));
        $show->field('device_uuid', __('Device uuid'));
        $show->field('device_brand', __('Device brand'));
        $show->field('device_os_version', __('Device os version'));
        $show->field('network', __('Network'))->as(function ($network) {
            return PlayerLoginLog::transferNetworkDefinition(is_null($network)? -1: (int) $network);
        });
        $show->field('app_version', __('App version'));
        $show->field('sdk_version_id', __('Sdk version id'));
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
        $form = new Form(new PlayerLoginLog());

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
            PlayerLoginLog::PC_OS => PlayerLoginLog::transferOsDefinition(PlayerLoginLog::PC_OS),
            PlayerLoginLog::IOS_OS => PlayerLoginLog::transferOsDefinition(PlayerLoginLog::IOS_OS),
            PlayerLoginLog::ANDROID_OS => PlayerLoginLog::transferOsDefinition(PlayerLoginLog::ANDROID_OS),
            PlayerLoginLog::WAP_OS => PlayerLoginLog::transferOsDefinition(PlayerLoginLog::WAP_OS)
        ]);
        $form->ip('ip', __('Ip'));
        $form->text('device_uuid', __('Device uuid'));
        $form->text('device_brand', __('Device brand'));
        $form->text('device_os_version', __('Device os version'));
        $form->select('network', __('Network'))->options([
            PlayerLoginLog::UNKNOWN_NET_WORK => PlayerLoginLog::transferNetworkDefinition(PlayerLoginLog::UNKNOWN_NET_WORK),
            PlayerLoginLog::TWO_G_NET_WORK => PlayerLoginLog::transferNetworkDefinition(PlayerLoginLog::TWO_G_NET_WORK),
            PlayerLoginLog::THREE_G_NET_WORK => PlayerLoginLog::transferNetworkDefinition(PlayerLoginLog::THREE_G_NET_WORK),
            PlayerLoginLog::FOUR_G_NET_WORK => PlayerLoginLog::transferNetworkDefinition(PlayerLoginLog::FOUR_G_NET_WORK),
            PlayerLoginLog::FIVE_G_NET_WORK => PlayerLoginLog::transferNetworkDefinition(PlayerLoginLog::FIVE_G_NET_WORK),
            PlayerLoginLog::WIFI_NET_WORK => PlayerLoginLog::transferNetworkDefinition(PlayerLoginLog::WIFI_NET_WORK),
        ]);
        $form->text('app_version', __('App version'));

        $defaultSdkVersionIdMapNames = [];
        if (!is_null($PlayerLoginLogId = request()->route('player_login_log'))) {
            $PlayerLoginLog = PlayerLoginLog::findOrFail($PlayerLoginLogId);
            foreach (PostChannelSdkVersion::query()->where('post_channel_id', $PlayerLoginLog->post_channel_id)->get() as $defaultSdkVersion) {
                $defaultSdkVersionIdMapNames[$defaultSdkVersion->id] = $defaultSdkVersion->sdk_version;
            }
        }

        $form->select('sdk_version_id', __('Sdk version id'))->options($defaultSdkVersionIdMapNames);

        return $form;
    }
}
