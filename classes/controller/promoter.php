<?php

class Controller_Promoter extends \Fuel\Core\Controller_Hybrid
{

    public function before()
    {
        // forward up
        parent::before();
        // load promoter config
        Config::load('promoter', true);
    }

    public function action_index()
    {
        // create view
        $view = View::forge('promoter/index');
        // set view url variables
        $view->provider_url = Config::get('promoter.authority_url') . '/authority/login/facebook';
        $view->callback_url = Config::get('promoter.base_url') . '/promoter/callback';
        $view->redirect_url = Config::get('promoter.base_url') . '/' . Config::get('promoter.redirect_path');
        // success
        return Response::forge($view);
    }

    public function action_login()
    {
        // if we got login data
        if (Input::post())
        {
            // check credentials
            if (Auth::login())
            {
                // get redirect url
                $redirect_url = urldecode($_GET['redirect_url']);
                // credentials ok, go right in
                Response::redirect($redirect_url);
            }
            else
            {
            }
        }
    }

    public function action_callback($user_id)
    {
        // force login user
        Auth::force_login($user_id);
        // get redirect url
        $redirect_url = urldecode($_GET['redirect_url']);
        // redirect :)
        Response::redirect($redirect_url);
    }

    public function action_logout()
    {
        // logout user
        Auth::logout();
        // redirect to login
        Response::redirect(Config::get('promoter.base_url'));
    }

}
