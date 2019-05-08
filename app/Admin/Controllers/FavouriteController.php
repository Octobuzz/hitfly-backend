<?php

namespace App\Admin\Controllers;

use App\Models\Favourite;
use App\Http\Controllers\Controller;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class FavouriteController extends Controller
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
        $grid = new Grid(new Favourite());
        $grid->actions(function ($actions) {
            $actions->disableEdit();
        });

        $grid->id('Id');
        $grid->favouriteable_type('Тип избранного')->display(function ($favourite) {
            return __('messages.'.Favourite::CLASS_NAME[$favourite]);
        });
        $grid->favouriteable()->display(
            function ($favouriteable) {//админка возвращает массив, вместо объекта, получить имя через метод нельзя
                if (!empty($favouriteable['title'])) {
                    return $favouriteable['title'];
                }
                if (!empty($favouriteable['track_name'])) {
                    return $favouriteable['track_name'];
                }
                if (!empty($favouriteable['name'])) {
                    return $favouriteable['name'];
                }

                return 'имя ненайдено';
            }
        );
        $grid->user('Пользователь')->display(function ($user) {
            return $user['username'];
        });
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
        $show = new Show(Favourite::findOrFail($id));
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();
            });
        $show->id('Id');
        $show->favouriteable_type('Тип избранного')->as(function ($favourite) {
            return __('messages.'.Favourite::CLASS_NAME[$favourite]);
        });
        /*$show->favouriteable()->title('Тип избранного')->as(function ($favourite) {
            return $favourite->title;
        });*/
        //$show->favouriteable_id('Favouriteable id');
        $show->user('Пользователь')->as(function ($user) {
            return $user->username;
        });
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
        $form = new Form(new Favourite());
        /*$form->select('id', 'Тип избранного')->options(function ($id) {
            $favourite = Favourite::find($id);

            return [$id => (string) $favourite->favouriteable['title'].' ('.__('messages.'.Favourite::CLASS_NAME[$favourite['favouriteable_type']]).')'];
        })->ajax('/admin/api/favorite')->rules('required');*/

        $form->select('favouriteable_type', 'Тип избранного')->options(function ($type) {
            $return = [];
            if ($type) {
                foreach (Favourite::CLASS_NAME as $k => $fType) {
                    $return[$k] = __('messages.'.$fType);
                }
            }

            return $return;
        })/*->load('favouriteable_id', '/admin/api/favorite')*/->rules('required')->disable();

        $form->select('id', 'Избранное')->options(function ($id) {
            $favourite = Favourite::find($id);

            return [$id => (string) $favourite->favouriteable->getName()];
        })->rules('required');
        /*$form->select('favouriteable_id','Избранное')->model(Favourite::class);/*->options(function ($type) {
            dd($type);
            $return = [];
            if ($type) {
                foreach (Favourite::CLASS_NAME as $k => $fType) {
                    $return[$k] = __('messages.' . $fType);
                }
            }

            return $return;
        })->load('favouriteable_id', '/admin/api/favorite')->rules('required');*/

        $form->select('user_id', 'Пользователь')->options(function ($id) {
            $user = User::find($id);
            if ($user) {
                return [$user->id => $user->username];
            }
        })->ajax('/admin/api/users');

        return $form;
    }

    public function getFavourite(Request $request)
    {
        $q = $request->get('q');

        $return = [];
        $favourites = $q::query()->get();
        foreach ($favourites as $fav) {
            $return[] = ['id' => $fav->id, 'text' => $fav->getName()];
        }

        return $return;
    }
}
