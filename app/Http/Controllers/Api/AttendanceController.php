<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceStoreRequest;
use App\Http\Resources\AttendanceCollection;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $order = $request->input('order', "created_at");
        $direction = $request->input('direction', "desc");
        $attendances = Attendance::orderBy($order,$direction)->get();
        return AttendanceCollection::collection($attendances);
    }

    public function store(AttendanceStoreRequest $request)
    {
        $data = $request->all();
        foreach ($data as $key => $item) {
            if ($item['mode'] == 1) {
                Attendance::create([
                    'emp_code' => $item['emp_code'],
                    'checkin_time' => $item['checkin_time'] ?? now(),
                ]);
            }
            if ($item['mode'] == 2) {

                $lastAttendance = Attendance::where('emp_code', $item['emp_code'])
                ->latest('checkin_time')
                ->first();

                if ($lastAttendance) {
                    $lastAttendance->checkout_time = $item['checkout_time'] ?? now();
                    $lastAttendance->save();
                }
            }
        }
        return response()->json([
            'status' => 201,
            'success' => true,
            'message' => "Attendance saved successfully!"
        ]);
    }
}
