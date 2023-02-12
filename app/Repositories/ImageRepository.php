<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class ImageRepository implements Contracts\ImageRepositoryContract
{

    public function attach(Model $model, string $methodName, array $images = [], string $directory = null)
    {
//        dd($directory);
        if (! method_exists($model, $methodName)) {
            throw new \Exception($model::class . " doesn't have the method [{$methodName}]");
        }

        if (!empty($images)) {
            foreach ($images as $image) {
                // $user->avatar()
                // $product->images()
                call_user_func([$model, $methodName])->create(['directory' => $directory, 'path' => $image]);
            }
        }
    }
}
