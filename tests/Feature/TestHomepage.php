<?php

namespace Tests\Feature;

use Tests\TestCase;

class TestHomepage extends TestCase
{
    public function testHomepageNeedsLogin()
    {
        $response = $this->get('/');
        $response->assertStatus(302); // 302 = redirect
    }
}
