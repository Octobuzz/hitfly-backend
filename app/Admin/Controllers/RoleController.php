<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 12.02.19
 * Time: 13:33.
 */

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Lang;

class RoleController extends \Encore\Admin\Controllers\RoleController
{
    use HasResourceActions;
    const ROUTE_NAME = 'ROUTE_ROLE';

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header(trans('Роли'))
            ->description(trans('Список'))
            ->body($this->grid()->render())
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME)]
            );
    }

    public function show($id, Content $content)
    {
        return $content
            ->header(trans('Роли'))
            ->description(trans('Просмотр'))
            ->body($this->detail($id))
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME.'.index')],
                ['text' => $id]
            );
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('Роли'))
            ->description(trans('Редактирование'))
            ->body($this->form()->edit($id))
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME.'.index')],
                ['text' => $id]
            );
    }

    public function create(Content $content)
    {
        return $content
            ->header(trans('admin.roles'))
            ->description(trans('admin.create'))
            ->body($this->form())
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME.'.index')],
                ['text' => 'Создать']
            );
    }
}
