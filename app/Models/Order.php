<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(BookingDriver::class, 'booking_driver_id', 'id');
    }

    public function getTotalNoOfPeopleAttribute()
    {
        return getTotalNoOfPeopleFromCart($this->cart_data);
    }

    public function getBookingIdAttribute()
    {
        return $this->id;
    }

    public function getToursAttribute()
    {
        $tours = getToursFromCart($this->cart_data);

        if ($tours instanceof \Illuminate\Support\Collection && $tours->isNotEmpty()) {
            return $tours->pluck('title')->implode(' | ');
        }

        return 'N/A';
    }

    public function getGuestNameAttribute()
    {
        if ($this->user_id) {
            return $this->user->full_name ?? 'N/A';
        }

        if ($this->request_data) {
            $data = json_decode($this->request_data, true);
            return $data['name'] ?? 'N/A';
        }

        return 'N/A';
    }

    public function getGuestEmaiLAddressAttribute()
    {
        if ($this->user_id) {
            return $this->user->email ?? 'N/A';
        }

        if ($this->request_data) {
            $data = json_decode($this->request_data, true);
            return $data['email'] ?? 'N/A';
        }

        return 'N/A';
    }

    public function getGuestContactAttribute()
    {
        if ($this->user_id) {
            return $this->user->phone ?? 'N/A';
        }

        if ($this->request_data) {
            $data = json_decode($this->request_data, true);

            if ($data) {
                $dialCode = $data['phone_dial_code'] ?? '';
                $number = $data['phone_number'] ?? '';

                if ($dialCode || $number) {
                    return trim($dialCode.$number);
                }
            }
        }

        return 'N/A';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $lastNumber = (int) self::max('order_number');

            if ($lastNumber < 1000) {
                $lastNumber = 999;
            }

            $invoice->invoice_number = $lastNumber + 1;
        });
    }
}
