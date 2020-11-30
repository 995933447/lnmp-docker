<?php

namespace App\Admin\Controllers;

use App\Models\PostChannel;
use App\Models\PostChannelSdkVersion;
use App\Models\SupportGameType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PostChannelSdkVersionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '渠道的sdk版本';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PostChannelSdkVersion());

        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $postChannelOptions = [];

            foreach (PostChannel::query()->where('status', 1)->get() as $channel) {
                $postChannelOptions[$channel->id] = $channel->name;
            }

            $selector->select('post_channel_id', __('Post channel'), $postChannelOptions);
        });

        $grid->model()->orderBy('created_at', 'DESC');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('sdk_version', __('Sdk version'));
        $grid->column('channel.name', __('Channel name'));
        $grid->column('status', __('Status'))->switch([
            'on' => ['text' => SupportGameType::transferStatusDefinition(SupportGameType::VALID_STATUS), 'value' => SupportGameType::VALID_STATUS],
            'off' => ['text' => SupportGameType::transferStatusDefinition(SupportGameType::INVALID_STATUS), 'value' => SupportGameType::INVALID_STATUS]
        ]);
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
        $show = new Show(PostChannelSdkVersion::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('sdk_version', __('Sdk version'));
        $show->field('channel.name', __('Channel name'));
        $show->field('status', __('Status'))->as(function ($status) {
            return SupportGameType::transferStatusDefinition($status);
        });
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
        $form = new Form(new PostChannelSdkVersion());

        $form->text('sdk_version', __('Sdk version'));

        $channelIdMapNames = [];
        foreach (PostChannel::query()->get() as $postChannel) {
            $channelIdMapNames[$postChannel->id] = $postChannel->name;
        }

        $form->select('post_channel_id', __('Channel name'))->options($channelIdMapNames);
        $form->switch('status', __('Status'));

        // 保存后回调
        $form->saved(function (Form $form) {
            $channel = PostChannel::find($form->post_channel_id);
            if (!$channel->sdk_version_id) {
                if ($form->model()->status) {
                    $channel->sdk_version_id = $form->model()->id;
                    $channel->save();
                }
            }
        });

        return $form;
    }
}
