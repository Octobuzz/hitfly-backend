<?php

namespace App\Admin\Controllers;

use App\Models\Album;
use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\MusicGroup;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class AlbumController extends Controller
{
    use HasResourceActions;
    const ROUTE_NAME = 'ROUTE_ALBUM';
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
            ->header('Альбомы')
            ->description('Список альбомов')
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
            ->header('Детально')
            ->description('Обзор альбома')
            ->body($this->detail($id))
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME . '.index')],
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
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id))
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME . '.index')],
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
            ->header('Создать')
            ->description('Создание альбома')
            ->body($this->form())
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME . '.index')],
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
        $grid = new Grid(new Album());

        $grid->id('#');
        $grid->title('Назвние');
        $grid->author('Автор');
        $grid->year('Год');
        $grid->likes('Лайки');
        $grid->dislikes('Дизлайки');
        $grid->genre_id('Жанр')->display(function ($genreId) {
            $genre = Genre::find($genreId);
            if (empty($genre)) {
                return null;
            }

            return Genre::find($genreId)->name;
        });
        $grid->music_group_id('Музыкальная группа')->display(function ($genreId) {
            return MusicGroup::find($genreId)->name;
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
        $show = new Show(Album::findOrFail($id));

        $show->id('#');
        $show->title('Название');
        $show->author('Автор');
        $show->year('Год');
        $show->cover('Обложка');
        $show->likes('Лайки');
        $show->dislikes('Дизлайки');
        $show->created_at('Созадно');
        $show->updated_at('Обновлено');
        $show->genre('Жанр', function ($genre) {
            $genre->setResource('/admin/genre');
            $genre->name('Имя');
        });
        $show->musicGroup('Музыкальная группа', function ($musicGroup) {
            $musicGroup->setResource('/admin/musicgroup');
            $musicGroup->name('Имя');
        })
        ->title('Музыкальная группа');
        $show->deleted_at('Дата удаленния');
        $show->user('Пользователь', function ($user) {
            $user->setResource('/admin/auth/users');
            $user->username('Имя');
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
        $form = new Form(new Album());

        $form->text('title', 'Название')->rules(['required']);
        $form->text('author', 'Автор')->rules(['required']);
        $form->image('cover', 'Обложка')->uniqueName();
        $form->select('genre_id', 'Жанр')->options(function ($id) {
            $genre = Genre::find($id);

            if ($genre) {
                return [$genre->id => $genre->name];
            }
        })->ajax('/admin/api/genres')->rules(['required']);

        $form->select('music_group_id', 'Музыкальная группа')->options(function ($id) {
            $musicGroup = MusicGroup::find($id);

            if ($musicGroup) {
                return [$musicGroup->id => $musicGroup->name];
            }
        })->ajax('/admin/api/music/group');

        $form->select('user_id', 'Пользователь')->options(function ($id) {
            $user = User::find($id);

            if ($user) {
                return [$user->id => $user->username];
            }
        })->ajax('/admin/api/users')->rules(['required']);

        $form->saving(function (Form $form) {
            $form->image('cover')->move('albums/'.$form->user_id)->uniqueName();
        });

        return $form;
    }

    public function getAlbum(Request $request)
    {
        $q = $request->get('q');

        return Album::where('title', 'like', "%$q%")->paginate(null, ['id', 'title as text']);
    }
}
