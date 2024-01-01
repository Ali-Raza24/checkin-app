<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Employee extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['name', 'email', 'gender', 'dob', 'join_date', 'probation_period', 'designation', 'line_manager', 'contact_number'];

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

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     */
    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with('attendances');
    }


    public function toSearchableArray() : array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'contact_number' => '',
            'attendances.checkin_time' => '',
            'attendances.checkout_time' => '',
        ];
    }
}
