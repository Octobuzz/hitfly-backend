<?php

namespace App\Admin\Controllers;

use App\Models\City;
use App\Models\Genre;
use App\Models\MusicGroup;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;

class MusicGroupController extends Controller
{
    use HasResourceActions;

    const ROUTE_NAME = 'ROUTE_MUSIC_GROUP';

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
            ->header('Музыкальные группы')
            ->description('Список музыкальных групп')
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
            ->header('Музыкальные группы')
            ->description('Музыкальная группа')
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
        MusicGroup::query()->find($id);

        return $content
            ->header('Реадктирование музыкальной группы')
            ->description('реадктирование музыкальной группы')
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
            ->header('Создать музыкальную группу')
            ->description('создание музыкальной группы')
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
        $grid = new Grid(new MusicGroup());

        $grid->id('#');
        $grid->creator_group_id('Создатель группы')->display(function ($user_id) {
            $user = User::find($user_id);
            if (true === empty($user)) {
                return null;
            }

            return $user->username;
        });
        $grid->name('Имя');
        $grid->career_start_year('Дата начала карьеры');
        $grid->city_id('Город')->display(function ($city) {
            $city = City::find($city);
            if (true === empty($city)) {
                return null;
            }

            return $city->title;
        });
        $grid->model()->withTrashed();
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
        $show = new Show(MusicGroup::withTrashed()->findOrFail($id));

        $show->id('#');
        $show->user('Пользователь', function ($user) {
            $user->setResource('/admin/auth/users');
            $user->username('Имя');
        });
        $show->name('Имя');
        $show->career_start_year('Дата начала карьеры')->as(function ($year) {
            return $year->format('Y');
        });

        $show->genre('Жанр', function ($genre) {
            $genre->setResource('/admin/genre');
            $genre->name('Имя');
        });
        $show->description('Описание');
        $show->created_at('Создано');
        $show->updated_at('Обновлено');
        $show->deleted_at('Удалено');

        $show->location('Город', function ($city) {
            $city->setResource('/admin/city');
            $city->title('Имя');
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
        $form = new Form(new MusicGroup());
        $form->model()->withTrashed();

        $form->select('creator_group_id', 'Пользователь')->options(function ($id) {
            $user = User::find($id);

            if ($user) {
                return [$user->id => $user->username];
            }
        })->ajax('/admin/api/users');

        $form->text('name', 'Имя');
        $form->date('career_start_year', 'Старт начала карьеры')->format('YYYY')->default('YYYY');

        $form->multipleSelect('genres', 'Жанр')->options(Genre::all()->pluck('name', 'id'));

        $form->select('city_id', 'Город')->options(function ($id) {
            $city = City::find($id);
            if ($city) {
                return [$city->id => $city->title];
            }
        })->ajax('/admin/api/city');
        $form->textarea('description', 'Описание');

        $form->image('avatar_group', 'Обложка')->uniqueName();

        $form->saving(function (Form $form) {
            $form->image('avatar_group')->move(MusicGroup::PATH_FOLDER.DIRECTORY_SEPARATOR.$form->user_id)->uniqueName();
        });

        $form->saved(function (Form $form) {
            /** @var MusicGroup $model */
            $model = $form->model();
            $model->career_start_year = Carbon::createFromFormat('Y', $form->career_start_year)->format('Y-m-d H:i:s');
            $model->save();

            if (null !== $form->avatar_group) {
                $cover = $form->avatar_group;
                $nameCover = Storage::disk('public')->putFile(MusicGroup::PATH_FOLDER.DIRECTORY_SEPARATOR.$form->model()->creator_group_id, $cover);
                $form->model()->avatar_group = $nameCover;
                $form->model()->save();
            }
        });

        $form->disableEditingCheck();

        $form->disableCreatingCheck();

        $form->disableViewCheck();

        return $form;
    }

    public function getMusicGroup(Request $request)
    {
        $q = $request->get('q');

        return MusicGroup::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
    }
}
