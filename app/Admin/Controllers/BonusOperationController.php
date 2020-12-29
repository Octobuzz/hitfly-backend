<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 12.02.19
 * Time: 13:33.
 */

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ArtistProfile;
use App\Models\Genre;
use App\Models\Purse;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Lang;

class BonusOperationController extends Controller
{
    use HasResourceActions;
    const ROUTE_NAME = 'ROUTE_BONUS_OPERATION';

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
            ->header('История операций с бонусами')
            ->description('История бонусов')
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
            ->description('История операций с бонусами')
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
            ->description('История операций с бонусами')
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
            ->description('История операций с бонусами')
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
        $grid = new Grid(new Purse());

        $grid->id('# кошелька');
        $grid->user_id('Пользователь')->display(function ($userId) {
            $model = User::find($userId);
            if (true == empty($model)) {
                return null;
            }

            return $model->username;
        });
        $grid->balance('Баланс');
        $grid->name('Тип кошелька');
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableExport();

        $grid->disableCreateButton();

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableDelete();
            $actions->disableEdit();
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
        $show = new Show(Purse::findOrFail($id));

        $show->id('# кошелька');
        $show->operations('операции', function ($operation) {
            $operation->id('#');
            $operation->amount('Сумма');
            $operation->disableExport();
            $operation->disableActions();
            $operation->disableCreateButton();
            $operation->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
        });
        $show->balance('Баланс');
        $show->name('Тип кошелька');
        $show->panel()->tools(function ($tools) {
            $tools->disableDelete();
            $tools->disableList();
            $tools->disableEdit();
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
        $form = new Form(new ArtistProfile());

        $form->text('id')->disable();
        $form->select('user_id', 'Пользователь')->options(function ($id) {
            $user = User::find($id);
            if ($user) {
                return [$user->id => $user->username];
            }
        })->ajax('/admin/api/users');
        $form->date('career_start', 'Дата начала карьеры')->default(date('Y'));
        $form->textarea('description', 'Описание')->rules(['required']);
        $form->multipleSelect('genres', 'Жанры')->options(Genre::all()->pluck('name', 'id'))->rules(['required']);

        return $form;
    }
}
