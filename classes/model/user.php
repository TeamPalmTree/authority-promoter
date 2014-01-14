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
            ->execute(self::$_connection);

        // verify we got it
        if (count($users) == 0)
            return null;
        // success
        return $users[0]['username'];

    }

    public static function filtered($filter)
    {

        // start users query
        $users_query = Model_User::query()
            ->select('username', 'group_id', 'email')
            ->order_by('username', 'ASC')
            ->limit(1000);
        // split up filter into commas
        $filter_parts = explode(',', $filter);
        // add where like conditions for each filter part
        foreach ($filter_parts as $filter_part)
            $users_query->or_where('username', 'LIKE', '%' . $filter_part . '%');
        // success
        return $users_query->get();

    }

    public static function for_groups($ids)
    {

        // verify not null
        if (is_null($ids))
            return array();
        // verify one
        if (count($ids) != 1)
            return array();

        // get users for this group
        return self::query()
            ->where('group_id', $ids[0])
            ->get();

    }

    public static function for_roles($ids)
    {
        return self::for_items($ids, 'users_user_roles', 'role_id');
    }

    public static function for_permissions($ids, &$actions)
    {
        return self::for_items($ids, 'users_user_permissions', 'perms_id', $actions);
    }

    private static function for_items($ids, $table_name, $column_name, &$permission_actions = null)
    {
        // get shared user ids
        $shared_user_ids = Model_General::shared_item_ids_for_items($ids, $table_name, 'user_id', $column_name, $permission_actions);
        // verify we have some
        if (count($shared_user_ids) == 0)
            return array();

        // query users
        return self::query()
            ->where('id', 'IN', $shared_user_ids)
            ->order_by('username', 'ASC')
            ->get();
    }

}
