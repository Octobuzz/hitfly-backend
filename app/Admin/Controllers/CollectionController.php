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
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;

class CollectionController extends Controller
{
    use HasResourceActions;
    const ROUTE_NAME = 'ROUTE_COLLECT';

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
            ->header('Коллекции')
            ->description('список')
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
            ->header('Коллекции')
            ->description('просмотр')
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
            ->header('Коллекции')
            ->description('редактирование')
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
            ->header('Коллекции')
            ->description('создание')
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
        $grid = new Grid(new Collection());

        $grid->id('#');
        $grid->title('Заголовок');
        $grid->image('Изображение');

        $grid->user_id('Создатель коллекции')->display(function ($userId) {
            return User::find($userId)['username'];
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

        $show->id('#');
        $show->title('Заголовок');
        $show->image('Изображение');
        $show->user_id('ID пользователя');
        $show->is_admin('Подборка');
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

        $form->text('title', 'Заголовок')->required();
        $form->image('image', 'Изображение')->uniqueName();
        $form->select('user_id', 'Пользователь')->options(function ($id) {
            $user = User::find($id);
            if ($user) {
                return [$user->id => $user->username];
            }
        })->ajax('/admin/api/users');
        $states = [
            'on' => ['value' => 1, 'text' => 'Админ', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'Пользователь', 'color' => 'danger'],
        ];
        $form->switch('is_admin', 'Подборка')->states($states);
        $form->saving(function (Form $form) {
            if (null !== $form->image) {
                Storage::disk('public')->delete($form->model()->getOriginal('image'));
            }
            $form->image('image')->move('collections/'.$form->user_id)->uniqueName();
        });

        return $form;
    }
}
