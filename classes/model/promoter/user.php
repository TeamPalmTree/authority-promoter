<?php

namespace Promoter\Model;

class Promoter_User extends \Auth\Model\Auth_User
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
        $users_query = Promoter_User::query()
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

    protected static function for_items($ids, $table_name, $column_name, &$permission_actions = null)
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

    public function mapped_metadata($field_names)
    {

        // get metadata
        $metadatas = Promoter_Metadata::query()
            ->where('parent_id', $this->id)
            ->where('key', 'IN', $field_names)
            ->get();

        $mapped_metadata = array();
        // map into array
        foreach ($metadatas as $metadata)
            $mapped_metadata[$metadata->key] = $metadata->value;
        // success
        return $mapped_metadata;

    }

}
