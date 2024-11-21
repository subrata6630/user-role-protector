<?php
/*
Plugin Name: User Role Protector
Plugin URI: https://github.com/subrata6630/user-role-protector
Description: A simple and nice plugin to block existing users from logging into the admin panel by assigning them to the 'Blocked' user role, as simple as that.
Version: 1.1.0
Author: subrata-deb-nath
Author URI: https://subrata6630.github.io/
License: GPLv2 or later
Text Domain: user-role-protector
*/

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


add_action('init', function () {
    add_role('urb_user_blocked', __('Blocked', 'user-role-protector'), array('blocked' => true));
    add_rewrite_rule('blocked/?$', 'index.php?blocked=1', 'top');
});

add_action('init', function () {
    if (is_admin() && current_user_can('blocked')) {
        wp_redirect(get_home_url() . '/blocked');
        die();
    }
});

add_filter('query_vars', function ($query_vars) {
    $query_vars[] = 'blocked';
    return $query_vars;
});

add_action('template_redirect', function () {
    $is_blocked = intval(get_query_var('blocked'));
    if ($is_blocked || current_user_can('blocked')) {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php esc_html_e('Blocked User', 'user-role-protector'); ?></title>

            <?php
            wp_head();
            ?>
        </head>

        <body>
            <h2 style="text-align: center"><?php esc_html_e('You are blocked', 'user-role-protector'); ?></h2>
            <?php
            wp_footer();
            ?>
        </body>

        </html>
<?php
        die();
    }
});
