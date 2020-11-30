<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\PostChannel\ToAddPostChannelArgumentItemJumper;
use App\Models\PostChannel;
use App\Models\PostChannelSdkVersion;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class PostChannelController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '可发布渠道';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PostChannel());

        $grid->filter(function ($filter) {
            $filter->startsWith('name', __('Name'));
        });

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('post_platform', __('Post platform'), [
                PostChannel::ANDROID_POST_PLATFORM => PostChannel::transferPostPlatformDefinition(PostChannel::ANDROID_POST_PLATFORM),
                PostChannel::IOS_POST_PLATFORM => PostChannel::transferPostPlatformDefinition(PostChannel::IOS_POST_PLATFORM),
                PostChannel::FOREIGN_POST_PLATFORM => PostChannel::transferPostPlatformDefinition(PostChannel::FOREIGN_POST_PLATFORM)
            ]);

            $selector->select('status', __('Status'), [
                PostChannel::VALID_STATUS => PostChannel::transferStatusDefinition(PostChannel::VALID_STATUS),
                PostChannel::INVALID_STATUS => PostChannel::transferStatusDefinition(PostChannel::INVALID_STATUS)
            ]);
        });

        $grid->model()->with('argumentKeys');

        $grid->actions(function ($actions) {
            $actions->add(new ToAddPostChannelArgumentItemJumper());
            $actions->disableDelete();
        });

        $grid->model()->orderBy('created_at', 'DESC');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'));

        $grid->column('', __('Argument item'))->display(function () {
            return __('Click expand');
        })->expand(function ($model) {
            $argumentKeysData = [];

            foreach ($model->argumentKeys as $argumentKey) {
                $argumentKeyData['id'] = $argumentKey->id;
                $argumentKeyData['argument_name'] = $argumentKey->argument_key;
                $argumentKeyData['version'] = $argumentKey->sdkVersion->sdk_version;
                $argumentKeyData['created_at'] = $argumentKey->created_at;
                $argumentKeysData[$argumentKeyData['version']] = array_merge([$argumentKeyData], $argumentKeysData[$argumentKeyData['version']]?? []);
            }

            $data = [];
            foreach ($argumentKeysData as $argumentKeyData) {
                $data = array_merge($data, $argumentKeyData);
            }

            return new Table([__('Argument id'), __('Argument item'), __('Sdk version'), __('Created at')], $data);
        });

        $grid->column('status', __('Status'))->switch([
            'on' => ['text' => PostChannel::transferStatusDefinition(PostChannel::VALID_STATUS), 'value' => PostChannel::VALID_STATUS],
            'off' => ['text' => PostChannel::transferStatusDefinition(PostChannel::INVALID_STATUS), 'value' => PostChannel::INVALID_STATUS]
        ]);

        $grid->column('post_platform', __('Post platform'))->display(function (int $postPlatform) {
            return PostChannel::transferPostPlatformDefinition($postPlatform);
        });

        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('sdkVersion.sdk_version', __('Sdk version'));

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
        $show = new Show(PostChannel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('status', __('Status'))->as(function ($status) {
            return PostChannel::transferStatusDefinition($status);
        });
        $show->field('post_platform', __('Post platform'))->as(function ($postPlatform) {
            return PostChannel::transferPostPlatformDefinition($postPlatform);
        });
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('sdkVersion.sdk_version', __('Sdk version'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PostChannel());

        $form->text('name', __('Name'));
        $form->switch('status', __('Status'))->default(1);
        $form->select('post_platform', __('Post platform'))->options([
            PostChannel::ANDROID_POST_PLATFORM => PostChannel::transferPostPlatformDefinition(PostChannel::ANDROID_POST_PLATFORM),
            PostChannel::IOS_POST_PLATFORM => PostChannel::transferPostPlatformDefinition(PostChannel::IOS_POST_PLATFORM),
            PostChannel::H5_POST_PLATFORM => PostChannel::transferPostPlatformDefinition(PostChannel::H5_POST_PLATFORM),
            PostChannel::FOREIGN_POST_PLATFORM => PostChannel::transferPostPlatformDefinition(PostChannel::FOREIGN_POST_PLATFORM)
        ]);

        $sdkVersionIdMapNames = [];
        foreach (
            PostChannelSdkVersion::query()->where('post_channel_id', request()->route('post_channel'))
                ->where('status', PostChannelSdkVersion::VALID_STATUS)
                ->get()
            as
            $sdkVersion
        ) {
            $sdkVersionIdMapNames[$sdkVersion->id] = $sdkVersion->sdk_version;
        }

        $form->select('sdk_version_id', __('Sdk version'))->options($sdkVersionIdMapNames);

        return $form;
    }
}
