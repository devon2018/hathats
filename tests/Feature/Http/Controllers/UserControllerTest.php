<?php

namespace Tests\Feature\Http\Controllers;

use App\Repositories\GoodTillRepository;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * Test it validates a request
     *
     * @return void
     */
    public function test_it_checks_the_existence_of_a_customer_based_on_the_member_id()
    {
        $res = $this->postJson(route('users.check'), ['member_no' => 'testmemno']);
        $res->assertStatus(200);
    }


    /**
     * Test it creates a user
     *
     * @return void
     */
    public function test_it_registers_a_new_user_against_a_membership_no()
    {
        $res = $this->postJson(route('users.create'), ['member_no' => 'testmemno', 'email' => 'test@domain.com']);
        $res->assertStatus(201);
    }
}
