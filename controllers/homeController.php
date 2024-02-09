<?php

namespace controllers;

use Views\MainView;

class homeController
{
    public function index()
    {
        MainView::render('home');
    }
}