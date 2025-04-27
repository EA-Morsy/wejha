<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class CouponController extends Controller
{
    private $viewIndex  = 'admin.pages.coupons.index';
    private $viewEdit   = 'admin.pages.coupons.create_edit';
    private $viewShow   = 'admin.pages.coupons.show';
    private $route      = 'admin.coupons';

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
        $item = Coupon::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Coupon::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Coupon::findOrFail($id);
        if ($item->delete()) {
            flash(__('coupons.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(CouponRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('coupons.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
        $data = Coupon::distinct()
                 ->where(function ($query) use ($request) {
                     if ($request->filled('q')) {
                         if (App::isLocale('en')) {
                             return $query->where('description_en', 'like', '%'.$request->q.'%');
                         } else {
                             return $query->where('description_ar', 'like', '%'.$request->q.'%');
                         }
                     }
                 })->select('id', 'description_en', 'description_ar')->get();

        if ($request->filled('pure_select')) {
            $html = '<option value="">'. __('Coupon.select') .'</option>';
            foreach ($data as $row) {
                $html .= '<option value="'.$row->id.'">'.$row->text.'</option>';
            }
            return $html;
        }
        return response()->json($data);
    }


    public function update(CouponRequest $request, $id): RedirectResponse
    {
        $item = Coupon::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('coupons.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Coupon|null
    {
        $item = $id == null ? new Coupon() : Coupon::find($id);
        $data = $request->except(['_token', '_method']);
        $item = $item->fill($data);
        $item->user_id = auth()->user()->id;
        if ($item->save()) {
            if ($request->hasFile('brand_logo')) {
                $brand_logo = $request->file('brand_logo');
                $fileName = time() . rand(0, 999999999) . '.' . $brand_logo->getClientOriginalExtension();
                $item->brand_logo->move(public_path('storage/coupons'), $fileName);
                $item->brand_logo = $fileName;
                $item->save();
            }
            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = Coupon::with(['category','user'])->select('*');
        return FacadesDataTables::of($data)
        ->addIndexColumn()
        ->addColumn('category', function ($item) {
            return $item->category?->title;
        })
        ->addColumn('user', function ($item) {
            return $item->user?->name;
        })
        ->addColumn('photo', function ($item) {
            return "<img src=".$item->photo." width='100px' height='100px'>";
        })
        ->addColumn('location_url', function ($item) {
            return "<a href=".$item->location_url." target=_blank><i data-feather='map' class='font-medium-2'></i></a>";
        })

        ->filterColumn('description', function ($query, $keyword) {
            if (App::isLocale('en')) {
                return $query->where('description_en', 'like', '%'.$keyword.'%');
            } else {
                return $query->where('description_ar', 'like', '%'.$keyword.'%');
            }
        })
        ->rawColumns(['category','photo','location_url','user'])
        ->make(true);
    }
}
