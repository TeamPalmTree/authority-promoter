<?php

class Controller_Promoter extends \Fuel\Core\Controller_Hybrid
{

    public function before()
    {
        // forward up
        parent::before();
    }

    public function action_index()
    {
        // create view
        $view = View::forge('promoter/index');
        // set view button
        $view->button = Promoter::button_view();
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
                // load promoter config
                Config::load('promoter', true);
                // credentials ok, go right in
                Response::redirect(Config::get('promoter.base_url') . '/' . Config::get('promoter.redirect_path'));
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
        return Response::redirect($redirect_url, 'refresh');
    }

    public function action_logout()
    {
        // logout user
        Auth::logout();
        // load promoter config
        Config::load('promoter', true);
        // redirect to login
        Response::redirect(Config::get('promoter.base_url'));
    }

}
