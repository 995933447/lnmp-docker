<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\GameApp\ToPostedChannelsJumper;
use App\Models\GameApp;
use App\Models\SupportGameType;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GameAppController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '游戏应用';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GameApp());

        $grid->filter(function ($filter) {
            $filter->startsWith('name', __('Name'));

            $filter->where(function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });
            }, __('User name'));
        });

        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();
        });

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $typeOptions = [];

            foreach (SupportGameType::query()->where('status', 1)->get() as $type) {
                $typeOptions[$type->id] = $type->name;
            }

            $selector->select('game_type_id', __('Game type'), $typeOptions);

            $selector->select('app_platform', __('App platform'), [
                GameApp::PHONE_APP_PLATFORM => GameApp::transferAppPlatformDefinition(GameApp::PHONE_APP_PLATFORM),
                GameApp::H5_APP_PLATFORM => GameApp::transferAppPlatformDefinition(GameApp::H5_APP_PLATFORM),
            ]);

            $selector->select('business_area', __('App platform'), [
                GameApp::LOCAL_COUNTRY_BUSINESS_AREA => GameApp::transferBusinessAreaDefinition(GameApp::LOCAL_COUNTRY_BUSINESS_AREA),
                GameApp::FOREIGN_AREA_BUSINESS_AREA => GameApp::transferBusinessAreaDefinition(GameApp::FOREIGN_AREA_BUSINESS_AREA),
            ]);
        });

        $grid->actions(function ($actions) {
            $actions->add(new ToPostedChannelsJumper());
        });

        $grid->model()->orderBy('created_at', 'DESC');

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'));
        $grid->column('type.name', __('Game type'));
        $grid->column('app_platform', __('App platform'))->display(function ($appPlatform) {
            return GameApp::transferAppPlatformDefinition($appPlatform);
        });
        $grid->column('business_area', __('Business area'))->display(function ($businessArea) {
            return GameApp::transferBusinessAreaDefinition($businessArea);
        });
        $grid->column('on_charged_notify_link', __('On charged notify link'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('deleted_at', __('Deleted at'));
        $grid->column('user.name', __('User name'));
        $grid->column('callback_key', __('Callback key'));

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
        $show = new Show(GameApp::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('type.name', __('Game type'));
        $show->field('app_platform', __('App platform'))->as(function ($appPlatform) {
            return GameApp::transferAppPlatformDefinition($appPlatform);
        });
        $show->field('business_area', __('Business area'))->as(function ($businessArea) {
            return GameApp::transferBusinessAreaDefinition($businessArea);
        });
        $show->field('on_charged_notify_link', __('On charged notify link'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('user.name', __('User name'));
        $show->field('callback_key', __('Callback key'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new GameApp());

        $form->text('name', __('Name'));

        $gameTypeIdMapNames = [];
        foreach (SupportGameType::query()->where('status', SupportGameType::VALID_STATUS)->get()->all() as $gameType) {
            $gameTypeIdMapNames[$gameType->id] = $gameType->name;
        }

        $form->select('game_type_id', __('Game type'))->options($gameTypeIdMapNames);
        $form->select('app_platform', __('App platform'))->options([
            GameApp::PHONE_APP_PLATFORM => GameApp::transferAppPlatformDefinition(GameApp::PHONE_APP_PLATFORM),
            GameApp::H5_APP_PLATFORM => GameApp::transferAppPlatformDefinition(GameApp::H5_APP_PLATFORM)
        ]);
        $form->select('business_area', __('Business area'))->options([
            GameApp::LOCAL_COUNTRY_BUSINESS_AREA => GameApp::transferBusinessAreaDefinition(GameApp::LOCAL_COUNTRY_BUSINESS_AREA),
            GameApp::FOREIGN_AREA_BUSINESS_AREA => GameApp::transferBusinessAreaDefinition(GameApp::FOREIGN_AREA_BUSINESS_AREA)
        ]);
        $form->url('on_charged_notify_link', __('On charged notify link'));

        $userIdMapNames = [];
        foreach (User::all() as $user) {
            $userIdMapNames[$user->id] = $user->name;
        }

        $form->select('user_id', __('User name'))->options($userIdMapNames);

        $form->resetCallbackKeyButton('callback_key');

        return $form;
    }
}
