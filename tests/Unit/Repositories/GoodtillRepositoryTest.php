<?php

namespace Tests\Unit\Repositories;

use App\Repositories\GoodTillRepository;
use Tests\TestCase;

class GoodtillRepositoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_authenticates_with_goodies_correctly()
    {
        $repo = new GoodTillRepository();
        $this->assertNotEmpty($repo->getToken());
    }
}
