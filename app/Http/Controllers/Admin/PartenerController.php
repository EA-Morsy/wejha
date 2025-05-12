<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartenerRequest;
use App\Models\Partener;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class PartenerController extends Controller
{
    private $viewIndex  = 'admin.pages.parteners.index';
    private $viewEdit   = 'admin.pages.parteners.create_edit';
    private $viewShow   = 'admin.pages.parteners.show';
    private $route      = 'admin.parteners';

    public function index(Request $request): View
    {
        return view($this->viewIndex, get_defined_vars());
    }

    public function create(): View
    {
        return view($this->viewEdit, get_defined_vars());
    }

    public function edit($id): View
    {
        $item = Partener::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Partener::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Partener::findOrFail($id);
        if ($item->delete()) {
            flash(__('parteners.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(PartenerRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('parteners.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
        $data = Partener::distinct()
                 ->where(function ($query) use ($request) {
                     if ($request->filled('q')) {
                             return $query->where('name', 'like', '%'.$request->q.'%');
                     }
                 })->select('id', 'name')->get();

        if ($request->filled('pure_select')) {
            $html = '<option value="">'. __('Parteners.select') .'</option>';
            foreach ($data as $row) {
                $html .= '<option value="'.$row->id.'">'.$row->text.'</option>';
            }
            return $html;
        }
        return response()->json($data);
    }


    public function update(PartenerRequest $request, $id): RedirectResponse
    {
        $item = Partener::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('parteners.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Partener|null
    {
        $item = $id == null ? new Partener() : Partener::find($id);
        $data = $request->except(['_token', '_method', 'remove_logo']);

        // Handle logo upload and removal
        if ($request->input('remove_logo') == 1) {
            // حذف الصورة القديمة من التخزين إذا كانت موجودة
            if ($id && isset($item->logo) && !empty($item->logo) && Storage::disk('public')->exists($item->logo)) {
                Storage::disk('public')->delete($item->logo);
            }
            $data['logo'] = null;
        } else if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = uniqid('partener_').'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/parteners'), $filename);
            $data['logo'] = 'uploads/parteners/'.$filename;
        } else if ($id && isset($item->logo)) {
            $data['logo'] = $item->logo; // keep old logo if not replaced
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

    public function list(Request $request): JsonResponse
    {
        $data = Partener::select('*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($item) {
                return $item->is_active == 1
                    ? '<span class="badge bg-success">'.__('parteners.active').'</span>'
                    : '<span class="badge bg-secondary">'.__('parteners.not_active').'</span>';
            })
            ->addColumn('logo', function ($item) {
                if ($item->logo) {
                    return '<img src="'.asset($item->logo).'" style="max-width:60px;max-height:40px;border-radius:6px;border:1px solid #eee;">';
                }
                $default = asset('assets/admin/images/default-logo.png');
                return '<img src="'.$default.'" style="max-width:60px;max-height:40px;border-radius:6px;border:1px solid #eee;opacity:.6;">';
            })
            ->rawColumns(['is_active','logo'])
            ->make(true);
    }
}
