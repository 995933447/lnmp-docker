<?php

namespace App\Admin\Controllers;

use App\Models\GameAppPostedChannelArgument;
use App\Models\PostChannel;
use App\Models\PostChannelSdkVersion;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use App\Admin\Forms\PostedChannelArgumentSetting;

class GameAppPostedChannelArgumentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '关系已发布渠道参数';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GameAppPostedChannelArgument());

        $grid->disableCreateButton();

        $grid->filter(function ($filter) {
            $filter->where(function ($query) {
                $query->whereHas('gameApp', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });
            }, __('Game app'));
        });

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $postChannelOptions = [];

            foreach (PostChannel::query()->where('status', 1)->get() as $channel) {
                $postChannelOptions[$channel->id] = $channel->name;
            }

            $selector->select('post_channel_id', __('Post channel'), $postChannelOptions);
        });

        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();

            // 去掉查看
            $actions->disableView();
        });

        $grid->model()->orderBy('created_at', 'DESC');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('gameApp.name', __('Game app'));
        $grid->column('channel.name', __('Post channel'));
        $grid->column('argumentKey.argument_key', __('Argument key'));
        $grid->column('argument_value', __('Argument value'));
        $grid->column('argumentKey.post_channel_sdk_version_id', __('Sdk version'))->display(function ($postChannelSdkVersionId) {
            return PostChannelSdkVersion::query()->where('id', $postChannelSdkVersionId)->value('sdk_version');
        });
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
        $show = new Show(GameAppPostedChannelArgument::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('game_app_id', __('Game app id'));
        $show->field('post_channel_id', __('Post channel id'));
        $show->field('argument_value', __('Argument value'));
        $show->field('post_channel_argument_id', __('Post channel argument id'));
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
        $form = new Form(new GameAppPostedChannelArgument());

        $form->textarea('argument_value', __('Argument value'));
//        $form->number('game_app_id', __('Game app id'));
//        $form->number('post_channel_id', __('Post channel id'));
//        $form->number('post_channel_argument_id', __('Post channel argument id'));

        return $form;
    }

    public function setting(Content $content, Request $request)
    {
        return $content
            ->title('游戏渠道参数配置')
            ->body(new PostedChannelArgumentSetting());
    }
}
