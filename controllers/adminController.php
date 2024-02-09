<?php

namespace controllers;

use Views\MainView;

class adminController
{
    public function index()
    {
        MainView::render('admin');
    }
}