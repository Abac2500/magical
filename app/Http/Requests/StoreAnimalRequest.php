<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreAnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'species_id' => 'required|integer|exists:species,id',
            'gender' => ['required', new Enum(Gender::class)],
            'birth_date' => 'required|date|before_or_equal:today',
            'best_friend_name' => 'required|string|max:255',
        ];
    }
}
