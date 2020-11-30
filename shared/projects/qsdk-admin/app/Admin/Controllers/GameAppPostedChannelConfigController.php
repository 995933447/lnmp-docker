<?php

namespace App\Admin\Controllers;

use App\Models\GameAppPostedChannelConfig;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameAppPostedChannelConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '游戏已发布渠道封禁配置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GameAppPostedChannelConfig());

        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();
        });

        $grid->model()->orderBy('created_at', 'DESC');

        $grid->column('id', __('Id'))->sortable();
        $grid->column('is_opened_login', __('Is opened login'));
        $grid->column('closed_login_tip', __('Closed login tip'));
        $grid->column('is_opened_pay', __('Is opened pay'));
        $grid->column('can_new_role_entry_game', __('Can new role entry game'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('game_app_posted_channel_id', __('Game app posted channel id'));

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
        $show = new Show(GameAppPostedChannelConfig::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            $tools->disableDelete();
            $tools->disableList();

            $url = back()->getTargetUrl();
            $tools->append("<a class='btn btn-sm btn-primary mallto-next' href='{$url}' target=''>返回</a> &nbsp;");
        });

        $show->field('id', __('Id'));
        $show->field('is_opened_login', __('Is opened login'))->as(function ($isOpenedLogin) {
            return GameAppPostedChannelConfig::transferCanLoginDefinition((int) $isOpenedLogin);
        });
        $show->field('closed_login_tip', __('Closed login tip'));
        $show->field('is_opened_pay', __('Is opened pay'))->as(function ($isOpenedPay) {
            return GameAppPostedChannelConfig::transferCanPayDefinition((int) $isOpenedPay);
        });
        $show->field('can_new_role_entry_game', __('Can new role entry game'))->as(function ($canNewRoleEnterGame) {
            return GameAppPostedChannelConfig::transferCanNewRoleEnterGameDefinition((int) $canNewRoleEnterGame);
        });;
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->gameAppPostedChannel(__('Link game app posted channel'), function ($gameAppPostedChannel) {
            $gameAppPostedChannel->panel()->tools(function ($tools) {
                $tools->disableDelete();
                $tools->disableEdit();
                $tools->disableList();
            });

            $gameAppPostedChannel->field('gameApp.name', __('Game app'));
            $gameAppPostedChannel->field('channel.name', __('Post channel'));
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
        $form = new Form(new GameAppPostedChannelConfig());

        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableList();

            $url = back()->getTargetUrl();
            $tools->append("<a class='btn btn-sm btn-primary mallto-next' href='{$url}' target=''>返回</a> &nbsp;");
        });

        $form->switch('is_opened_login', __('Is opened login'))->default(1);
        $form->text('closed_login_tip', __('Closed login tip'));
        $form->switch('is_opened_pay', __('Is opened pay'))->default(1);
        $form->switch('can_new_role_entry_game', __('Can new role entry game'))->default(1);
//        $form->number('game_app_posted_channel_id', __('Game app posted channel id'));

        return $form;
    }
}
