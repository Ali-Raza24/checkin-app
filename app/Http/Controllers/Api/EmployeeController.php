<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeRequest;
use App\Http\Resources\EmployeeCollection;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('attendances');
        $order = $request->input('order', "created_at");
        $direction = $request->input('direction', "desc");
        $employees = $this->filters($request, $query)->orderBy($order, $direction)->paginate(10);

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

    private  function filters(Request $request, $query)
    {
        $model = new Employee();

        if ($request->has('name') && $request->get('name') !== "null") {
            $query->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }

        if ($model->hasStartDate($request)) {
            if ($request->get('is_attendance')) {
                $query->whereDate('checkin_time', $request->get('start_date'));
            } else {
                $query->whereHas('attendances', function ($q) use ($request) {
                    $q->whereDate('checkin_time', $request->get('start_date'));
                });
            }
        }

        if ($model->hasStartAndEndDate($request)) {
            if ($request->get('is_attendance')) {
                $query->whereDate('checkin_time', '>=', $request->get('start_date'))
                    ->whereDate('checkin_time', '<=',  $request->get('end_date'));
            } else {
                $query->whereHas('attendances', function ($q) use ($request) {
                    $q->whereDate('checkin_time', '>=', $request->get('start_date'))
                        ->whereDate('checkin_time', '<=',  $request->get('end_date'));
                });
            }
        }

        return  $query;
    }
}
