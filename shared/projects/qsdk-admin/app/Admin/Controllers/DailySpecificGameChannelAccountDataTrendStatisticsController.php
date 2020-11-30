<?php

namespace App\Admin\Controllers;

use App\Models\PostChannel;
use App\Models\Statistics\DailySpecificGameChannelAccountDataTrendStatistics;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DailySpecificGameChannelAccountDataTrendStatisticsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '游戏具体渠道玩家账户数据每日趋势';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DailySpecificGameChannelAccountDataTrendStatistics());

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

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $postChannelOptions = [];

            foreach (PostChannel::query()->where('status', 1)->get() as $channel) {
                $postChannelOptions[$channel->id] = $channel->name;
            }

            $selector->select('post_channel_id', __('Post channel'), $postChannelOptions);
        });

        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();

            // 去掉查看
            $actions->disableView();
        });

        $grid->model()->orderBy('created_at', 'DESC');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('game.name', __('Game app'));
        $grid->column('channel.name', __('Post channel'));
        $grid->column('players_increments', __('Players increments'));
        $grid->column('roles_increments', __('Roles increments'));
        $grid->column('create_role_conversion_rate', __('Create role conversion rate'))->display(function ($value) {
            return ($value * 100) . '%';
        });
        $grid->column('active_players_num', __('Active players num'));
        $grid->column('logon_roles_num', __('Logon roles num'));
        $grid->column('aggregated_at', __('Aggregated at'))->display(function ($value) {
            if (!$value) return '';
            return date('Y-m-d H:i:s', (int) $value);
        });
        $grid->column('belong_date', __('Belong date'));
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
        $show = new Show(DailySpecificGameChannelAccountDataTrendStatistics::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('game_app_id', __('Game app id'));
        $show->field('post_channel_id', __('Post channel id'));
        $show->field('players_increments', __('Players increments'));
        $show->field('roles_increments', __('Roles increments'));
        $show->field('create_role_conversion_rate', __('Create role conversion rate'));
        $show->field('active_players_num', __('Active players num'));
        $show->field('logon_roles_num', __('Logon roles num'));
        $show->field('aggregated_at', __('Aggregated at'));
        $show->field('belong_date', __('Belong date'));
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
        $form = new Form(new DailySpecificGameChannelAccountDataTrendStatistics());

        $form->number('game_app_id', __('Game app id'));
        $form->number('post_channel_id', __('Post channel id'));
        $form->number('players_increments', __('Players increments'));
        $form->number('roles_increments', __('Roles increments'));
        $form->decimal('create_role_conversion_rate', __('Create role conversion rate'));
        $form->number('active_players_num', __('Active players num'));
        $form->number('logon_roles_num', __('Logon roles num'));
        $form->number('aggregated_at', __('Aggregated at'));
        $form->date('belong_date', __('Belong date'))->default(date('Y-m-d'));
        $form->number('batch_aggregated_at', __('Batch aggregated at'));

        return $form;
    }
}
