<?php

namespace App\Admin\Controllers;

use App\Models\Album;
use App\Models\Genre;
use App\Models\Track;
use App\Http\Controllers\Controller;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class TrackController extends Controller
{
    use HasResourceActions;
    const ROUTE_NAME = 'ROUTE_TRACK';

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
            ->header('Список треков')
            ->description('список треков')
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
            ->header('Detail')
            ->description('description')
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
            ->header('Edit')
            ->description('description')
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
            ->header('Create')
            ->description('description')
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
        $grid = new Grid(new Track());

        $grid->filter(function ($filter) {
            $filter->like('track_name', 'Имя трека');
        });

        $grid->id('#');
        $grid->track_name('Имя трека');
        $grid->album_id('Альбом')->display(function ($album) {
            $album = Album::find($album);
            if (empty($album)) {
                return '';
            }

            return $album->title;
        });
        $grid->genre_id('Жанр')->display(function ($genreId) {
            $genre = Genre::find($genreId);
            if (empty($genre)) {
                return null;
            }

            return $genre->name;
        });
        $grid->singer('Название трека');
        $grid->user_id('Пользователь')->display(function ($userId) {
            $model = User::find($userId);
            if (true == empty($model)) {
                return null;
            }

            return $model->username;
        });
        $grid->deleted_at(trans('admin.deleted_at'));
        $grid->model()->withTrashed();

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
        $show = new Show(Track::findOrFail($id));

        $show->id('#');
        $show->created_at('Создано');
        $show->updated_at('Обновленно');
        $show->track_name('Название трека');
        $show->track_date('Дата трека')->as(function ($year) {
            return date('Y', strtotime($year));
        });
        $show->album('Альбом', function ($album) {
            $album->setResource('/admin/album');
            $album->title('Имя');
        });
        $show->genre('Жанр', function ($genre) {
            $genre->setResource('/admin/genre');
            $genre->name('Имя');
        });
        $show->singer('Исполнитель');
        $show->song_text('Текст трека');
        $show->filename('Filename');
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
        $form = new Form(new Track());

        $form->text('track_name', 'Название трека')->rules(['required']);
        $form->select('album_id', 'Альбом')->options(function ($id) {
            $album = Album::find($id);
            if ($album) {
                return [$album->id => $album->title];
            }
        })->ajax('/admin/api/album');
        $form->multipleSelect('genres', 'Жанр')->options(Genre::all()->pluck('name', 'id'));

        $form->text('singer', 'Исполнитель')->rules(['required']);
        $form->date('track_date', 'Дата трека')->default(date('Y'))->rules(['required']);
        $form->textarea('song_text', 'Текст трека')->rules(['required']);
        $form->file('filename', 'Файл')
            ->rules('required|mimetypes:audio/ogg,audio/wave,audio/x-wav,audio/x-pn-wav,audio/aac,audio/mp4,audio/vnd.wave,audio/flac,audio/x-flac,audio/vnd.wave,audio/x-aiff,audio/aiff,audio/x-m4a')->uniqueName()
        ;

        $form->select('user_id', 'Пользователь')->options(function ($id) {
            $user = User::find($id);

            if ($user) {
                return [$user->id => $user->username];
            }
        })->ajax('/admin/api/users')->rules(['required']);

        $form->saving(function (Form $form) {
            $form->file('filename')->move('tracks/'.$form->user_id)->uniqueName();
        });

        return $form;
    }

    public function getTracks(Request $request)
    {
        $q = $request->get('q');

        return Track::where('track_name', 'like', "%$q%")->paginate(null, ['id', 'track_name as text']);
    }
}
