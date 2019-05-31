<?php

namespace App\Admin\Controllers;

use App\Models\Charts;
use App\Http\Controllers\Controller;
use App\Models\Track;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Lang;

class ChartController extends Controller
{
    use HasResourceActions;
    const ROUTE_NAME = 'ROUTE_CHART';

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
            ->header('Чарты')
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
            ->header('Чарты')
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
            ->header('Чарты')
            ->description('Редактирование')
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
            ->header('Чарты')
            ->description('Создание')
            ->body($this->form())
            ->breadcrumb(
                ['text' => Lang::get('admin.breadcrumb.'.self::ROUTE_NAME), 'url' => \route(self::ROUTE_NAME . '.index')],
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
        $grid = new Grid(new Charts());

        $grid->id('#');
        $grid->track_id('ID трека');
        $grid->weekly_rate('Недельный рейтинг');
        $grid->rating('Рейтинг');
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
        $show = new Show(Charts::findOrFail($id));

        $show->id('#');
        $show->track_id('ID трека');
        $show->weekly_rate('Недельный рейтинг');
        $show->rating('Рейтинг');
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
        $form = new Form(new Charts());
        $form->select('track_id', 'Название трека')->options(function ($id) {
            $track = Track::find($id);

            if ($track) {
                return [$track->id => $track->track_name];
            }
        })->ajax('/admin/api/tracks')->rules('required');
        $form->number('weekly_rate', 'Weekly rate')->default(1);
        $form->number('rating', 'Rating')->default(1);

        return $form;
    }
}
