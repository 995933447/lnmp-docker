<?php

namespace App\Admin\Controllers;

use App\Models\PostChannel;
use App\Models\Statistics\LTV\DailySpecificGameChannelLtvStatistics;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class DailySpecificGameChannelLtvStatisticsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '游戏具体渠道LTV每日趋势';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DailySpecificGameChannelLtvStatistics());


        $grid->filter(function ($filter) {
            $filter->where(function ($query) {
                $query->whereHas('gameApp', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });
            }, __('Game app'));


            $filter->date('created_at', __('Created at'))->datetime();
            $filter->between('updated_at', __('Updated at'))->datetime();
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
        $grid->column('', __('Ltv'))->modal('LTV', function ($model) {
            $ltvs = json_decode($model->ltvs, true);
            if (!empty($ltvs)) {
                return new Table(['ltv'], $ltvs);
            }
            return "";
        });
        $grid->column('belong_date', __('Belong date'));
        $grid->column('aggregated_at', __('Aggregated at'))->display(function ($value) {
            if (!$value) return '';
            return date('Y-m-d H:i:s', (int) $value);
        });
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
        $show = new Show(DailySpecificGameChannelLtvStatistics::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('game_app_id', __('Game app id'));
        $show->field('post_channel_id', __('Post channel id'));
        $show->field('players_increments', __('Players increments'));
        $show->field('ltvs', __('Ltvs'));
        $show->field('belong_date', __('Belong date'));
        $show->field('aggregated_at', __('Aggregated at'))->as(function ($value) {
            return date('Y-m-d H:i:s', (int) $value);
        });
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('batch_aggregated_at', __('Batch aggregated at'))->as(function ($value) {
            return date('Y-m-d H:i:s', (int) $value);
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DailySpecificGameChannelLtvStatistics());

        $form->number('game_app_id', __('Game app id'));
        $form->number('post_channel_id', __('Post channel id'));
        $form->number('players_increments', __('Players increments'));
        $form->text('ltvs', __('Ltvs'));
        $form->date('belong_date', __('Belong date'))->default(date('Y-m-d'));
        $form->number('aggregated_at', __('Aggregated at'));
        $form->number('batch_aggregated_at', __('Batch aggregated at'));

        return $form;
    }
}
