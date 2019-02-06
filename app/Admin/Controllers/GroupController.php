<?php

namespace App\Admin\Controllers;

use App\Models\Group;
use App\Http\Controllers\Controller;
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
     * @param mixed $id
     * @param Content $content
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
     * @param mixed $id
     * @param Content $content
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
        $grid = new Grid(new Group);

        $grid->id('Id');
        $grid->creator_group_id('Creator group id');
        $grid->name('Name');
        $grid->career_start_year('Career start year');
        $grid->genre_id('Genre id');
        $grid->created_at('Created at');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Group::findOrFail($id));

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
        $form = new Form(new Group);

        $form->number('creator_group_id', 'Creator group id');
        $form->text('avatar_group', 'Avatar group');
        $form->text('name', 'Name');
        $form->datetime('career_start_year', 'Career start year')->default(date('Y-m-d H:i:s'));
        $form->number('type_music_group_id', 'Type music group id');
        $form->number('genre_id', 'Genre id');
        $form->textarea('description', 'Description');

        return $form;
    }
}
