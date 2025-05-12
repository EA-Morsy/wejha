<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SolutionTypeRequest;
use App\Models\Solution;
use App\Models\SolutionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class SolutionTypeController extends Controller
{
    private $viewIndex  = 'admin.pages.solution-types.index';
    private $viewEdit   = 'admin.pages.solution-types.create_edit';
    private $viewShow   = 'admin.pages.solution-types.show';
    private $route      = 'admin.solution-types';

    public function index(Request $request): View
    {
        return view($this->viewIndex, get_defined_vars());
    }

    public function create(): View
    {
        $solutions = Solution::where('is_active', 1)->get();
        return view($this->viewEdit, get_defined_vars());
    }

    public function edit($id): View
    {
        $item = SolutionType::findOrFail($id);
        $solutions = Solution::where('is_active', 1)->get();
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = SolutionType::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = SolutionType::findOrFail($id);
        if ($item->delete()) {
            flash(__('solution_types.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(SolutionTypeRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('solution_types.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    
    public function select(Request $request): JsonResponse|string
    {
        $data = SolutionType::distinct()
                 ->where(function ($query) use ($request) {
                     if ($request->filled('q')) {
                         $query->where('name_ar', 'like', '%'.$request->q.'%')
                               ->orWhere('name_en', 'like', '%'.$request->q.'%');
                     }
                 })
                 ->where('is_active', 1)
                 ->select('id', 'name_ar', 'name_en')
                 ->get()
                 ->map(function($item) {
                     return [
                         'id' => $item->id,
                         'text' => App::isLocale('ar') ? $item->name_ar : $item->name_en
                     ];
                 });

        if ($request->filled('pure_select')) {
            $html = '<option value="">'. __('solution_types.select') .'</option>';
            foreach ($data as $row) {
                $html .= '<option value="'.$row['id'].'">'.$row['text'].'</option>';
            }
            return $html;
        }
        return response()->json($data);
    }

    public function update(SolutionTypeRequest $request, $id): RedirectResponse
    {
        $item = SolutionType::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('solution_types.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): SolutionType|null
    {
        $item = $id == null ? new SolutionType() : SolutionType::find($id);
        $data = $request->except(['_token', '_method', 'remove_icon']);

        // Handle icon upload and removal
        if ($request->input('remove_icon') == 1) {
            // حذف الأيقونة القديمة من التخزين إذا كانت موجودة
            if ($id && isset($item->icon) && !empty($item->icon) && file_exists(public_path($item->icon))) {
                unlink(public_path($item->icon));
            }
            $data['icon'] = null;
        } else if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = uniqid('solution_type_').'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/solution_types'), $filename);
            $data['icon'] = 'uploads/solution_types/'.$filename;
        } else if ($id && isset($item->icon)) {
            $data['icon'] = $item->icon; // keep old icon if not replaced
        }

        $item = $item->fill($data);
        if ($request->has('is_active')) {
            $item->is_active = 1;
        } else {
            $item->is_active = 0;
        }
        if ($item->save()) {
            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = SolutionType::with('solution')->select('*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($item) {
                return $item->is_active == 1
                    ? '<span class="badge bg-success">'.__('solution_types.active').'</span>'
                    : '<span class="badge bg-secondary">'.__('solution_types.not_active').'</span>';
            })
            ->addColumn('solution_name', function ($item) {
                if ($item->solution) {
                    return App::isLocale('ar') ? $item->solution->name_ar : $item->solution->name_en;
                }
                return '<span class="text-muted">--</span>';
            })
            ->addColumn('icon', function ($item) {
                if ($item->icon) {
                    return '<img src="'.asset($item->icon).'" style="max-width:60px;max-height:40px;border-radius:6px;border:1px solid #eee;">';
                }
                $default = asset('assets/admin/images/default-logo.png');
                return '<img src="'.$default.'" style="max-width:60px;max-height:40px;border-radius:6px;border:1px solid #eee;opacity:.6;">';
            })
            ->rawColumns(['is_active','icon', 'solution_name'])
            ->make(true);
    }
}
