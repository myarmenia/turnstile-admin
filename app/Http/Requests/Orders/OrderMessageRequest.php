<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Http;

class OrderMessageRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $locale = $this->header('Accept-Language', 'hy'); // дефолт: армянский
        app()->setLocale($locale);

        return [
            'full_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            // 'product_code' => 'required',
            'captcha' => 'required|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (!$this->captcha) {
                return;
            }

            $response = Http::asForm()->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret'   => config('services.recaptcha.secret'),
                    'response' => $this->captcha,
                    'remoteip' => $this->ip(),
                ]
            );

            $result = $response->json();

            if (!($result['success'] ?? false)) {
                $validator->errors()->add('captcha', __('captcha.invalid'));
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
