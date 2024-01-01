<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeRequest;
use App\Http\Resources\EmployeeCollection;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $order = $request->input('order', "employees.created_at");
        $direction = $request->input('direction', "desc");

        $employees = Employee::search($request->get('search'))
                        ->query(function(Builder $builder) {
                            $builder->join('attendances', 'attendances.emp_code', '=', 'employees.id');
                            $builder->select('employees.*');
                        })
                        ->orderBy($order, $direction)
                        ->paginate(10);

        return EmployeeCollection::collection($employees);
    }

    public function store(EmployeeRequest $request)
    {
        $employeeData = $request->validated();

        $employee = Employee::create($employeeData);
        
        return response()->json([
            'status' => 201,
            'success' => true,
            'employee' => $employee
        ]);
    }

    public function view(Request $request, $id)
    {
        $query = Attendance::where('emp_code', $id);
        $order = $request->input('order', "created_at");
        $direction = $request->input('direction', "desc");
        $employee = Employee::find($id);
        $employeesAttendaces = $this->filters($request, $query)->orderBy($order, $direction)->paginate(10);
        return response()->json(['employee' => $employee,'employee_attendance' => $employeesAttendaces]);
    }


}
