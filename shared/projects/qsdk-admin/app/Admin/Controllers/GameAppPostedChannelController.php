<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\GameAppPostedChannel\ToEditPostedChannelArugumentJumper;
use App\Admin\Actions\GameAppPostedChannel\ToEditPostedChannelConfigJumper;
use App\Admin\Requests\CreateGameAppPostedChannelRequest;
use App\Models\GameApp;
use App\Models\GameAppPostedChannel;
use App\Models\GameAppPostedChannelArgument;
use App\Models\PostChannel;
use App\Models\PostChannelSdkVersion;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class GameAppPostedChannelController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '游戏已发布渠道';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GameAppPostedChannel());

        $grid->model()->with('argumentKeys');

        $grid->filter(function ($filter) {
            $filter->equal('game_app_id', __('Game app id'));
        });

        $grid->actions(function ($actions) {
            $actions->add(new ToEditPostedChannelConfigJumper());
            $actions->add(new ToEditPostedChannelArugumentJumper());
            $actions->disableDelete();
        });

        $grid->model()->orderBy('created_at', 'DESC');

        $grid->column('id', __('Id'))->sortable();
        $grid->column('gameApp.name', __('Game app'));
        $grid->column('channel.name', __('Channel name'));

        $grid->column('', __('Sdk arguments'))->display(function () {
            return __('Click expand');
        })->expand(function ($model) {
            $argumentKeys = $model->argumentKeys;
            $argumentValues = GameAppPostedChannelArgument::query()
                ->where('game_app_id', $model->game_app_id)
                ->where('post_channel_id', $model->post_channel_id)
                ->get();

            $argumentsData = [];
            foreach ($argumentKeys as $argumentKey) {
                $argumentData = [
                    'key' => $argumentKey->argument_key,
                    'value' => null,
                    'version' => $argumentKey->sdkVersion->sdk_version,
                ];

                foreach ($argumentValues as $argumentValue) {
                    if ($argumentValue->post_channel_argument_id == $argumentKey->id) {
                        $argumentData['value'] = $argumentValue->argument_value;
                    }
                }

                $argumentsData[$argumentData['version']] = array_merge([$argumentData], $argumentsData[$argumentData['version']]?? []);
            }

            $data = [];
            foreach ($argumentsData as $version => $argumentData) {
                $data = array_merge($data, $argumentData);
            }

            return new Table([__('Argument item'), __('Argument value'), __('Sdk version')], $data);
        });

        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('channelSdkVersion.sdk_version', __('Sdk version'));
        $grid->column('createdAtChannelSdkVersion.sdk_version', __('Created at sdk version'));

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
        $show = new Show(GameAppPostedChannel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('gameApp.name', __('Game app'));
        $show->field('channel.name', __('Channel name'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('channelSdkVersion.sdk_version', __('Sdk version'));
        $show->field('createdAtChannelSdkVersion.sdk_version', __('Created at sdk version'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new GameAppPostedChannel());

        $form->tools(function (Form\Tools $tools) {
            $url = back()->getTargetUrl();
            $tools->append("<a class='btn btn-sm btn-primary mallto-next' href='{$url}' target=''>返回</a> &nbsp;");
        });

        $users = User::all();
        foreach ($users as $user) {
            $userIdMapNames[$user->id] = $user->name;
        }

        $defaultSelectedUserId = empty($userIdMapNames)? 0: key($userIdMapNames);

        $isEditScene = true;
        if (!is_null($gameAppPostedChannelId = request()->route('game_app_posted_channel'))) {
            $gameAppPostedChannel = GameAppPostedChannel::with(['gameApp.user', 'channel'])->find($gameAppPostedChannelId);
            $defaultSelectedUserId = $gameAppPostedChannel->gameApp->user->id;
            $defaultSelectedGameAppId = $gameAppPostedChannel->gameApp->id;
            $defaultSelectedChannelId = $gameAppPostedChannel->channel->id;
        } else {
            $isEditScene = false;
        }

        $defaultGameApps = GameApp::query()->where('user_id', $defaultSelectedUserId)->get();
        $defaultGameAppIdMapNames = [];
        foreach ($defaultGameApps as $gameApp) {
            $defaultGameAppIdMapNames[$gameApp->id] = "{$gameApp->id} -- {$gameApp->name}";
        }

        if (!$isEditScene) {
            $defaultSelectedGameAppId = empty($defaultGameAppIdMapNames)? 0: key($defaultGameAppIdMapNames);
        }

        $channels = PostChannel::query()->where('status', PostChannel::VALID_STATUS)->get();
        $channelIdMapNames = [];
        foreach ($channels as $channel) {
            $channelIdMapNames[$channel->id] = $channel->name;
        }

        if (!$isEditScene) {
            $defaultSelectedChannelId = empty($channelIdMapNames)? 0: key($channelIdMapNames);
        }

        $sdkVersions = PostChannelSdkVersion::query()->where('post_channel_id', $defaultSelectedChannelId)->get();
        $sdkVersionIdMapNames = [];
        foreach ($sdkVersions as $sdkVersion) {
            $sdkVersionIdMapNames[$sdkVersion->id] = $sdkVersion->sdk_version;
        }

        $form->select('user_id', __('User name'))
            ->options($userIdMapNames)
            ->default($defaultSelectedUserId)
            ->load('game_app_id', '/admin/api/game_apps');

        $form->select('game_app_id', __('Game app'))->options($defaultGameAppIdMapNames)->default($defaultSelectedGameAppId);

        $form->select('post_channel_id', __('Post channel'))
            ->options($channelIdMapNames)
            ->default($defaultSelectedChannelId);

        if ($isEditScene) {
            $form->select('sdk_version_id', __('Sdk version'))->options($sdkVersionIdMapNames);

            $form->display('created_at_sdk_version_id', __('Created at sdk version id'))->with(function ($value) use ($sdkVersionIdMapNames) {
                return $sdkVersionIdMapNames[$value];
            });
        } else {
            $form->display('created_at_sdk_version_id', __('Created at sdk version id'));
        }

        $form->saving(function (Form $form) use ($gameAppPostedChannelId) {
            if (is_null($gameAppPostedChannelId)) {
                $form->model()->created_at_sdk_version_id = $form->model()->sdk_version_id = PostChannel::query()
                    ->where('id', $form->post_channel_id)
                    ->value('sdk_version_id');
            }
        });

        $form->ignore(['user_id']);

        return $form;
    }

    public function store()
    {
        app(CreateGameAppPostedChannelRequest::class);
        return $this->form()->store();
    }
}
