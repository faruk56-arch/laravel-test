<?php

namespace App\Http\Requests\Actions;

use Illuminate\Foundation\Http\FormRequest;

class ValidateAdminChangesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if ($this->research) {
            return $this->research->user->id === $this->user()->id;
        }
        return $this->product->merchant->user->first()->id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'accept' => 'required|boolean'
        ];
    }
}
