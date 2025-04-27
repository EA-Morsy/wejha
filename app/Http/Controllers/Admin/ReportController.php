<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function saleOrders(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::with('user')
                        ->where('type', Order::TYPE_SALE)
                        ->where('status', '!=', Order::STATUS_PENDDING_X)
                                ->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('statusText', function ($item) {
                    $class = "";
                    switch ($item->status) {
                        case Order::STATUS_PENDDING:
                            $class = "primary";
                            break;
                        case Order::STATUS_CONFIRMED:
                            $class = "success";
                            break;
                        case Order::STATUS_REJECTED:
                            $class = "danger";
                            break;
                        default:
                            $class = "primary";
                    }
                    return '<button type="button" class="btn btn-sm btn-outline-'.$class.' round waves-effect active border-0">' . strval(__('orders.statuses.' . $item->status)) . '</button>';
                })
                ->editColumn('user', function ($item) {
                    return $item->user?->name;
                })
                ->rawColumns([ 'statusText', 'change_status', 'user'])
                ->make(true);
        }
        return view('admin.pages.reports.saleOrders');
    }
    public function rentOrders(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::with('user')
            ->where('status', '!=', Order::STATUS_PENDDING_X)
            ->where('type', Order::TYPE_RENT)
                    ->select('*');
            return DataTables::of($data)
                ->addIndexColumn()

                ->editColumn('statusText', function ($item) {
                    $class = "";
                    switch ($item->status) {
                        case Order::STATUS_PENDDING:
                            $class = "primary";
                            break;
                        case Order::STATUS_CONFIRMED:
                            $class = "success";
                            break;
                        case Order::STATUS_REJECTED:
                            $class = "danger";
                            break;
                        default:
                            $class = "primary";
                    }
                    return '<button type="button" class="btn btn-sm btn-outline-'.$class.' round waves-effect active border-0">' . strval(__('orders.statuses.' . $item->status)) . '</button>';
                })
                ->editColumn('user', function ($item) {
                    return $item->user?->name;
                })
                ->rawColumns([ 'statusText', 'change_status', 'user'])
                ->make(true);
        }
        return view('admin.pages.reports.rentOrders');
    }
}
