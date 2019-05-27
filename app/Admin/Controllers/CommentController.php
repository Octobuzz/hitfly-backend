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
use Illuminate\Support\Facades\Lang;

class CommentController extends Controller
{
    use HasResourceActions;
    const ROUTE_NAME = 'ROUTE_COMMENT';
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
            ->header('Жанры')
            ->description('Список')
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
            ->header('Жанры')
            ->description('просмотр')
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
            ->header('Жанры')
            ->description('редактирование')
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
            ->header('Жанры')
            ->description('создание')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Comment());
        $grid->disableCreateButton();
        $grid->id('#');
        //$grid->setRelation();
        $grid->user('Пользователь')->display(function ($user) {
            return $user['username'];
        });
        $grid->commentable_type('Тип комментария')->display(function ($comment) {
            return __('messages.'.Comment::CLASS_NAME[$comment]);
        });
        $grid->comment('Комментарий');
        $grid->estimation('Оценка');
        $grid->created_at('Дата создания');
        $grid->updated_at('Дата обновления');

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
        $show = new Show(Comment::findOrFail($id));

        $show->id('#');
        $show->user('Пользователь')->as(function ($user) {
            return $user->username;
        });
        $show->commentable_type('Тип комментария')->as(function ($comment) {
            return __('messages.'.Comment::CLASS_NAME[$comment]);
        });
        $show->commentable()->title('Комментарий к')->as(function ($comment) {
            return $comment->title;
        });

        $show->comment('Комментарий');
        $show->estimation('Оценка');
        $show->created_at('Дата создания');
        $show->updated_at('Дата обновления');

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

        $form->select('id', 'Комментарий к')->options(function ($id) {
            $comment = Comment::find($id);

            return [$id => (string) $comment->commentable['title'].' ('.__('messages.'.Comment::CLASS_NAME[$comment['commentable_type']]).')'];
        })->rules('required');

        $form->select('user_id', 'Пользователь')->options(function ($id) {
            $user = User::find($id);
            if ($user) {
                return [$user->id => $user->username];
            }
        })->ajax('/admin/api/users');
        $form->textarea('comment', 'Комментарий');
        $form->select('estimation', 'Оценка')->options([0 => 'Нет', 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5]);

        return $form;
    }
}
