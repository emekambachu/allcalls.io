<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        if ($this->user()->phone != $this->phone) {
            return [
                'first_name' => ['string', 'max:255'],
                'last_name' => ['string', 'max:255'],
                'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
                'phone' => ['required', 'string', 'min:10', 'max:15',  Rule::unique('users', 'phone')->ignore($this->user()->id), 'regex:/^[0-9]*$/'],
                'phone_code' => ['required', 'regex:/^\+(?:[0-9]){1,4}$/'],
                'phone_country' => ['required'],
                'call_types' => ['required'],
                'selected_states' => ['required', 'array'],
                'selected_states.*.typeId' => ['required', 'exists:call_types,id'],
                'selected_states.*.selectedStateIds.*' => ['nullable', 'exists:states,id'],
                'timezone' => ['nullable', 'string']
            ];
        }

        return [
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'call_types' => ['required'],
            'selected_states' => ['required', 'array'],
            'selected_states.*.typeId' => ['required', 'exists:call_types,id'],
            'selected_states.*.selectedStateIds.*' => ['nullable', 'exists:states,id'],
            'timezone' => ['nullable', 'string']
        ];
    }
}
