<?php

namespace App\Admin\Controllers;

use App\Models\Lifehack;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Lang;

class LifehackController extends Controller
{
    use HasResourceActions;

    const ROUTE_NAME = 'ROUTE_LIFEHACKS';

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
            ->header('Лайфхаки')
            ->description(' ')
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
            ->header('Лайфхаки')
            ->description(' ')
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
            ->header('Лайфхаки - редактирование')
            ->description(' ')
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
            ->header('Создать лайфхак')
            ->description('')
            ->body($this->form())
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME.'.index')]
            );
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Lifehack());

        $grid->id('Id');
        $grid->image('Изображение')->image();
        $grid->title('Заголовок');
        $grid->sort('Сортировка');

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
        $show = new Show(Lifehack::findOrFail($id));

        $show->id('Id');
        $show->image('Изображение');
        $show->title('Заголовок');
        $show->sort('Сортировка');
        $show->created_at('Дата создания');
        $show->updated_at('Дата обновления');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Lifehack());

        $form->text('title', 'Заголовок');
        $form->text('sort', 'Сортировка (меньше-приоритетней)')->rules('integer')->default(500);
        $form->image('image', 'Изображение')->required();
        $form->multipleSelect('tags', 'Теги')->options(Tag::all()->pluck('name', 'id'))->required();

        return $form;
    }
}
