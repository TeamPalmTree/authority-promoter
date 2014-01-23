<?php

namespace Promoter\Model;

use Fuel\Core\DB;

class Promoter_General
{

    public static function shared_item_ids_for_items(
        $for_ids,
        $table_name,
        $shared_column_name,
        $for_column_name,
        &$permission_actions = null)
    {

        // verify not null
        if (is_null($for_ids))
            return array();

        if (!is_null($permission_actions))
            $items_items_query = DB::select(
                $table_name . '.' . $shared_column_name,
                $table_name . '.' . $for_column_name,
                $table_name . '.actions',
                array('users_permissions.actions', 'users_permissions_actions')
            );
        else
            $items_items_query = DB::select();

        // select all user ids assigned to items
        $items_items_query->from(array($table_name, $table_name));
        // if we have actions, we need to include permissions
        if (!is_null($permission_actions))
            $items_items_query->join('users_permissions')->on($table_name . '.perms_id', '=', 'users_permissions.id');
        // add additional conditions
        $items_items_query->where($for_column_name, 'in', $for_ids)->order_by($for_column_name, 'ASC');
        // get items items
        $items_items = $items_items_query->execute()->as_array();

        ////////////////////////
        // INITIAL GUT CHECKS //
        ////////////////////////

        // get count of items items
        $items_items_count = count($items_items);
        // if we have no items items, we are done
        if ($items_items_count == 0)
            return array();
        // if the items items count is less than the ids count, we are done
        if ($items_items_count < count($for_ids))
            return array();

        /////////////////////////////////////////
        // FIND SHARED USERS AMONGST ALL ITEMS //
        /////////////////////////////////////////

        $shared_ids = null;
        // sort the for ids numerically
        sort($for_ids, SORT_NUMERIC);
        // loop over all item ids, ensuring that they share at least one user
        foreach ($for_ids as $for_id)
        {

            // get current item item
            $current_item_item = current($items_items);
            // get current for id
            $current_for_id = $current_item_item[$for_column_name];
            // verify we have the item ids sequentially, else we are missing an item
            if ($current_for_id != $for_id)
                return array();

            // create temp arrays to hold current shared ids
            $current_shared_ids = array();

            do
            {

                // get current shared item id
                $current_shared_id = $current_item_item[$shared_column_name];
                // walk over shared ids for this item, adding each to the temp array
                $current_shared_ids[] = $current_shared_id;

                // save the permission actions
                if (!is_null($permission_actions))
                {

                    // get the keys of the permission actions
                    $current_action_keys = unserialize($current_item_item['actions']);
                    // get the permission actions
                    $current_permission_actions = unserialize($current_item_item['users_permissions_actions']);

                    // get current actions
                    if ($current_action_keys)
                        $current_permission_actions = array_values(array_intersect_key($current_permission_actions, array_flip($current_action_keys)));
                    else
                        $current_permission_actions = array();

                    // if we already have actions for this shared item, intersect
                    if (isset($permission_actions[$current_shared_id]))
                        $permission_actions[$current_shared_id] = array_intersect($permission_actions[$current_shared_id], $current_permission_actions);
                    else
                        $permission_actions[$current_shared_id] = $current_permission_actions;


                }

                // move to next item item
                $current_item_item = next($items_items);

            } while ($current_item_item[$for_column_name] == $for_id);

            // initialize shared ids with the first item's shared ids
            if (is_null($shared_ids))
            {
                $shared_ids = $current_shared_ids;
                continue;
            }

            // intersect current shared ids with shared ids to get new shared ids
            $shared_ids = array_intersect($current_shared_ids, $shared_ids);
            // verify we still have some shared ids
            if (count($shared_ids) == 0)
                return array();

        }

        // remove all permission actions not associated with shared ids
        if (!is_null($permission_actions))
            $permission_actions = array_intersect_key($permission_actions, array_flip($shared_ids));

        // return all shared users
        return $shared_ids;

    }

} 