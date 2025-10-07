<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTitleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('store', \App\Models\Title::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'original_title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'poster' => 'nullable|url|max:500',
            'backdrop' => 'nullable|url|max:500',
            'release_date' => 'nullable|date',
            'runtime' => 'nullable|integer|min:1|max:1000',
            'budget' => 'nullable|integer|min:0',
            'revenue' => 'nullable|integer|min:0',
            'tmdb_id' => 'nullable|integer|unique:titles,tmdb_id',
            'imdb_id' => 'nullable|string|max:20',
            'is_series' => 'boolean',
            'adult' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Title name is required',
            'name.max' => 'Title name cannot exceed 255 characters',
            'runtime.min' => 'Runtime must be at least 1 minute',
            'tmdb_id.unique' => 'A title with this TMDB ID already exists',
        ];
    }
}