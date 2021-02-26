<?php

namespace App\Http\Requests;

use App\Repositories\GoodTillRepository;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserForMembershipIdForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'member_no' => ['required', 'string'],
            'email' => ['required', 'email']
        ];
    }

    /**
     *
     * Creates the new good till user
     *
     * @param GoodTillRepository $goodTillRepository
     * @return bool
     */
    public function save(GoodTillRepository $goodTillRepository)
    {
        $goodTillCustomers = $goodTillRepository->makeCall('GET', 'customers')->data;

        $matches = collect($goodTillCustomers)->filter(function ($customer) {
            return $customer->membership_no == $this->input('member_no') && empty($customer->email);
        });

        if (count($matches)) {
            $match = $matches->first();

            try {
                $res = $goodTillRepository->makeCall('PUT', "customers/{$match->id}", ['form_params' => [
                    'email' => $this->input('email')
                ]])->data;

                if ($res->email == $this->input('email')) return true;
            } catch (\Throwable $th) {
                abort(500, 'There has been an issue connecting to the server.');
            }
        } else {
            return abort(404, 'The QR code membership number is invalid');
        }
    }
}
