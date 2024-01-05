<?php

/**
 * Plugin Name: Database run query
 * Description: Query your database tables
 * Author: florentroques
 */

if (!defined('ABSPATH')) exit;

add_action('admin_enqueue_scripts', 'add_bootstrap_css_db_run_query');

function add_bootstrap_css_db_run_query()
{
    global $pagenow;

    if (!(
        ($pagenow == 'admin.php') &&
        (isset($_GET['page']) && $_GET['page'] == 'db-run-query')
    )) {
        return;
    }

    wp_enqueue_style('db_run_query_bootstrap-styles', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
}


add_action('admin_menu', 'setup_menu_plugin_db_run_query');

function setup_menu_plugin_db_run_query()
{
    add_menu_page(
        'Run query',
        'Run query',
        'manage_options',
        'db-run-query',
        'db_run_query',
        '',
        3.2
    );
}

function db_run_query()
{
    global $wpdb;

    $sql = '';

    if (isset($_POST['sql_command'])) {
        $sql = $_POST['sql_command'];

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
            $(this).height(this.scrollHeight);
        });
        $('textarea').trigger('input');
    });
</script>
<?php
}
