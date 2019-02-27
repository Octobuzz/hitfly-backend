<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\MusicGroup;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GroupController extends Controller
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
            ->header('Index')
            ->description('description')
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
        $grid = new Grid(new MusicGroup());

        $grid->id('Id');
        $grid->creator_group_id('Создатель группы')->display(function ($genreId) {
            return User::find($genreId)->username;
        });
        $grid->name('Name');
        $grid->career_start_year('Год начала');
        $grid->genre_id('Жанр')->display(function ($genreId) {
            return Genre::find($genreId)->name;
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

        $show->id('Id');
        $show->creator_group_id('Creator group id');
        $show->avatar_group('Avatar group');
        $show->name('Name');
        $show->career_start_year('Career start year');
        $show->type_music_group_id('Type music group id');
        $show->genre_id('Genre id');
        $show->description('Description');
        $show->created_at('Created at');
        $show->updated_at('Updated at');
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
        $form = new Form(new MusicGroup());

        $form->select('creator_group_id', 'Создатель группы')->options(function ($id) {
            $user = User::find($id);

            if ($user) {
                return [$user->id => $user->username];
            }
        })->ajax('/admin/api/users');
        $form->text('avatar_group', 'Аватар');
        $form->text('name', 'Название группы');
        $form->datetime('career_start_year', 'Год начала')->default(date('Y'));
        $form->select('genre_id', 'Жанр')->options(function ($id) {
            $genre = Genre::find($id);

            if ($genre) {
                return [$genre->id => $genre->name];
            }
        })->ajax('/admin/api/genres');
        $form->textarea('description', 'Описание');

        return $form;
    }
}
