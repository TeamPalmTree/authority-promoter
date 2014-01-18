<?php

namespace Promoter\Model;

class Model_User extends \Auth\Model\Auth_User
{

    protected static $_connection = 'authority';

    public static function search_usernames($query)
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

    public static function usernames($ids)
    {

        // get usernames
        $users = DB::select('id', 'username')
            ->from('users')
            ->where('id', 'IN', $ids)
            ->execute(self::$_connection);

        $usernames = array();
        // map to array
        foreach ($users as $user)
            $usernames[$user['id']] = $user['username'];
        // success
        return $usernames;

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

}
