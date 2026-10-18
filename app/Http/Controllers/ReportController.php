<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Report\EmployeesReportService;
use App\Services\Report\ProductsReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $productsReportService;
    protected $EmployeesReportService;

    public function __construct(
        ProductsReportService $productsReportService,
        EmployeesReportService $EmployeesReportService
    ) {
        $this->productsReportService = $productsReportService;
        $this->EmployeesReportService = $EmployeesReportService;
    }


    public function productsReport(Request $request)
    {
        $limit = $request->input('limit', 10);
        $topProducts = $this->productsReportService
            ->withStatus($request->input('status', 'completed'))
            ->betweenDates($request->input('start_date'), $request->input('end_date'))
            ->get($limit, $request->input('order_by', 'total_sold'));

        return response()->json($topProducts);
    }

    public function topEmployees(Request $request)
    {
        $limit = $request->input('limit', 3);

        $topEmployees =  $this->EmployeesReportService
            ->withStatus($request->input('status', 'completed'))
            ->betweenDates($request->input('start_date'), $request->input('end_date'))
            ->get($limit, $request->input('order_by', 'total_orders'));

        return response()->json($topEmployees, 200);
    }
}
