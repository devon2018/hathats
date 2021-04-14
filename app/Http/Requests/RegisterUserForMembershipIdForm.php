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
            return abort(404, 'Oops, something has not gone to plan. This QR Code has either: been linked to an email address already, has another membership number linked to it, or the membership number is invalid.  Please try logging in with your email address by using the light blue login button.  If the issue continues, please email rewards@hathats.co.uk and we\'ll get things sorted!');
        }
    }
}
