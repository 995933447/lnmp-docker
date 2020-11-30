<?php

namespace App\Admin\Controllers;

use App\Models\PostChannel;
use App\Models\Statistics\DailySpecificGameChannelChargedTrendStatistics;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DailySpecificGameChannelChargedTrendStatisticsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '游戏具体渠道玩家充值数据每日趋势';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DailySpecificGameChannelChargedTrendStatistics());

        $grid->filter(function ($filter) {
            $filter->where(function ($query) {
                $query->whereHas('game', function ($query) {
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
        $grid->column('id', __('Id'));
        $grid->column('channel.name', __('Post channel'));
        $grid->column('game.name', __('Game app'));
        $grid->column('charged_players_num', __('Charged players num'));
        $grid->column('total_charged_amount', __('Total charged amount'));
        $grid->column('new_players_charged_orders_num', __('New players charged orders num'));
        $grid->column('new_players_charged_rate', __('New players charged rate'))->display(function ($value) {
            return ($value * 100) . "%";
        });
        $grid->column('active_player_charged_rate', __('Active player charged rate'))->display(function ($value) {
            return ($value * 100) . "%";
        });
        $grid->column('new_players_charged_amount', __('New players charged amount'));
        $grid->column('commit_order_players_num', __('Commit order players num'));
        $grid->column('active_players_charged_num', __('Active players charged num'));
        $grid->column('new_players_charged_num', __('New players charged num'));
        $grid->column('aggregated_at', __('Aggregated at'))->display(function ($value) {
            if (!$value) return '';
            return date('Y-m-d H:i:s', $value);
        });
        $grid->column('belong_date', __('Belong date'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('batch_aggregated_at', __('Batch aggregated at'))->display(function ($value) {
            if (!$value) return '';
            return date('Y-m-d H:i:s', $value);
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
        $show = new Show(DailySpecificGameChannelChargedTrendStatistics::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('post_channel_id', __('Post channel id'));
        $show->field('game_app_id', __('Game app id'));
        $show->field('charged_players_num', __('Charged players num'));
        $show->field('total_charged_amount', __('Total charged amount'));
        $show->field('new_players_charged_orders_num', __('New players charged orders num'));
        $show->field('new_players_charged_rate', __('New players charged rate'));
        $show->field('active_player_charged_rate', __('Active player charged rate'));
        $show->field('new_players_charged_amount', __('New players charged amount'));
        $show->field('commit_order_players_num', __('Commit order players num'));
        $show->field('active_players_charged_num', __('Active players charged num'));
        $show->field('aggregated_at', __('Aggregated at'));
        $show->field('belong_date', __('Belong date'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('new_players_charged_num', __('New players charged num'));
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
        $form = new Form(new DailySpecificGameChannelChargedTrendStatistics());

        $form->number('post_channel_id', __('Post channel id'));
        $form->number('game_app_id', __('Game app id'));
        $form->number('charged_players_num', __('Charged players num'));
        $form->text('total_charged_amount', __('Total charged amount'));
        $form->number('new_players_charged_orders_num', __('New players charged orders num'));
        $form->decimal('new_players_charged_rate', __('New players charged rate'));
        $form->decimal('active_player_charged_rate', __('Active player charged rate'));
        $form->decimal('new_players_charged_amount', __('New players charged amount'));
        $form->number('commit_order_players_num', __('Commit order players num'));
        $form->number('active_players_charged_num', __('Active players charged num'));
        $form->number('aggregated_at', __('Aggregated at'));
        $form->date('belong_date', __('Belong date'))->default(date('Y-m-d'));
        $form->number('new_players_charged_num', __('New players charged num'));
        $form->number('batch_aggregated_at', __('Batch aggregated at'));

        return $form;
    }
}
