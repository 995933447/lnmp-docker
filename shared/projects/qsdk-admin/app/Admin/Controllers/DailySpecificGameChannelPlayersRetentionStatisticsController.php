<?php

namespace App\Admin\Controllers;

use App\Models\PostChannel;
use App\Models\Statistics\DailySpecificGameChannelPlayersRetentionStatistics;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DailySpecificGameChannelPlayersRetentionStatisticsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '游戏具体渠道玩家留存每日趋势';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DailySpecificGameChannelPlayersRetentionStatistics());

        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();

            // 去掉查看
            $actions->disableView();
        });

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $postChannelOptions = [];

            foreach (PostChannel::query()->where('status', 1)->get() as $channel) {
                $postChannelOptions[$channel->id] = $channel->name;
            }

            $selector->select('post_channel_id', __('Post channel'), $postChannelOptions);
        });

        $grid->filter(function ($filter) {
            $filter->where(function ($query) {
                $query->whereHas('gameApp', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });
            }, __('Game app'));


            $filter->date('created_at', __('Created at'))->datetime();
            $filter->between('updated_at', __('Updated at'))->datetime();
            $filter->between('belong_date', __('Belong date'))->datetime();
        });

        $grid->model()->orderBy('created_at', 'DESC');

        $grid->column('id', __('Id'))->sortable();
        $grid->column('game.name', __('Game app'));
        $grid->column('channel.name', __('Post channel'));
        $grid->column('belong_date', __('Belong date'));
        $grid->column('aggregated_at', __('Aggregated at'))->display(function ($value) {
            if (!$value) return '';
            return date('Y-m-d H:i:s', (int) $value);
        });
        $grid->column('remain_num_2nd_day', __('Remain num 2nd day'));
        $grid->column('remain_rate_2nd_day', __('Remain rate 2nd day'));
        $grid->column('remain_num_3rd_day', __('Remain num 3rd day'));
        $grid->column('remain_rate_3rd_day', __('Remain rate 3rd day'));
        $grid->column('remain_num_4th_day', __('Remain num 4th day'));
        $grid->column('remain_rate_4th_day', __('Remain rate 4th day'));
        $grid->column('remain_num_5th_day', __('Remain num 5th day'));
        $grid->column('remain_rate_5th_day', __('Remain rate 5th day'));
        $grid->column('remain_num_6th_day', __('Remain num 6th day'));
        $grid->column('remain_rate_6th_day', __('Remain rate 6th day'));
        $grid->column('remain_num_7th_day', __('Remain num 7th day'));
        $grid->column('remain_rate_7th_day', __('Remain rate 7th day'));
        $grid->column('remain_num_15th_day', __('Remain num 15th day'));
        $grid->column('remain_rate_15th_day', __('Remain rate 15th day'));
        $grid->column('remain_num_30th_day', __('Remain num 30th day'));
        $grid->column('remain_rate_30th_day', __('Remain rate 30th day'));
        $grid->column('remain_num_60th_day', __('Remain num 60th day'));
        $grid->column('remain_rate_60th_day', __('Remain rate 60th day'));
        $grid->column('remain_num_90th_day', __('Remain num 90th day'));
        $grid->column('remain_rate_90th_day', __('Remain rate 90th day'));
        $grid->column('remain_num_120th_day', __('Remain num 120th day'));
        $grid->column('remain_rate_120th_day', __('Remain rate 120th day'));
        $grid->column('remain_num_180th_day', __('Remain num 180th day'));
        $grid->column('remain_rate_180th_day', __('Remain rate 180th day'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('batch_aggregated_at', __('Batch aggregated at'))->display(function ($value) {
            if (!$value) return '';
            return date('Y-m-d H:i:s', (int) $value);
        });

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
        $show = new Show(DailySpecificGameChannelPlayersRetentionStatistics::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('game_app_id', __('Game app id'));
        $show->field('post_channel_id', __('Post channel id'));
        $show->field('belong_date', __('Belong date'));
        $show->field('aggregated_at', __('Aggregated at'));
        $show->field('remain_num_2nd_day', __('Remain num 2nd day'));
        $show->field('remain_rate_2nd_day', __('Remain rate 2nd day'));
        $show->field('remain_num_3rd_day', __('Remain num 3rd day'));
        $show->field('remain_rate_3rd_day', __('Remain rate 3rd day'));
        $show->field('remain_num_4th_day', __('Remain num 4th day'));
        $show->field('remain_rate_4th_day', __('Remain rate 4th day'));
        $show->field('remain_num_5th_day', __('Remain num 5th day'));
        $show->field('remain_rate_5th_day', __('Remain rate 5th day'));
        $show->field('remain_num_6th_day', __('Remain num 6th day'));
        $show->field('remain_rate_6th_day', __('Remain rate 6th day'));
        $show->field('remain_num_7th_day', __('Remain num 7th day'));
        $show->field('remain_rate_7th_day', __('Remain rate 7th day'));
        $show->field('remain_num_15th_day', __('Remain num 15th day'));
        $show->field('remain_rate_15th_day', __('Remain rate 15th day'));
        $show->field('remain_num_30th_day', __('Remain num 30th day'));
        $show->field('remain_rate_30th_day', __('Remain rate 30th day'));
        $show->field('remain_num_60th_day', __('Remain num 60th day'));
        $show->field('remain_rate_60th_day', __('Remain rate 60th day'));
        $show->field('remain_num_90th_day', __('Remain num 90th day'));
        $show->field('remain_rate_90th_day', __('Remain rate 90th day'));
        $show->field('remain_num_120th_day', __('Remain num 120th day'));
        $show->field('remain_rate_120th_day', __('Remain rate 120th day'));
        $show->field('remain_num_180th_day', __('Remain num 180th day'));
        $show->field('remain_rate_180th_day', __('Remain rate 180th day'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('batch_aggregated_at', __('Batch aggregated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DailySpecificGameChannelPlayersRetentionStatistics());

        $form->number('game_app_id', __('Game app id'));
        $form->number('post_channel_id', __('Post channel id'));
        $form->date('belong_date', __('Belong date'))->default(date('Y-m-d'));
        $form->number('aggregated_at', __('Aggregated at'));
        $form->number('remain_num_2nd_day', __('Remain num 2nd day'));
        $form->decimal('remain_rate_2nd_day', __('Remain rate 2nd day'));
        $form->number('remain_num_3rd_day', __('Remain num 3rd day'));
        $form->decimal('remain_rate_3rd_day', __('Remain rate 3rd day'));
        $form->number('remain_num_4th_day', __('Remain num 4th day'));
        $form->decimal('remain_rate_4th_day', __('Remain rate 4th day'));
        $form->number('remain_num_5th_day', __('Remain num 5th day'));
        $form->decimal('remain_rate_5th_day', __('Remain rate 5th day'));
        $form->number('remain_num_6th_day', __('Remain num 6th day'));
        $form->decimal('remain_rate_6th_day', __('Remain rate 6th day'));
        $form->number('remain_num_7th_day', __('Remain num 7th day'));
        $form->decimal('remain_rate_7th_day', __('Remain rate 7th day'));
        $form->number('remain_num_15th_day', __('Remain num 15th day'));
        $form->decimal('remain_rate_15th_day', __('Remain rate 15th day'));
        $form->number('remain_num_30th_day', __('Remain num 30th day'));
        $form->decimal('remain_rate_30th_day', __('Remain rate 30th day'));
        $form->number('remain_num_60th_day', __('Remain num 60th day'));
        $form->decimal('remain_rate_60th_day', __('Remain rate 60th day'));
        $form->number('remain_num_90th_day', __('Remain num 90th day'));
        $form->decimal('remain_rate_90th_day', __('Remain rate 90th day'));
        $form->number('remain_num_120th_day', __('Remain num 120th day'));
        $form->decimal('remain_rate_120th_day', __('Remain rate 120th day'));
        $form->number('remain_num_180th_day', __('Remain num 180th day'));
        $form->decimal('remain_rate_180th_day', __('Remain rate 180th day'));
        $form->number('batch_aggregated_at', __('Batch aggregated at'));

        return $form;
    }
}
