<?php

namespace App\Admin\Controllers;

use App\Models\City;
use App\Models\Genre;
use App\Models\MusicGroup;
use App\Http\Controllers\Controller;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class MusicGroupController extends Controller
{
    use HasResourceActions;

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
            ->body($this->grid());
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
            ->body($this->detail($id));
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
            ->header('Реадктирование музыкальной группы')
            ->description('реадктирование музыкальной группы')
            ->body($this->form()->edit($id));
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
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MusicGroup());

        $grid->id('Id');
        $grid->creator_group_id('Создатель группы')->display(function ($user) {
            return User::find($user)->username;
        });
        $grid->name('Имя');
        $grid->career_start_year('Дата начала карьеры');
        $grid->city_id('Город')->display(function ($city) {
            return City::find($city)->title;
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
        $show = new Show(MusicGroup::findOrFail($id));

        $show->id('#');
        $show->user('Пользователь', function ($user) {
            $user->setResource('/admin/users');
            $user->username('Имя');
        });
        $show->name('Имя');
        $show->career_start_year('Дата начала карьеры');

        $show->genre('Жанр', function ($genre) {
            $genre->setResource('/admin/genre');
            $genre->name('Имя');
        });
        $show->description('Description');
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

        $form->select('creator_group_id', 'Пользователь')->options(function ($id) {
            $user = User::find($id);

            if ($user) {
                return [$user->id => $user->username];
            }
        })->ajax('/admin/api/users');

        $form->text('name', 'Имя');
        $form->datetime('career_start_year', 'Старт начала карьеры')->default(date('Y-m-d'));

        $form->select('genre_id', 'Жанр')->options(function ($id) {
            $genre = Genre::find($id);

            if ($genre) {
                return [$genre->id => $genre->name];
            }
        })->ajax('/admin/api/genres');

        $form->select('city_id', 'Город')->options(function ($id) {
            $city = City::find($id);

            if ($city) {
                return [$city->id => $city->name];
            }
        })->ajax('/admin/api/city');
        $form->textarea('description', 'Описание');

        return $form;
    }

    public function getMusicGroup(Request $request)
    {
        $q = $request->get('q');

        return MusicGroup::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
    }
}
