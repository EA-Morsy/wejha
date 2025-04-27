<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class RateController extends Controller
{
    public function product(Request $request): View
    {
        return view('admin.pages.rates.products', get_defined_vars());
    }
    public function company(Request $request): View
    {
        return view('admin.pages.rates.companies', get_defined_vars());
    }

    public function listProducts(Request $request): JsonResponse
    {
        $data = Rate::with(['product','user','companyProduct','company'])->whereIn('rates.type', [1,2])->select('*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('product', function ($item) {
                return $item->type == 2 ? $item->companyProduct?->product?->name : $item->product?->name;
            })
            ->addColumn('company', function ($item) {
                return $item->type == 2 ? $item->companyProduct?->company?->name : $item->company?->name;
            })
            ->addColumn('user', function ($item) {
                return  $item->user?->name;
            })
            ->rawColumns(['product','user','company'])
            ->make(true);
    }
    public function listCompanies(Request $request): JsonResponse
    {
        $data = Rate::with(['company','user'])->where('rates.type', 3)->select('*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user', function ($item) {
                return  $item->user?->name;
            })
            ->addColumn('company', function ($item) {
                return  $item->company?->name;
            })
            ->rawColumns(['user','company'])
            ->make(true);

    }
}
