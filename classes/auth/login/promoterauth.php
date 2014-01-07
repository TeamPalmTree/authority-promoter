<?php

class Auth_Login_Promoterauth extends \Auth\Auth_Login_Ormauth
{

    public function force_login($user_id = '')
    {
        // bail out if we don't have a user
        if (empty($user_id))
        {
            return false;
        }

        // get the user we need to login
        if ( ! $user_id instanceOf Auth\Model\Auth_User)
        {
            $this->user = Auth\Model\Auth_User::query()
                ->select(\Config::get('ormauth.table_columns', array()))
                ->related('metadata')
                ->where('id', '=', $user_id)
                ->get_one();
        }
        else
        {
            $this->user = $user_id;
        }

        // did we find it
        if ($this->user and ! $this->user->is_new())
        {
            // store the logged-in user and it's hash in the session
            \Session::set('username', $this->user->username);
            \Session::set('login_hash', $this->create_login_hash());

            // and rotate the session id, we've elevated rights
            \Session::instance()->rotate();

            return true;
        }

        // force a logout
        //$this->logout();

        // and signal a failed login
        return false;
    }

}