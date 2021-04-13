<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckUserStatusForm;
use App\Http\Requests\RegisterUserForMembershipIdForm;
use App\Repositories\GoodTillRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     *
     * Makes an api call to the good-till api to check if a membership id is
     * valid and that there is not a user attached to that membership id.
     *
     * @param CheckUserStatusForm $form
     * @param GoodTillRepository $goodTillRepository
     *
     * @return JsonResponse
     */
    public function checkUser(CheckUserStatusForm $form, GoodTillRepository $goodTillRepository)
    {
        $goodTillCustomers = $goodTillRepository->makeCall('GET', 'customers')->data;

        $matches = collect($goodTillCustomers)->filter(function ($customer) use ($form) {
            return $customer->membership_no == $form->input('member_no') && empty($customer->email);
        });

        if (count($matches)) {
            return response()->json(['valid' => true]);
        } else {
            return abort(404, 'Oops, something has not gone to plan. This QR Code has either: been linked to an email address already, has another membership number linked to it, or the membership number is invalid.  Please try logging in with your email address by using the light blue login button.  If the issue continues, please email rewards@hathats.co.uk and we\'ll get things sorted!');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterUserForMembershipIdForm $form, GoodTillRepository $goodTillRepository)
    {
        return response()->json([
            'success' => !!$form->save($goodTillRepository)
        ], 201);
    }
}
