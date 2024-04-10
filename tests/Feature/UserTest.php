<?php

namespace Tests\Feature;;

use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegisterSuccess()
    {
        $this->post('/api/users/sign-up', [
            'username' => 'hyerin',
            'password' => '12345678',
            'name' => 'Kim Hyerin'
        ])->assertStatus(201)->assertJson([
            'data' => [
                'username' => 'hyerin',
                'name' => 'Kim Hyerin'
            ]
        ]);
    }

    public function testRegisterFailed()
    {
        $this->post('/api/users/sign-up', [
            'username' => '',
            'password' => '',
            'name' => ''
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => [
                        "The username field is required."
                    ],
                    'password' => [
                        "The password field is required."
                    ],
                    'name' => [
                        "The name field is required."
                    ]
                ]
            ]);
    }

    public function testRegisterUsernameAlreadyExist()
    {
    }
}
