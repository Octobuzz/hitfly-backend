<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 12.02.19
 * Time: 13:33.
 */

namespace App\Admin\Controllers;

use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Http\Request;

class UserController extends \Encore\Admin\Controllers\UserController
{
    use HasResourceActions;

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

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if (1 == $actions->getKey()) {
                $actions->disableDelete();
            }
        });

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

        $form->text('username', trans('admin.username'))->rules('required');
        $form->text('email', trans('auth.email'))->rules('required');
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('confirmed');
        $form->password('password_confirmation', trans('admin.password_confirmation'))
            ->default(function ($form) {
                return null;
            });

        $form->ignore(['password_confirmation']);

        $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
        $form->multipleSelect('permissions', trans('admin.permissions'))->options($permissionModel::all()->pluck('name', 'id'));

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            } else {
                $form->password = $form->model()->password;
            }
        });

        $form->editing(function (Form $form) {
            $form->ignore(['password', 'password_confirmation']);
        });

        return $form;
    }
}
