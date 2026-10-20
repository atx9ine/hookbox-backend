<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWebhookRequestsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'method' => 'required|in:POST,GET,DELETE,PUT,PATCH',
            'path' => 'required|string',
            'query' => 'required|json',
            'headers' => 'required|json',
            'body' => 'required|json',
            'ip_address' => 'required|ip',
            'content-type' => 'required|string',
            'received_at' => 'required|date'
        ];
    }
}
