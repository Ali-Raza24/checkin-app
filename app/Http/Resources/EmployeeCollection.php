<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'checkin_time' => $this->getCheckinTimes(),
            'checkout_time' => $this->getCheckoutTimes(),
            'hours' => $this->getDiffInHours(),
            'date' => $this->created_at,
        ];
    }

    private function getCheckinTimes()
    {
        $checkinTimes = $this->attendances->pluck('checkin_time')->flatten();
        
        return count($checkinTimes) !== 0 ? $checkinTimes[0]:null;
    }
    private function getCheckoutTimes()
    {
        $checkoutTimes = $this->attendances->pluck('checkout_time')->flatten();

        return count($checkoutTimes) !== 0 ? $checkoutTimes[0]:null;
    }

    private function getDiffInHours()
    {
        if ($this->getCheckinTimes() !== null && $this->getCheckoutTimes() !== null) {
            $to = Carbon::createFromFormat('Y-m-d H:s:i', $this->getCheckinTimes());
            $from = Carbon::createFromFormat('Y-m-d H:s:i', $this->getCheckoutTimes());
    
            $diff_in_hours = $to->diffInHours($from);
    
            return $diff_in_hours;
        }
        return null;
    }
}
