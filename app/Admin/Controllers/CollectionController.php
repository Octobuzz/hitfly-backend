<?php

namespace App\Admin\Controllers;

use App\Models\Collection;
use Encore\Admin\Controllers\HasResourceActions;
use App\Http\Controllers\Controller;
use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Storage;

class CollectionController extends Controller
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
        $grid = new Grid(new Collection());

        $grid->id('Id');
        $grid->title('Заголовок');
        $grid->image('Изображение');

        $grid->user_id('Создатель коллекции')->display(function ($userId) {
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
        $show = new Show(Collection::findOrFail($id));

        $show->id('Id');
        $show->title('Заголовок');
        $show->image('Изображение');
        $show->user_id('ID пользователя');
        $show->is_admin('Is admin');
        //как показать связанные треки?

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Collection());

        $form->text('title', 'Заголовок');
        $form->image('image', 'Изображение')->uniqueName();
        $form->select('user_id', 'Пользователь')->options(function ($id) {
            $user = User::find($id);
            if ($user) {
                return [$user->id => $user->username];
            }
        })->ajax('/admin/api/users');
        $states = [
            'on'  => ['value' => 1, 'text' => 'Админ', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'Пользователь', 'color' => 'danger'],
        ];
        $form->switch('is_admin', 'Коллекция администратора')->states($states);
        $form->saving(function (Form $form) {
            if($form->image !== null) {
                Storage::disk('public')->delete($form->model()->getOriginal('image'));
            }
            $form->image('image')->move('collections/'.$form->user_id)->uniqueName();
        });

        return $form;
    }
}
