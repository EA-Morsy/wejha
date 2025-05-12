<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class IndustryController extends Controller
{
    private $viewIndex  = 'admin.pages.industries.index';
    private $viewEdit   = 'admin.pages.industries.create_edit';
    private $route      = 'admin.industries';

    public function index(Request $request): View
    {
        return view($this->viewIndex);
    }

    public function list(Request $request)
    {
        $data = Industry::select('*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($item) {
                return $item->is_active == 1
                    ? '<span class="badge bg-success">'.__('industries.status.active').'</span>'
                    : '<span class="badge bg-secondary">'.__('industries.status.inactive').'</span>';
            })
            ->addColumn('logo', function ($item) {
                if ($item->logo) {
                    return '<img src="'.asset($item->logo).'" style="max-width:60px;max-height:40px;border-radius:6px;border:1px solid #eee;">';
                }
                $default = asset('assets/admin/images/default-logo.png');
                return '<img src="'.$default.'" style="max-width:60px;max-height:40px;border-radius:6px;border:1px solid #eee;opacity:.6;">';
            })
            ->addColumn('actions', function ($item) {
                $editBtn = '';
                $deleteBtn = '';
                if (auth()->user()->can('industries.edit')) {
                    $editBtn = '<a href="'.route('admin.industries.edit', $item->id).'" class="btn btn-sm btn-primary mx-1"><i class="fa fa-edit"></i></a>';
                }
                if (auth()->user()->can('industries.delete')) {
                    $deleteBtn = '<form method="POST" action="'.route('admin.industries.destroy', $item->id).'" style="display:inline;">'
                        .csrf_field().method_field('DELETE').
                        '<button type="submit" class="btn btn-sm btn-danger mx-1" onclick="return confirm(\'هل أنت متأكد من الحذف؟\')"><i class="fa fa-trash"></i></button></form>';
                }
                return $editBtn.$deleteBtn;
            })
            ->rawColumns(['is_active','logo','actions'])
            ->make(true);
    }

    public function create(): View
    {
        return view($this->viewEdit);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->processForm($request);
        return to_route($this->route.'.index');
    }

    public function edit($id): View
    {
        $item = Industry::findOrFail($id);
        return view($this->viewEdit, compact('item'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->processForm($request, $id);
        return to_route($this->route.'.index');
    }

    public function destroy($id): RedirectResponse
    {
        $item = Industry::findOrFail($id);
        if ($item->logo && file_exists(public_path($item->logo))) {
            @unlink(public_path($item->logo));
        }
        $item->delete();
        return to_route($this->route.'.index');
    }

    protected function processForm($request, $id = null)
    {
        $item = $id == null ? new Industry() : Industry::findOrFail($id);
        $data = $request->except(['_token', '_method', 'remove_logo']);

        if ($request->input('remove_logo') == 1) {
            if ($item->logo && file_exists(public_path($item->logo))) {
                @unlink(public_path($item->logo));
            }
            $data['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'industry_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/industries'), $filename);
            $data['logo'] = 'uploads/industries/' . $filename;
        } elseif ($id && isset($item->logo)) {
            $data['logo'] = $item->logo;
        }

        $item->fill($data);
        $item->is_active = $request->filled('is_active') ? 1 : 0;
        $item->save();
        return $item;
    }
}
