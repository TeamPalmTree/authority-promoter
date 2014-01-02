<?php

class Controller_Promoter extends \Fuel\Core\Controller_Hybrid
{

    public function action_index()
    {
        return Response::forge(View::forge('promoter/index'));
    }

    public function action_login()
    {
        // if we got login data
        if (Input::post())
        {
            // check credentials
            if (Auth::login())
            {
                // Credentials ok, go right in.
                Response::redirect('manager');
                return;
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

}
