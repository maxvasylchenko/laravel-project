<?php

namespace App\Models;

use App\Helpers\Enums\OrderStatusesEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeDefault(Builder $query)
    {
        return $this->statusQuery($query, OrderStatusesEnum::InProcess);
    }

    public function scopePaid(Builder $query)
    {
        return $this->statusQuery($query, OrderStatusesEnum::Paid);
    }

    public function scopeCanceled(Builder $query)
    {
        return $this->statusQuery($query, OrderStatusesEnum::Canceled);
    }

    public function scopeCompleted(Builder $query)
    {
        return $this->statusQuery($query, OrderStatusesEnum::Completed);
    }

    protected function statusQuery(Builder $query, OrderStatusesEnum $enum): Builder
    {
        return $query->where('name', $enum->value);
    }
}
