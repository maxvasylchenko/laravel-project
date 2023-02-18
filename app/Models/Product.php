<?php

namespace App\Models;

use App\Services\FileStorageService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'discount',
        'thumbnail',
        'quantity',
        'SKU',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function setThumbnailAttribute($image)
    {
//        dd($image, $this);
//        dd(strtolower(str_replace(' ', '_', $this->attributes['title'] )));
        if (!empty($this->attributes['thumbnail'])) {
            FileStorageService::remove($this->attributes['thumbnail']);
        }

        $this->attributes['thumbnail'] = FileStorageService::upload(
            $image,
            strtolower(str_replace(' ', '_', $this->attributes['title']))
        );
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::exists($this->attributes['thumbnail'])
                ? Storage::url($this->attributes['thumbnail'])
                : $this->attributes['thumbnail']
        );
    }

    public function slug(): Attribute
    {
        return Attribute::make(
            get: fn() => strtolower(str_replace(' ', '_', $this->attributes['title']))
        );
    }

    public function endPrice(): Attribute
    {
        return Attribute::get(function () {
            $price = is_null($this->attributes['discount']) || $this->attributes['discount'] === 0
                ? $this->attributes['price']
                : ($this->attributes['price'] - ($this->attributes['price'] * ($this->attributes['discount'] / 100)));

            return $price < 0 ? 1 : round($price, 2); // скорочення до десятичних (10.5232325 -> 10.52)
        });
    }

    public function price(): Attribute
    {
        return Attribute::get(fn() => round($this->attributes['price'], 2));
    }

    public function available(): Attribute
    {
        return Attribute::get(fn() => $this->attributes['quantity'] > 0);
    }
}
