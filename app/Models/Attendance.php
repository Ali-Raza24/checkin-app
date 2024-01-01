<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Attendance extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['emp_code','mode','checkin_time','checkout_time'];


    protected $casts = [
        'checkin_time' => 'datetime',
    ];



    public function toSearchableArray() : array
    {
        return [
            'checkin_time' => $this->checkin_time,
            'checkout_time' => $this->checkout_time,
        ];
    }
}
