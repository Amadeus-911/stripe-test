<?php

namespace Ollyo\Task\Controllers;

class HomeController
{
    public function index()
    {
        return view('app', []);
    }
}