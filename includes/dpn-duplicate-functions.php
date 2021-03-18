<?php

/**
 * Adds the link to the row, containing the option to duplicate a post/page
 * 
 */
function dpn_add_duplicate_link($actions, $post)
{
    if(!current_user_can('edit_posts')){
        return;
    }
    $actions['duplicate'] = '<a href="'. wp_nonce_url('admin.php?action=dpn_duplicate_post&post='. $post->ID, basename(__FILE__), 'nonce').'" title ="'._x('Duplicate', 'verb').'"rel="permalink">' ._x('Duplicate', 'verb').'</a>' ;
    return $actions;
}

/** 
 * Function for getting post object parameters
 * 
 * @param int $post_ID The post id number.
 * @return array The array containing the post info and a modified post title with a ' (copy)' tag appended to it.
 */
function dpn_get_post_parameters($post_ID){
    $dpn_post = get_post($post_ID);
    $dpn_data = array(
        'comment_status'        => $dpn_post->comment_status,
        'ping_status'           => $dpn_post->ping_status,
        'post_author'           => $dpn_post->post_author,
        'post_content'          => $dpn_post->post_content,
        'post_excerpt'          => $dpn_post->post_excerpt,
        'post_name'             => $dpn_post->post_name,
        'post_parent'           => $dpn_post->post_parent,
        'post_password'         => $dpn_post->post_password,
        'post_status'           => $dpn_post->post_status,
        'post_title'            => ($dpn_post->post_title . _x(' (copy)', 'noun')),
        'post_type'             => $dpn_post->post_type,
        'to_ping'               => $dpn_post->to_ping,
        'menu_order'            => $dpn_post->menu_order,
        'pinged'                => $dpn_post->pinged,
        'post_modified'         => $dpn_post->post_modified,
        'post_modified_gmt'     => $dpn_post->post_modified_gmt,
        'post_content_filtered' => $dpn_post->post_content_filtered,
        'post_parent'           => $dpn_post->post_parent,
        'guid'                  => $dpn_post->guid,
        'menu_order'            => $dpn_post->menu_order,
        'post_mime_type'        => $dpn_post->post_mime_type,
        'comment_count'         => $dpn_post->comment_count,
        'filter'                => $dpn_post->filter

    );
    return $dpn_data;
}


/** 
 * Function for duplicating the post/page
 * 
 * @param int $dpn_post_ID The post id number.
 * @return bool Boolean return for success purpose checking.
 */
function dpn_duplicate_post($dpn_post_ID){

    if(!current_user_can('edit_posts') || !isset($_GET['nonce']) && !$dpn_post_ID){      
        return false;
    }
    
    //Checking whether the post comes from the bulk handler
    if(!$dpn_post_ID){    
        $dpn_post_ID = (isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']));
    }
    
    $dpn_post_data = get_post($dpn_post_ID);

    if(empty($dpn_post_data)){
        $str = __("Invalid Post ID: ");
        wp_die($str . $dpn_post_ID);
    }

    $dpn_post_type = get_post_type($dpn_post_ID);

    if(isset($dpn_post_data)){
         $dpn_post_arr = dpn_get_post_parameters($dpn_post_ID);
    }else{
        $str = __('Post data not found.');
        wp_die($str);
    }

    $dpn_copied_post_id = wp_insert_post($dpn_post_arr);

    if(is_wp_error($dpn_copied_post_id)){
        $str = __("Action failed, could not duplicate the post.");
        wp_die($str);
    }

    $redirect_to = admin_url('edit.php?post_type='.$dpn_post_type);

    $redirect_to = add_query_arg(
        array(
            'dpn_duplicated_objects' => 1,
            'dpn_obj_id' => $dpn_post_type),
        $redirect_to
    );
    
    wp_redirect($redirect_to);
    return true;
}
