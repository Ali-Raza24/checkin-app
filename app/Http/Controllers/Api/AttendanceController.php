<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceStoreRequest;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function store(AttendanceStoreRequest $request)
    {
        $data = $request->all();
        // dd($data);
        foreach ($data as $key => $item) {
            if ($item['mode'] == 1) {
                Attendance::create([
                    'emp_code' => $item['emp_code'],
                    'mode'     => $item['mode'],
                    'checkin_time' => $item['checkin_time'] ?? now(),
                ]);
            }
            if ($item['mode'] == 2) {
                Attendance::create([
                    'emp_code' => $item['emp_code'],
                    'mode'     => $item['mode'],
                    'checkin_time' => $item['checkout_time'] ?? now(),
                ]);
            }
        }

        return response()->json([
            'status' => 201,
            'success' => true,
            'message' => "Attendance saved successfully!"
        ]);
    }
}
