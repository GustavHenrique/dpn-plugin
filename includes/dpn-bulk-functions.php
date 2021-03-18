<?php

/** 
 * Adds new bulk actions to the bulk actions area.
 * 
 * @param array $dpn_bulk_array The current array of items in the bulk actions area.
 * @return array The modified array of items to be placed in the bulk actions area.
 */
function dpn_bulk_actions($dpn_bulk_array){
    $dpn_bulk_array['dpn_bulk_duplicate'] = __('Duplicate');
    return $dpn_bulk_array;
}

/** 
 * Handles the bulk actions area requests.
 * 
 * @param string $redirect      The string containing the current view URL   
 * @param string $doaction      The action being taken
 * @param array  $object_ids    The items to take the action on. Accepts an array of IDs of posts, comments, terms, links, plugins, attachments, or users.
 * 
 * @return array The redirect URL, modified with query entries.
 */
function dpn_bulk_action_handler($redirect, $doaction, $object_ids){
    
    $redirect = remove_query_arg('dpn_duplicated_objects', $redirect);

    if($doaction == 'dpn_bulk_duplicate'){
        if(!is_array($object_ids)){
            $str = __("Action failed.");
            wp_die($str);
        }
        foreach($object_ids as $dpn_post_id){
            $dpn_post_data = get_post($dpn_post_id);
            if(!empty($dpn_post_data)){
                if(dpn_duplicate_post($dpn_post_id) === false){
                    $str = __("Action failed.");
                    wp_die($str);  
                }
                else{
                    $redirect = add_query_arg(
                        array(
                        'dpn_duplicated_objects' => count($object_ids),
                        'dpn_obj_id' => get_post_type($dpn_post_id)
                        ), 
                    $redirect);
                }   
            }
        }       
    }
    return $redirect;
}

