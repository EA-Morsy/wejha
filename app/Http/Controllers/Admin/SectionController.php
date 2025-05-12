<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DataTables;

class SectionController extends Controller
{
    private $viewIndex  = 'admin.pages.sections.index';
    private $viewCreateEdit = 'admin.pages.sections.create_edit';
    private $viewShow   = 'admin.pages.sections.show';
    private $route      = 'admin.pages.sections';

    public function index(Request $request, $page_id): View
    {
        $page = Page::findOrFail($page_id);
        $items = $page->sections()->orderBy('order')->paginate(20);
        return view($this->viewIndex, compact('items', 'page'));
    }

    public function create($page_id): View
    {
        $page = Page::findOrFail($page_id);
        return view($this->viewCreateEdit, compact('page'));
    }

    public function store(Request $request, $page_id): RedirectResponse
    {
        $page = Page::findOrFail($page_id);
        $data = $this->validateSection($request);
        $data['page_id'] = $page->id;
        Section::create($data);
        return redirect()->route($this->route.'.index', $page_id)
            ->with('success', __('sections.messages.created'));
    }

    public function edit($page_id, $id): View
    {
        $page = Page::findOrFail($page_id);
        $item = Section::where('page_id', $page_id)->findOrFail($id);
        return view($this->viewCreateEdit, compact('item', 'page'));
    }

    public function update(Request $request, $page_id, $id): RedirectResponse
    {
        $page = Page::findOrFail($page_id);
        $item = Section::where('page_id', $page_id)->findOrFail($id);
        $data = $this->validateSection($request);
        $item->update($data);
        return redirect()->route($this->route.'.index', $page_id)
            ->with('success', __('sections.messages.updated'));
    }

    public function destroy($page_id, $id): RedirectResponse
    {
        $page = Page::findOrFail($page_id);
        $item = Section::where('page_id', $page_id)->findOrFail($id);
        $item->delete();
        return redirect()->route($this->route.'.index', $page_id)
            ->with('success', __('sections.messages.deleted'));
    }

    public function show($page_id, $id): View
    {
        $page = Page::findOrFail($page_id);
        $item = Section::where('page_id', $page_id)->findOrFail($id);
        return view($this->viewShow, compact('item', 'page'));
    }

    public function list(Request $request, $page_id)
    {
        $data = Section::where('page_id', $page_id)->orderBy('order')->select('*');
        return \DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('section_type', function ($item) {
                $types = [
                    'hero' => 'primary',
                    'gallery' => 'info',
                    'articles_list' => 'success',
                    'contact_form' => 'warning',
                    'about_text' => 'secondary',
                    'video' => 'danger',
                ];
                $color = $types[$item->section_type] ?? 'dark';
                $label = __('sections.types.' . $item->section_type);
                return '<span class="badge bg-' . $color . '">' . $label . '</span>';
            })
            ->editColumn('is_active', function ($item) {
                return $item->is_active
                    ? '<span class="badge bg-success">'.__('sections.status.active').'</span>'
                    : '<span class="badge bg-secondary">'.__('sections.status.inactive').'</span>';
            })
            ->addColumn('actions', function ($item) use ($page_id) {
                $editBtn = '';
                $deleteBtn = '';
                if (auth()->user()->can('sections.edit')) {
                    $editBtn = '<a href="'.route('admin.pages.sections.edit', [$page_id, $item->id]).'" class="btn btn-sm btn-warning mx-1"><i class="fa fa-edit"></i></a>';
                }
                if (auth()->user()->can('sections.delete')) {
                    $deleteBtn = '<form method="POST" action="'.route('admin.pages.sections.destroy', [$page_id, $item->id]).'" style="display:inline;">'
                        .csrf_field().method_field('DELETE').
                        '<button type="submit" class="btn btn-sm btn-danger mx-1" onclick="return confirm(\''. __('sections.actions.confirm_delete') .'\')"><i class="fa fa-trash"></i></button></form>';
                }
                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['is_active','actions','section_type'])
            ->make(true);
    }

    public function sort(Request $request, $page_id)
    {
        $order = $request->input('order', []);
        foreach ($order as $item) {
            Section::where('page_id', $page_id)->where('id', $item['id'])->update(['order' => $item['order']]);
        }
        return response()->json(['success' => true, 'message' => __('sections.messages.sorted')]);
    }

    private function validateSection(Request $request): array
    {
        return $request->validate([
            'section_type'    => 'required|string|max:100',
            'title_ar'        => 'nullable|string|max:255',
            'title_en'        => 'nullable|string|max:255',
            'description_ar'  => 'nullable|string',
            'description_en'  => 'nullable|string',
            'content_ar'      => 'nullable|string',
            'content_en'      => 'nullable|string',
            'image'           => 'nullable|string|max:255',
            'order'           => 'required|integer',
            'settings'        => 'nullable|json',
            'is_active'       => 'required|boolean',
        ]);
    }
}
