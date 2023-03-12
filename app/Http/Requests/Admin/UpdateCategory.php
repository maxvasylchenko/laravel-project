<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategory extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        /**
//         * @var User $user
//         */
//
//        $user = auth()->user();
        // dd(auth()->user()->hasPermissionTo(config('permission.access.categories.publish')));
        return auth()->user()->can(config('permission.access.categories.publish'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $categoryID = $this->route('category')->id;

        return [
            'name' => ['required', 'string', 'min:2', 'max:50', Rule::unique('categories', 'name')->ignore($categoryID)],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:App\Models\Category,id'],
        ];
    }
}
