<?php

namespace App\Models;

use App\Helpers\Enums\OrderStatusesEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'status_id',
        'user_id',
        'name',
        'surname',
        'phone',
        'email',
        'city',
        'address',
        'total',
        'vendor_order_id',
        //        "transaction_id"
    ];

    public function status()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(['quantity', 'single_price']);
    }

    public function inProcess(): Attribute
    {
        return Attribute::get(fn () => $this->status->name === OrderStatusesEnum::InProcess->value);
    }

    public function fullName(): Attribute
    {
        return Attribute::get(fn () => ucfirst($this->attributes['name']).' '.ucfirst($this->attributes['surname']));
    }
//    public function routeNotificationFor($driver, $notification = null)
//    {
//        return 'some@test.com';
//    }
}
