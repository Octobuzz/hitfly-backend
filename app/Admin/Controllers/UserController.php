<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 12.02.19
 * Time: 13:33.
 */

namespace App\Admin\Controllers;

use App\Models\City;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class UserController extends \Encore\Admin\Controllers\UserController
{
    use HasResourceActions;
    const ROUTE_NAME = 'ROUTE_USER';

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header(trans('admin.administrator'))
            ->description(trans('admin.list'))
            ->body($this->grid()->render())
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME)]
            );
    }
    public function show($id, Content $content)
    {
        return $content
            ->header(trans('admin.administrator'))
            ->description(trans('admin.detail'))
            ->body($this->detail($id))
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME . '.index')],
                ['text' => $id]
            );
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('admin.administrator'))
            ->description(trans('admin.edit'))
            ->body($this->form()->edit($id))
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME . '.index')],
                ['text' => $id]
            );
    }

    public function users(Request $request)
    {
        $q = $request->get('q');

        return User::where('username', 'like', "%$q%")->paginate(null, ['id', 'username as text']);
    }

    protected function grid()
    {
        $userModel = config('admin.database.users_model');

        $grid = new Grid(new $userModel());

        $grid->id('ID')->sortable();
        $grid->username(trans('admin.username'));
        $grid->email(trans('admin.email'));
        $grid->roles(trans('admin.roles'))->pluck('name')->label();
        $grid->created_at(trans('admin.created_at'));
        $grid->updated_at(trans('admin.updated_at'));

//        $grid->actions(function (Grid\Displayers\Actions $actions) {
//            $actions->disableDelete();
//        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $userModel = config('admin.database.users_model');
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');

        $form = new Form(new $userModel());

        $form->display('id', 'ID');

        $form->text('username', 'Имя пользователя')->rules('required');
        $form->text('email', 'Email (логин)')->rules('required');
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('confirmed');
        $form->password('password_confirmation', trans('admin.password_confirmation'))
            ->default(function ($form) {
                return null;
            });

        $form->ignore(['password_confirmation']);

        $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
        $form->multipleSelect('permissions', trans('admin.permissions'))->options($permissionModel::all()->pluck('name', 'id'));

        $form->select('city_id', 'Город')->options(function ($id) {
            $city = City::find($id);

            if ($city) {
                return [$city->id => $city->title];
            }
        })->ajax('/admin/api/city');

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        $form->divider();

        $form->editing(function (Form $form) {
            $form->ignore(['password', 'password_confirmation']);

            $savingUser = $form->model();
            $form->model()->load('artist');
            if (true === $savingUser->isRole('star') || $savingUser->isRole('performer')) {
                $form->getRelations();
                $form->date('artist.career_start', 'Дата начала карьеры');
                $form->textarea('artist.description', 'Описание деятельности');
            }
        });

        $form->saving(function (Form $form) {
            $form->model()->load('artist');
            $form->getRelations();
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            } else {
                $form->password = $form->model()->password;
            }
            /** @var User $savingUser */
            $savingUser = $form->model();
            $savingUser->roles()->sync(array_filter($form->roles));
            if (true === $savingUser->isRole('star') || $savingUser->isRole('performer')) {
                $savingUser->artist()->updateOrCreate($form->artist);
            }
        });

        $form->tools(function (Form\Tools $tools) {
            // Disable `Delete` btn.
            $tools->disableDelete();
        });

        return $form;
    }
}
