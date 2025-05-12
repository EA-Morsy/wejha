<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    private $viewIndex  = 'admin.pages.pages.index';
    private $viewEdit   = 'admin.pages.pages.create_edit';
    private $viewShow   = 'admin.pages.pages.show';
    private $route      = 'admin.pages';

    public function index(Request $request): View
    {
        $items = Page::latest()->paginate(20);
        return view($this->viewIndex, compact('items'));
    }

    public function create(): View
    {
        return view($this->viewEdit);
    }

    public function edit($id): View
    {
        $item = Page::findOrFail($id);
        return view($this->viewEdit, compact('item'));
    }

    public function show($id): View
    {
        $item = Page::findOrFail($id);
        return view($this->viewShow, compact('item'));
    }

    public function destroy($id): RedirectResponse
    {
        $item = Page::findOrFail($id);
        if ($item->delete()) {
            flash(__('pages.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('pages.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $item = Page::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('pages.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function list(Request $request)
    {
        $data = Page::select('*');
        return \DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('type', function ($item) {
                return '<span class="badge bg-info">'.__('pages.types.' . $item->type).'</span>';
            })
            ->editColumn('status', function ($item) {
                return $item->status === 'published'
                    ? '<span class="badge bg-success">'.__('pages.status.published').'</span>'
                    : '<span class="badge bg-secondary">'.__('pages.status.draft').'</span>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at ? $item->created_at->format('H:i Y-m-d') : '';
            })
            ->addColumn('actions', function ($item) {
                $actions = '';
                $hasActions = auth()->user()->can('pages.edit') || auth()->user()->can('pages.delete') || auth()->user()->can('pages.view');
                if ($hasActions) {
                    $actions .= '<div class="dropdown">';
                    $actions .= '<button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>';
                    $actions .= '<ul class="dropdown-menu dropdown-menu-end">';
                    if (auth()->user()->can('pages.view')) {
                        $actions .= '<li><a href="'.route('admin.pages.show', $item->id).'" class="dropdown-item"><i class="fa fa-eye text-info"></i> ' . __('pages.actions.show') . '</a></li>';
                    }
                    if (auth()->user()->can('pages.edit')) {
                        $actions .= '<li><a href="'.route('admin.pages.edit', $item->id).'" class="dropdown-item"><i class="fa fa-edit text-warning"></i> ' . __('pages.actions.edit') . '</a></li>';
                    }
                    if (auth()->user()->can('pages.delete')) {
                        $actions .= '<li><form method="POST" action="'.route('admin.pages.destroy', $item->id).'" style="display:inline;" onsubmit="return confirm(\''. __('pages.actions.confirm_delete') .'\')">'
                            .csrf_field().method_field('DELETE').
                            '<button type="submit" class="dropdown-item"><i class="fa fa-trash text-danger"></i> ' . __('pages.actions.delete') . '</button></form></li>';
                    }
                    $actions .= '</ul></div>';
                }
                return $actions;
            })
            ->rawColumns(['type','status','actions'])
            ->make(true);
    }

    protected function processForm($request, $id = null): Page|null
    {
        $item = $id == null ? new Page() : Page::find($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . ($id ?? ''),
            'type' => 'required|in:static,dynamic',
            'status' => 'required|in:published,draft',
            'meta' => 'nullable|array',
        ]);
        $data['meta'] = $data['meta'] ?? [];
        $item = $item->fill($data);
        if ($item->save()) {
            return $item;
        }
        return null;
    }
}
