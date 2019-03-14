<?php
/**
Plugin Name: wp-maintenance
Version: 1.0.0
Author: Matchwerk Solutions GmbH
Description: This Plugin reads the env vars WP_MAINTENANCE, WP_MAINTENANCE_HEADLINE and WP_MAINTENANCE_MESSAGE and displays a maintenance page accordingly.
*/

define("ENV_WP_MAINTENANCE", 'WP_MAINTENANCE');
define("ENV_WP_MAINTENANCE_HEADLINE", 'WP_MAINTENANCE_HEADLINE');
define("ENV_WP_MAINTENANCE_MESSAGE", 'WP_MAINTENANCE_MESSAGE');

function wp_maintenance_mode() {

    $isMaintenance = defined(ENV_WP_MAINTENANCE) ? WP_MAINTENANCE : getenv(ENV_WP_MAINTENANCE);
    if ($isMaintenance === "true" || $isMaintenance === "True" || $isMaintenance === "TRUE"){
        $isMaintenance = true;
    } else {
        return false;
    }
    $maintenanceHeadline = "Under Maintenance.";
    $maintenanceMessage = "We are currently working on the site. Please try it again in a few minutes.";
    $headline = defined(ENV_WP_MAINTENANCE_HEADLINE) ? WP_MAINTENANCE_HEADLINE : getenv(ENV_WP_MAINTENANCE_HEADLINE);
    $message = defined(ENV_WP_MAINTENANCE_MESSAGE) ? WP_MAINTENANCE_MESSAGE : getenv(ENV_WP_MAINTENANCE_MESSAGE);
    if ($headline){
        $maintenanceHeadline = $headline;
    }
    if ($message){
        $maintenanceMessage = $message;
    }

    if ($isMaintenance) {
        if (!current_user_can('edit_themes') || !is_user_logged_in()) {
            wp_die('<h1>'.$maintenanceHeadline.'</h1><br />'.$maintenanceMessage);
        } else {
            error_log("Access by logged in user during maintenance.");
        }   
    }
    return $isMaintenance;
}
add_action('get_header', 'wp_maintenance_mode');

?>