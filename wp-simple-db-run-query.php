<?php

/**
 * Plugin Name: Simple database query runner
 * Description: Query your database tables
 * Author: florentroques
 */

if (!defined('ABSPATH')) exit;

define('WSDRQ_PLUGIN_SLUG', 'wp-simple-db-run-query');

add_action('admin_enqueue_scripts', 'wsdrq_add_bootstrap_css');

function add_bootstrap_css_db_run_query()
{
    global $pagenow;

    if (!(
        ($pagenow == 'admin.php') &&
        (isset($_GET['page']) && $_GET['page'] == WSDRQ_PLUGIN_SLUG)
    )) {
        return;
    }

    wp_enqueue_style('wsdrq_bootstrap-styles', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
}


add_action('admin_menu', 'wsdrq_setup_menu_plugin');

function wsdrq_setup_menu_plugin()
{
    add_menu_page(
        'Run query',
        'Run query',
        'manage_options',
        WSDRQ_PLUGIN_SLUG,
        'wp_simple_db_run_query',
        '',
        3.2
    );
}

function wp_simple_db_run_query()
{
    global $wpdb;

    $sql = '';

    if (isset($_POST['sql_command'])) {
        $sql = stripslashes_deep($_POST['sql_command']);

        $result = $wpdb->query($sql);

        if ($result === false) {
            echo '<div class="alert alert-danger" role="alert">';
            echo 'Error: ' . $sql . ' ' . $wpdb->last_error;
            echo '</div>';
        }
    }

    echo '<h1>SQL command</h1>';
    echo '<form action="" method="post">';
    echo '<div class="mb-3">';
    echo '<textarea name="sql_command" id="sql_command" cols="30" rows="10">' . $sql . '</textarea>';
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary">Run</button>';
    echo '</form>';
?>
<script>
    jQuery(document).ready(function($) {
        $('textarea').on('input', function() {
            // Keep '' instead of 0, 0 will reduce the textarea size when there is only one line of text
            $(this).height('');
            $(this).height(this.scrollHeight);
        });
    });
</script>
<?php
}
