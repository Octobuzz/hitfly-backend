<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 12.02.19
 * Time: 13:33.
 */

namespace App\Admin\Controllers;

use App\Models\ArtistProfile;
use App\Models\City;
use App\Models\Genre;
use App\User;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\PDOException;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
            ->header(trans('admin.users'))
            ->description(trans('admin.list'))
            ->body($this->grid()->render())
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME)]
            );
    }

    public function show($id, Content $content)
    {
        return $content
            ->header(trans('admin.users'))
            ->description(trans('admin.detail'))
            ->body($this->detail($id))
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME.'.index')],
                ['text' => $id]
            );
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('admin.users'))
            ->description(trans('admin.edit'))
            ->body($this->form()->edit($id))
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME.'.index')],
                ['text' => $id]
            );
    }

    public function create(Content $content)
    {
        $form = $this->form();
        $form->setAction(route('add_user'));
        $content
            ->header(trans('admin.administrator'))
            ->description(trans('admin.create'))
            ->body($form)
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME.'.index')],
                ['text' => 'Создать']
            );

        return $content;
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
        $grid->deleted_at(trans('admin.deleted_at'));
        $grid->model()->withTrashed();

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
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
        $userModel = config('admin.database.users_model');

        $show = new Show($userModel::findOrFail($id));

        $show->id('#');
        $show->username(trans('admin.username'));
        $show->email('Email (логин)');
        $show->avatar(trans('admin.avatar'))->image();
        $show->roles(trans('admin.roles'))->as(function ($roles) {
            return $roles->pluck('name');
        })->label();
        $show->permissions(trans('admin.permissions'))->as(function ($permission) {
            return $permission->pluck('name');
        })->label();
        $show->favouritegenre(trans('admin.f_genres'))->as(function ($roles) {
            return $roles->pluck('name');
        })->label();
        //if (true === $show->model()->isRole('star') || $show->model()->isRole('performer')) {
        $show->artistprofile('Дата начала карьеры')->as(function ($carrer) {
            if (isset($carrer->career_start)) {
                return Carbon::parse($carrer->career_start)->format('Y');
            } else {
                return '';
            }
        });
        $show->artist('Описание деятельности')->as(function ($description) {
            if (isset($carrer->career_start)) {
                return $description->description;
            } else {
                return '';
            }
        });

        $show->created_at(trans('admin.created_at'));
        $show->updated_at(trans('admin.updated_at'));

        return $show;
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

        $form->display('id', '#');

        $form->text('username', 'Имя пользователя')->rules('required');
        $form->text('email', 'Email (логин)')->rules('required');
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules(['confirmed', 'regex:/(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/i']);
        $form->password('password_confirmation', trans('admin.password_confirmation'))
            ->default(function ($form) {
                return null;
            });

        $form->ignore(['password_confirmation']);

        $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
        $form->multipleSelect('permissions', trans('admin.permissions'))->options($permissionModel::all()->pluck('name', 'id'));
        $form->multipleSelect('favouritegenre', trans('admin.f_genres'))->options(Genre::all()->pluck('name', 'id'));

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

            $form->model()->load('artistProfile', 'favouriteGenres');
            $savingUser = $form->model();

            $form->getRelations();
            if ((true === $savingUser->isRole('star') || $savingUser->isRole('performer')) && null !== $form->model()->artistProfile) {
                $form->getRelations();
                $form->html('<a href="/admin/auth/artist/'.$form->model()->artistProfile->id.'/edit">Профиль артиста</a>');
                $form->date('artist_profile.career_start', 'Дата начала карьеры')->format('YYYY');
                $form->textarea('artist_profile.description', 'Описание деятельности');
            }
        });

        $form->saving(function (Form $form) {
            $form->model()->load('artistProfile');
            $form->getRelations();
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            } else {
                $form->password = $form->model()->password;
            }
            /** @var User $savingUser */
            $savingUser = $form->model();

            $savingUser->roles()->sync(array_filter($form->roles));
            if (
                true === $savingUser->isRole(User::ROLE_STAR)
                || $savingUser->isRole(User::ROLE_PERFORMER)
                ) {
                $profile = $form->artist_profile;
                if (null == $profile) {
                    $ap = new ArtistProfile([
                            'user_id' => $savingUser->id,
                        ]);
                    $ap->save();
                } else {
                    ArtistProfile::updateOrCreate(['user_id' => $savingUser->id], $profile);
                }
            }
        });
        $form->builder()->getFooter()->disableReset(true);
        $form->tools(function (Form\Tools $tools) {
            // Disable `Delete` btn.
            $tools->disableDelete();
        });

        return $form;
    }

    public function addUser(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = new User();
            $user->username = $request->get('username');
            $user->email = $request->get('email');
            $user->password = bcrypt($request->get('password'));
            $user->save();

            $user->roles()->sync(array_filter($request->get('roles')));
            $user->favouriteGenres()->sync(array_filter($request->get('favouritegenre')));

            $user->save();

            $user->load('roles');
            if (true === $user->isRole(User::ROLE_STAR) || $user->isRole(User::ROLE_PERFORMER)) {
                $artistProfile = new ArtistProfile(['user_id' => $user->id]);
                $artistProfile->save();
            }

            if ($request->has('avatar')) {
                $image = $request->file('avatar');
                $nameFile = md5(microtime());
                $imagePath = "avatars/$user->id/".$nameFile.'.'.$image->getClientOriginalExtension();
                $image_resize = Image::make($image->getRealPath());
                $image_resize->fit(config('image.size.avatar.default.width'), config('image.size.avatar.default.height'));
                $path = Storage::disk('public')->getAdapter()->getPathPrefix();
                //создадим папку, если несуществует
                if (!file_exists($path.'avatars/'.$user->id)) {
                    Storage::disk('public')->makeDirectory('avatars/'.$user->id);
                }
                $image_resize->save($path.$imagePath, 100);
                $user->avatar = $imagePath;
            }
            $user->save();
            DB::commit();
        } catch (PDOException $e) {
            Log::error($e->getMessage(), $e->getTrace());
            DB::rollBack();
        } catch (\Exception $e) {
            Log::alert($e->getMessage(), $e->getTrace());
        }
        return redirect(route(self::ROUTE_NAME.'.index'));
    }
}
