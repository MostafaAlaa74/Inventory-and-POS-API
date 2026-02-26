<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Report\TopEmployeesReportService;
use App\Services\Report\TopSellingProductsReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $topSellingProductsReportService;
    protected $topEmployeesReportService;

    public function __construct(
        TopSellingProductsReportService $topSellingProductsReportService,
        TopEmployeesReportService $topEmployeesReportService
    ) {
        $this->topSellingProductsReportService = $topSellingProductsReportService;
        $this->topEmployeesReportService = $topEmployeesReportService;
    }


    public function topSellingProducts(Request $request)
    {
        $limit = $request->input('limit', 10);
        $topProducts = $this->topSellingProductsReportService->topSellingProducts($limit);

        return response()->json($topProducts);
    }

    public function topEmployees(Request $request)
    {
        $limit = $request->input('limit', 3);

        $topEmployees =  $this->topEmployeesReportService->getTopEmployees($limit);

        return response()->json($topEmployees, 200);
    }
}
