<?php

namespace App\Admin\Controllers;

use App\Models\SupportGameType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SupportGameTypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '游戏应用类型';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SupportGameType());

        $grid->filter(function ($filter) {
            $filter->startsWith('name', __('Name'));
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        $grid->model()->orderBy('created_at', 'DESC');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'));
        $grid->column('remark', __('Remark'));
        $grid->column('status', __('Status'))->switch([
            'on' => ['text' => SupportGameType::transferStatusDefinition(SupportGameType::VALID_STATUS), 'value' => SupportGameType::VALID_STATUS],
            'off' => ['text' => SupportGameType::transferStatusDefinition(SupportGameType::INVALID_STATUS), 'value' => SupportGameType::INVALID_STATUS]
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
        $show = new Show(SupportGameType::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('remark', __('Remark'));
        $show->field('status', __('Status'))->as(function ($status) {
            return SupportGameType::transferStatusDefinition($status);
        });
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
        $form = new Form(new SupportGameType());

        $form->text('name', __('Name'));
        $form->text('remark', __('Remark'));
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }
}
