<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $employee = Employee::create(['name' => $request->get('name')]);
        return response()->json([
            'status' => 201,
            'success' => true,
            'employee' => $employee 
        ]);
    }
}
