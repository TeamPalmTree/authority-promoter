<?php

class Controller_Promoter extends Controller_Template
{

    public function action_login($user_id)
    {
        Auth::force_login($user_id);
    }

}
