<?php

namespace App\Models;

class SimpleUser
{
    public $id;
    public $name;
    public $email;
    public $role;
    public $is_active;

    public function __construct($id = 1, $name = 'Admin Test', $email = 'admin@test.com', $role = 'admin', $is_active = true)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->is_active = $is_active;
    }
}
