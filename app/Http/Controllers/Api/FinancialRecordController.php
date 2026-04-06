<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FinancialRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FinancialRecordController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = FinancialRecord::with([
            'user:id,name',
            'category:id,name'
        ]);

        //    
        if ($request->type) {
            $query->where('type', $request->type);
        }


        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $financial_records = $query->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'data' => $financial_records->items(),
            'meta' => [
                'current_page' => $financial_records->currentPage(),
                'last_page' => $financial_records->lastPage(),
                'total' => $financial_records->total(),
            ]
        ]);
    }

    public function show($id)
    {
        $financial_record = FinancialRecord::find($id);
        if ($financial_record == null) {
            return response()->json([
                'status' => false,
                'message' => "Record not found!"
            ]);
        }

        $users = User::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();

        $financial_record->load([
            'user:id,name',
            'category:id,name'
        ]);

        return response()->json([
            'status' => true,
            'data' => $financial_record,
            'users' => $users,
            'categories' =>  $categories
        ]);
    }

    private function validateData($request)
    {
        return Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
        ]);
    }

    public function store(Request $request)
    {

        $validator = $this->validateData($request);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $record = new FinancialRecord();
        $record->user_id = $request->user_id;
        $record->category_id = $request->category_id;
        $record->amount = $request->amount;
        $record->type = $request->type;
        $record->date = $request->date;
        $record->description = $request->description;
        $record->save();

        return response()->json([
            'status' => true,
            'message' => 'Financial Record Created Successfully',
            'data' => $record
        ], 200);
    }

    public function update($id, Request $request)
    {
        $record = FinancialRecord::find($id);

        if ($record == null) {
            return response()->json([
                'status' => false,
                'message' => "Record not found!"
            ], 404);
        }

        $validator = $this->validateData($request);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $record->user_id = $request->user_id;
        $record->category_id = $request->category_id;
        $record->amount = $request->amount;
        $record->type = $request->type;
        $record->date = $request->date;
        $record->description = $request->description;
        $record->status = $request->status;
        $record->save();

        $record->load([
            'user:id,name',
            'category:id,name'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Financial Record Updated Successfully',
            'data' => $record,

        ], 200);
    }

    public function destroy($id)
    {
        $record = FinancialRecord::find($id);

        if ($record == null) {
            return response()->json([
                'status' => false,
                'message' => "Record not found!"
            ], 404);
        }

        $record->delete();

        return response()->json([
            'status' => true,
            'message' => "Record deleted successfully"
        ]);
    }
}
