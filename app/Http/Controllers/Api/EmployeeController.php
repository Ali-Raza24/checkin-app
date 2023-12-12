<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeCollection;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('attendances');
        $order = $request->input('order', "created_at");
        $direction = $request->input('direction', "desc");
        $employees = $this->filters($request,$query)->orderBy($order,$direction)->paginate(10);
    
        return EmployeeCollection::collection($employees);   
    }

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

    public function view($id)
    {
        $employeeDetails = Employee::with('attendances')->findOrFail($id);
        return response()->json(['employee' => $employeeDetails]);
    }

    private  function filters(Request $request,$query)
    {
        $model = new Employee();
        
        if ($request->has('name') && $request->get('name') !== "null") {
            $query->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }

        if ($model->hasStartDate($request)) {
            $query->whereHas('attendances', function ($q) use ($request) {
                $q->whereDate('checkin_time', $request->get('start_date'));
            });
        }

        if ($model->hasStartAndEndDate($request)) {
            $query->whereHas('attendances', function ($q) use ($request) {
                $q->whereBetween('checkin_time' ,[$request->get('start_date'), $request->get('end_date')]);
            });
        }

        return  $query;
    }
}
