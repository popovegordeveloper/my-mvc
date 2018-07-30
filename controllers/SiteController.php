<?php

use Routing\Controller;

class SiteController extends Controller
{
    public function index()
    {
        $this->render('home');
    }
}