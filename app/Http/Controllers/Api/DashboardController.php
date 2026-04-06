<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FinancialRecord;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $users = User::count();
        $categories = Category::count();
        $financial_records = FinancialRecord::count();

        return response()->json([
            'status' => true,
            'users' => $users,
            'categories' => $categories,
            'financial_records' => $financial_records
        ]);
    }
}
