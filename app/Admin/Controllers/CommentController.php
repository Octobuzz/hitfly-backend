<?php

namespace App\Admin\Controllers;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\User;

class CommentController extends Controller
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
        $grid = new Grid(new Comment);

        $grid->id('Id');
        //$grid->setRelation();
        $grid->user()->display(function ($user){
            return $user['username'];
        });
        $grid->commentable_type()->display(function ($comment){
            return __('messages.'.Comment::CLASS_NAME[$comment]);
        });
        $grid->comment('Comment');
        $grid->estimation('Estimation');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

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
        $show = new Show(Comment::findOrFail($id));

        $show->id('Id');
        $show->user()->as(function ($user){
            return $user->username;
        });
        $show->commentable_type('Тип комментария')->as(function ($comment){
            return __('messages.'.Comment::CLASS_NAME[$comment]);
        });
        $show->commentable()->title("Комментарий к")->as(function ($comment){
            return $comment->title;
        });


        $show->comment('Comment');
        $show->estimation('Estimation');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {

        $form = new Form(new Comment());

        $form->select('id', 'Комментарий к')->options(function($id){
            $comment = Comment::find($id);

            return [$id=>(string)$comment->commentable['title']." (".__('messages.'.Comment::CLASS_NAME[$comment['commentable_type']]).")"];
        })->rules('required');

        $form->select('user_id', 'Пользователь')->options(function ($id) {
            $user = User::find($id);
            if ($user) {
                return [$user->id => $user->username];
            }
        })->ajax('/admin/api/users');
        $form->textarea('comment', 'Comment');
        $form->select('estimation', 'Оценка')->options([0=>"Нет",1=>1,2=>2,3=>3,4=>4,5=>5]);

        return $form;
    }
}
