<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class CompanyController extends Controller
{
    private $viewIndex  = 'admin.pages.companies.index';
    private $viewEdit   = 'admin.pages.companies.create_edit';
    private $viewShow   = 'admin.pages.companies.show';
    private $route      = 'admin.companies';

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
        $item = Company::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Company::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Company::findOrFail($id);
        if ($item->delete()) {
            flash(__('companies.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(CompanyRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('companies.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
       $data = Company::distinct()
                ->where(function ($query) use ($request) {
                if ($request->filled('q')) {
                    if(App::isLocale('en')) {
                        return $query->where('title_en', 'like', '%'.$request->q.'%');
                    } else {
                        return $query->where('title_ar', 'like', '%'.$request->q.'%');
                    }
                }
                })->select('id', 'title_en', 'title_ar')->get();

        if ($request->filled('pure_select')) {
            $html = '<option value="">'. __('Company.select') .'</option>';
            foreach ($data as $row) {
                $html .= '<option value="'.$row->id.'">'.$row->text.'</option>';
            }
            return $html;
        }
        return response()->json($data);
    }


    public function update(CompanyRequest $request, $id): RedirectResponse
    {
        $item = Company::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('companies.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Company|null
    {
        $item = $id == null ? new Company() : Company::find($id);
        $data= $request->except(['_token', '_method']);

        $item = $item->fill($data);
            if($request->filled('active')){
                $item->active = 1;
            }else{
                $item->active = 0;
            }
        if ($item->save()) {

            if ($request->hasFile('image')) {
                $image= $request->file('image');
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $item->image->move(public_path('storage/companies'), $fileName);
                $item->image = $fileName;
                $item->save();
            }
            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = Company::select('*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($item) {
                return $item->is_active == 1
                    ? '<span class="badge bg-success">'.__('companies.status.active').'</span>'
                    : '<span class="badge bg-secondary">'.__('companies.status.inactive').'</span>';
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
