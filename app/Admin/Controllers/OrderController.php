<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 12.02.19
 * Time: 13:33.
 */

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Lang;

class OrderController extends Controller
{
    use HasResourceActions;
    const ROUTE_NAME = 'ROUTE_ORDER';

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
            ->header('Заказы')
            ->description('Список заказов')
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
            ->header('Просмотр')
            ->description('Список заказов')
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
            ->header('Редактирование')
            ->description('Список заказов')
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
            ->header('Создание')
            ->description('Список заказов')
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
        $grid = new Grid(new Order());
        $grid->model()->orderBy('status', 'desc')->orderBy('created_at', 'asc');
        $grid->id('#');
        $grid->status('Статус')->sortable();
        $grid->name('Товар');
        $grid->created_at('Дата заказа');
        $grid->user_id('Пользователь')->display(function ($userId) {
            $model = User::find($userId);
            if (true == empty($model)) {
                return null;
            }

            return $model->username;
        });

        $grid->disableExport();

        $grid->disableCreateButton();

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableDelete();
        });

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
        $show = new Show(Order::findOrFail($id));

        $show->id('#');
        $show->status('Статус');
        $show->name('Товар');
        $show->created_at('Дата заказа');
        $show->user_id('Пользователь')->display(function ($userId) {
            $model = User::find($userId);
            if (true == empty($model)) {
                return null;
            }

            return $model->username;
        });
        $show->panel()->tools(function ($tools) {
            $tools->disableDelete();
            $tools->disableList();
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
        $form = new Form(new Order());

        $form->text('id', '#')->disable();
        $form->select('status', 'Статус')->options(function ($id) {
            return ['NEW' => 'Новый', 'DONE' => 'Готово'];
        });
        $form->text('name', 'Товар')->disable();

        $form->select('user_id', 'Пользователь')->options(function ($id) {
            $user = User::find($id);
            if ($user) {
                return [$user->id => $user->username];
            }
        })->disable();
        $form->saving(function (Form $form) {
            $form->user_id = $form->model()->user_id;
        });

        return $form;
    }
}
