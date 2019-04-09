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

class TrackController extends Controller
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
            ->header('Список треков')
            ->description('список треков')
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
            ->header('Detail')
            ->description('description')
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
            ->header('Edit')
            ->description('description')
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
            ->header('Create')
            ->description('description')
            ->body($this->form());
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
            return Album::find($album)->title;
        });
        $grid->genre_id('Жанр')->display(function ($genreId) {
            return Genre::find($genreId)->name;
        });
        $grid->singer('Название трека');
        $grid->user_id('Пользователь')->display(function ($userId) {
            return User::find($userId)->username;
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
        $show = new Show(Track::findOrFail($id));

        $show->id('Id');
        $show->created_at('Created at');
        $show->updated_at('Updated at');
        $show->track_name('Track name');
        $show->album_id('Album id');
        $show->genre_id('Genre id');
        $show->singer('Singer');
        $show->track_date('Track date');
        $show->song_text('Song text');
        $show->track_hash('Track hash');
        $show->filename('Filename');
        $show->state('State');
        $show->user_id('User id');
        $show->deleted_at('Deleted at');

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

        $form->text('track_name', 'Track name');
        $form->number('album_id', 'Album id');
        $form->number('genre_id', 'Genre id');
        $form->text('singer', 'Singer');
        $form->datetime('track_date', 'Track date')->default(date('Y-m-d H:i:s'));
        $form->textarea('song_text', 'Song text');
        $form->text('track_hash', 'Track hash');
        $form->text('filename', 'Filename');
        $form->text('state', 'State');
        $form->number('user_id', 'User id');

        return $form;
    }
}
