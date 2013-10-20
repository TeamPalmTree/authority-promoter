<?php

class Controller_Promoter extends \Fuel\Core\Controller_Rest
{

    public function action_login($user_id)
    {
        // force login user
        Auth::force_login($user_id);
        // get redirect url
        $redirect_url = urldecode($_GET['redirect_url']);
        // redirect :)
        return Response::redirect($redirect_url, 'refresh');
    }

}
