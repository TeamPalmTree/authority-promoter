<?php

class Promoter
{

    public static function menu_view()
    {
        // load promoter config
        Config::load('promoter', true);
        // create button view
        $view = View::forge('promoter/menu');
        // set view url variables
        $view->provider_url = Config::get('promoter.authority_url') . '/authority/login/facebook';
        $view->callback_url = Config::get('promoter.base_url') . '/promoter/callback';
        $view->redirect_url = Config::get('promoter.base_url') . $_SERVER['REQUEST_URI'];
        // success
        return $view;
    }

}