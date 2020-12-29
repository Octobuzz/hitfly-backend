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
use App\User;
use Carbon\Carbon;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Lang;

class ArtistController extends Controller
{
    use HasResourceActions;
    const ROUTE_NAME = 'ROUTE_ARTIST';

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
            ->header('Профили артистов')
            ->description('профили артистов')
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
            ->description('профили артистов')
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
            ->description('профили артистов')
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
            ->description('профили артистов')
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
        $grid = new Grid(new ArtistProfile());

        $grid->id('#');
        $grid->user_id('Пользователь')->display(function ($userId) {
            $model = User::find($userId);
            if (true == empty($model)) {
                return null;
            }

            return $model->username;
        });
        $grid->career_start('Дата начала карьеры')->display(function ($date) {
            return Carbon::parse($date)->format('Y');
        });
        $grid->description('Описание');
        $grid->deleted_at(trans('admin.deleted_at'));

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
        $show = new Show(ArtistProfile::findOrFail($id));

        $show->id('#');
        $show->user_id('Пользователь')->display(function ($userId) {
            $model = User::find($userId);
            if (true == empty($model)) {
                return null;
            }

            return $model->username;
        });
        $show->career_start('Дата начала карьеры')->display(function ($date) {
            return Carbon::parse($date)->format('Y');
        });
        $show->description('Описание');
        $show->genres('Жанры')->as(function ($genres) {
            return $genres->pluck('name');
        })->label();

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
