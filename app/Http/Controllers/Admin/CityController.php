<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class CityController extends Controller
{
    private $viewIndex  = 'admin.pages.cities.index';
    private $viewEdit   = 'admin.pages.cities.create_edit';
    private $viewShow   = 'admin.pages.cities.show';
    private $route      = 'admin.cities';

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
        $item = City::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = City::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = City::findOrFail($id);
        if ($item->delete()) {
            flash(__('cities.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(CityRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('cities.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
        $data = City::distinct()
                 ->where(function ($query) use ($request) {
                     if ($request->filled('q')) {
                         if (App::isLocale('en')) {
                             return $query->where('title_en', 'like', '%'.$request->q.'%');
                         } else {
                             return $query->where('title_ar', 'like', '%'.$request->q.'%');
                         }
                     }
                 })->select('id', 'title_en', 'title_ar')->get();

        if ($request->filled('pure_select')) {
            $html = '<option value="">'. __('City.select') .'</option>';
            foreach ($data as $row) {
                $html .= '<option value="'.$row->id.'">'.$row->text.'</option>';
            }
            return $html;
        }
        return response()->json($data);
    }


    public function update(CityRequest $request, $id): RedirectResponse
    {
        $item = City::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('cities.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): City|null
    {
        $item = $id == null ? new City() : City::find($id);
        $data = $request->except(['_token', '_method']);

        $item = $item->fill($data);
        if ($request->filled('active')) {
            $item->active = 1;
        } else {
            $item->active = 0;
        }
        if ($item->save()) {
            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = City::select('*');
        return FacadesDataTables::of($data)
        ->addIndexColumn()
        ->editColumn('active', function ($item) {
            return $item->active == 1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>' : '<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
        })
        ->filterColumn('title', function ($query, $keyword) {
            if (App::isLocale('en')) {
                return $query->where('title_en', 'like', '%'.$keyword.'%');
            } else {
                return $query->where('title_ar', 'like', '%'.$keyword.'%');
            }
        })
        ->rawColumns(['active'])
        ->make(true);
    }
}
