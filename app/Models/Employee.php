<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'emp_code')->orderBy('created_at', 'desc');
    }

    public function hasStartDate($request)
    {
        if ($request->has('start_date') && $request->has('end_date') && $request->get('start_date') !== "null" && $request->get('end_date') == "null") {
            return true;
        }
        return false;
    }

    public function hasStartAndEndDate($request)
    {
        if ($request->has('start_date') && $request->has('end_date') && $request->get('start_date') !== "null" && $request->get('end_date') !== "null") {
            return true;
        }
        return false;
    }
}
