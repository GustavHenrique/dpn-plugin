<?php

/** 
 * Function for getting post object parameters
 */
function dpn_copy_action_notice(){
    
    if(!empty($_REQUEST['dpn_duplicated_objects'])){

        printf('<div id="message" class="updated notice is-dismissible"><p>' .
        _n(esc_html__('%s %s was duplicated.', 'dpn-plugin'),
        esc_html__('%s %ss were duplicated.', 'dpn-plugin'),
        intval($_REQUEST['dpn_duplicated_objects'])) . '</p></div>', 
        intval($_REQUEST['dpn_duplicated_objects']),
        $_REQUEST['dpn_obj_id']);
        
    }
}