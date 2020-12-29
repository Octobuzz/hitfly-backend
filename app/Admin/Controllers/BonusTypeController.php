<?php

namespace App\Admin\Controllers;

use App\Models\BonusType;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Lang;

class BonusTypeController extends Controller
{
    use HasResourceActions;
    const ROUTE_NAME = 'ROUTE_BONUS_TYPE';

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header(Lang::get('admin.breadcrumb.'.self::ROUTE_NAME))
            ->description('список')
            ->body($this->grid())
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME)]
            );
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header(Lang::get('admin.breadcrumb.'.self::ROUTE_NAME))
            ->description('просмотр')
            ->body($this->detail($id))
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME.'.index')],
                ['text' => $id]
            );
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header(Lang::get('admin.breadcrumb.'.self::ROUTE_NAME))
            ->description('редактирование')
            ->body($this->form()->edit($id))
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME.'.index')],
                ['text' => $id]
            );
    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header(Lang::get('admin.breadcrumb.'.self::ROUTE_NAME))
            ->description('создание')
            ->body($this->form())
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME.'.index')],
                ['text' => 'Создать']
            );
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BonusType());

        $grid->id('#');
        $grid->name('Название');
        $grid->bonus('Количество баллов');
        $grid->column('constant_name', 'Константа');
        $grid->column('show_user', 'Отоюражать пользователю');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(BonusType::findOrFail($id));

        $show->id('#');
        $show->name('Название');
        $show->bonus('Количество баллов');
        $show->description('Описание');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new BonusType());

        $form->text('name', 'Название');
        $form->text('constant_name', 'Контанта');
        $form->text('bonus', 'Кол-во балов');
        $form->textarea('description', 'Описание')->required();
        $form->image('img', 'Картинка');

        $states = [
            'on' => ['value' => 1, 'text' => 'Да', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'Нет', 'color' => 'danger'],
        ];

        $form->switch('show_user', 'Отображать пользователю')->states($states);

        return $form;
    }
}
