<?php

namespace App\Admin\Controllers;

use App\Models\Track;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class TrackController extends Controller
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
        $grid = new Grid(new Track);

        $grid->id('Id');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');
        $grid->track_name('Track name');
        $grid->album_id('Album id');
        $grid->genre_id('Genre id');
        $grid->singer('Singer');
        $grid->track_date('Track date');
        $grid->song_text('Song text');
        $grid->track_hash('Track hash');
        $grid->filename('Filename');
        $grid->state('State');
        $grid->user_id('User id');
        $grid->deleted_at('Deleted at');

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
        $form = new Form(new Track);

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

    public function getTracks(Request $request)
    {
        $q = $request->get('q');

        return Track::where('track_name', 'like', "%$q%")->paginate(null, ['id', 'track_name as text']);
    }
}
