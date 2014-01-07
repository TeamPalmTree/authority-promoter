<?php

class Model_User extends \Auth\Model\Auth_User
{

    protected static $_connection = 'authority';

    public static function display()
    {

        // get all users
        $users = self::query()
            ->select('last_login', 'username', 'email', 'group', 'profile_fields')
            ->get();

        $display_users = array();
        // move properties to parent
        foreach ($users as $user)
        {
            // convert timestamp to server datetime
            $userLastLogin = new DateTime();
            $userLastLogin->setTimestamp($user->last_login);
            // unserialize profile fields
            $profile_fields = unserialize($user->profile_fields);
            // create display user
            $display_user = array(
                'id' => $user->id,
                'user_last_login' => Helper::server_datetime_to_user_datetime_string($userLastLogin),
                'username' => $user->username,
                'email' => $user->email,
                'first_name' => isset($profile_fields['first_name']) ? $profile_fields['first_name'] : null,
                'last_name' => isset($profile_fields['last_name']) ? $profile_fields['last_name'] : null,
                'phone' => isset($profile_fields['phone']) ? $profile_fields['phone'] : null,
            );

            // get user groups
            Config::load('simpleauth');
            $groups = Config::get('simpleauth.groups');
            // set group
            $display_user['group_name'] = $groups[$user->group]['name'];
            // add to array of users
            $display_users[] = $display_user;
        }

        // success
        return $display_users;

    }

    public static function usernames($query)
    {
        $users = DB::select('username')
            ->from('users')
            ->where('username', 'LIKE', $query . '%')
            ->as_object()
            ->execute(self::$_connection);
        return Helper::extract_values('username', $users);
    }

    public static function username($id)
    {

        // verify not null
        if (is_null($id))
            return null;

        // get usernames
        $users = DB::select('username')
            ->from('users')
            ->where('id', $id)
            ->as_object()
            ->execute(self::$_connection);

        // verify we got it
        if (count($users) == 0)
            return null;
        // success
        return $users[0]['username'];

    }

}
