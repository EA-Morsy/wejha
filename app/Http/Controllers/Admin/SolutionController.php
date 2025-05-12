<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SolutionRequest;
use App\Models\Solution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class SolutionController extends Controller
{
    private $viewIndex  = 'admin.pages.solutions.index';
    private $viewEdit   = 'admin.pages.solutions.create_edit';
    private $viewShow   = 'admin.pages.solutions.show';
    private $route      = 'admin.solutions';

    public function index(Request $request): View
    {
        $items = Solution::latest()->paginate(20);
        return view($this->viewIndex, compact('items'));
    }

    public function create(): View
    {
        return view($this->viewEdit, get_defined_vars());
    }

    public function edit($id): View
    {
        $item = Solution::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Solution::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function store(SolutionRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('solutions.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function update(SolutionRequest $request, $id): RedirectResponse
    {
        $item = Solution::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('solutions.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function destroy($id): RedirectResponse
    {
        $item = Solution::findOrFail($id);
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }
        if ($item->delete()) {
            flash(__('solutions.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function list(Request $request): JsonResponse
    {
        $data = Solution::select('*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($item) {
                return $item->is_active == 1
                    ? '<span class="badge bg-success">'.__('solutions.status.active').'</span>'
                    : '<span class="badge bg-secondary">'.__('solutions.status.inactive').'</span>';
            })
            ->addColumn('image', function ($item) {
                if ($item->image) {
                    return '<img src="'.asset($item->image).'" style="max-width:60px;max-height:40px;border-radius:6px;border:1px solid #eee;">';
                }
                $default = asset('assets/admin/images/default-logo.png');
                return '<img src="'.$default.'" style="max-width:60px;max-height:40px;border-radius:6px;border:1px solid #eee;opacity:.6;">';
            })
            ->rawColumns(['is_active','image'])
            ->make(true);
    }

    protected function processForm($request, $id = null): Solution|null
    {
        $item = $id == null ? new Solution() : Solution::find($id);
        $data = $request->except(['_token', '_method', 'remove_image']);

        // Handle image upload and removal
        if ($request->input('remove_image') == 1) {
            // حذف الصورة القديمة من التخزين إذا كانت موجودة
            if ($id && isset($item->image) && !empty($item->image) && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = null;
        } else if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid('solution_').'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/solutions'), $filename);
            $data['image'] = 'uploads/solutions/'.$filename;
        } else if ($id && isset($item->image)) {
            $data['image'] = $item->image; // keep old image if not replaced
        }

        $item = $item->fill($data);
        if ($request->filled('is_active')) {
            $item->is_active = 1;
        } else {
            $item->is_active = 0;
        }
        if ($item->save()) {
            return $item;
        }
        return null;
    }
}
