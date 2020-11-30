<?php

namespace App\Admin\Controllers;

use App\Models\GameApp;
use App\Models\GameAppTestableWhiteIp;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GameAppTestableWhiteIpController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'GameAppTestableWhiteIp';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GameAppTestableWhiteIp());

        $grid->filter(function ($filter) {
            $filter->where(function ($query) {
                $query->whereHas('game', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });
            }, __('Game app'));

            $filter->date('created_at', __('Created at'))->datetime();
            $filter->between('updated_at', __('Updated at'))->datetime();
        });

        $grid->model()->orderBy('created_at', 'DESC');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('game.name', __('Game app'));
        $grid->column('ip', __('Ip'));
        $grid->column('status', __('Status'))->switch([
            'on' => [
                'text' => GameAppTestableWhiteIp::transferStatusDefinition(GameAppTestableWhiteIp::VALID_STATUS),
                'value' => GameAppTestableWhiteIp::VALID_STATUS
            ],
            'off' => [
                'text' => GameAppTestableWhiteIp::transferStatusDefinition(GameAppTestableWhiteIp::INVALID_STATUS),
                'value' => GameAppTestableWhiteIp::INVALID_STATUS
            ]
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
        $show = new Show(GameAppTestableWhiteIp::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('game_app_id', __('Game app id'));
        $show->field('ip', __('Ip'));
        $show->field('status', __('Status'));
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
        $form = new Form(new GameAppTestableWhiteIp());

        $defaultGameApps = GameApp::query()->get();
        $defaultGameAppIdMapNames = [];
        foreach ($defaultGameApps as $gameApp) {
            $defaultGameAppIdMapNames[$gameApp->id] = "{$gameApp->id} -- {$gameApp->name}";
        }

        $form->select('game_app_id', __('Game app id'))->options($defaultGameAppIdMapNames);
        $form->ip('ip', __('Ip'));
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }
}
