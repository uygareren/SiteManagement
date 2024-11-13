<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends BaseController
{
    public function index(): string
    {
        // Load a view and return it
        return view('welcome_message'); // or any other view you'd like to load
    }
}

