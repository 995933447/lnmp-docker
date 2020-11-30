<?php

namespace App\Admin\Controllers;

use App\Models\PostChannel;
use App\Models\PostChannelArgument;
use App\Models\PostChannelSdkVersion;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostChannelArgumentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '渠道sdk参数项';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PostChannelArgument());

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $postChannelOptions = [];

            foreach (PostChannel::query()->where('status', 1)->get() as $channel) {
                $postChannelOptions[$channel->id] = $channel->name;
            }

            $selector->select('post_channel_id', __('Post channel'), $postChannelOptions);
        });

        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        $grid->model()->orderBy('created_at', 'DESC');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('channel.name', __('Post channel'));
        $grid->column('sdkVersion.sdk_version', __('Sdk version'));
        $grid->column('argument_key', __('Argument key'));
        $grid->column('status', __('Status'))->display(function ($status) {
            return PostChannelArgument::transferStatusDefinition($status);
        });
        $grid->column('sort', __('Sort'))->sortable();
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('argument_type', __('Argument type'))->display(function ($argumentType) {
            return PostChannelArgument::transferArgumentTypeDefinition($argumentType);
        });
        $grid->column('placehodler', __('Placehodler'));

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
        $show = new Show(PostChannelArgument::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('channel.name', __('Post channel'));
        $show->field('sdkVersion.sdk_version', __('Sdk version'));
        $show->field('argument_key', __('Argument key'));
        $show->field('status', __('Status'))->as(function ($status) {
            return PostChannelArgument::transferStatusDefinition($status);
        });;
        $show->field('sort', __('Sort'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('argument_type', __('Argument type'))->as(function ($argumentType) {
            return PostChannelArgument::transferArgumentTypeDefinition($argumentType);
        });
        $show->field('placehodler', __('Placehodler'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PostChannelArgument());

        $form->display('post_channel_id', __('Post channel'))->with(function ($postChannelId) {
            return PostChannel::query()->where('id', $postChannelId)->value('name');
        });
        $form->display('post_channel_sdk_version_id', __('Post channel sdk version id'))->with(function ($postChanelSkdVersionId) {
            return PostChannelSdkVersion::query()->where('id', $postChanelSkdVersionId)->value('sdk_version');
        });
        $form->text('argument_key', __('Argument key'));
        $form->switch('status', __('Status'));
        $form->number('sort', __('Sort'));
        $form->select('argument_type', __('Argument type'))->options([
            PostChannelArgument::TEXT_ARGUMENT_TYPE => PostChannelArgument::transferArgumentTypeDefinition(PostChannelArgument::TEXT_ARGUMENT_TYPE)
        ]);
        $form->text('placehodler', __('Placehodler'))->default('');

        return $form;
    }

    public function setting(Request $request, $postChannelId)
    {
        if ($request->isMethod('POST')) {
            ($connetion = PostChannelArgument::query()->getConnection())->beginTransaction();
            try {
                foreach ($request->input('keys') as $index => $key) {
                    if (empty($key)) {
                        continue;
                    }

                    $postChannelArgument = PostChannelArgument::query()->where('argument_key', $key)
                        ->where('post_channel_sdk_version_id', $request->input('sdk_version_id'))
                        ->where('post_channel_id', $postChannelId)
                        ->first();

                    if (!is_null($postChannelArgument)) {
                        return back()->withInput()->with('error', "渠道参数项：$key 已存在");
                    } else {
                        $postChannelArgument = new PostChannelArgument();
                        $postChannelArgument->argument_key = $key;
                        $postChannelArgument->post_channel_id = $postChannelId;
                        $postChannelArgument->post_channel_sdk_version_id = $request->input('sdk_version_id');
                        $postChannelArgument->sort = (int)$request->input('sorts')[$index];
                        $postChannelArgument->argument_type = $request->input('types')[$index];
                        $postChannelArgument->placehodler = $request->input('placeholders')[$index]?: '';
                        $postChannelArgument->status = $request->input('status')[$index];
                        $postChannelArgument->save();
                    }

                    $connetion->commit();
                }

                return back()->with('success', __('Processed success.'));
            } catch (\Throwable $e) {
                $connetion->rollBack();
                throw $e;
            }
        }
        $postChannel = PostChannel::query()->findOrFail($postChannelId);
        $channelSdkVersions = PostChannelSdkVersion::query()
            ->where('post_channel_id', $postChannelId)
            ->where('status', PostChannelSdkVersion::VALID_STATUS)
            ->get();

        $assignViewData = [
            'channel' => $postChannel,
            'channel_sdk_versions' => $channelSdkVersions
        ];
        return view('admin.pages.post-channel-arguments.setting', $assignViewData);
    }
}
