<?php
/**
 * View template for the Reset Optimization State (ROS) engine panel.
 */
defined( 'ABSPATH' ) || die();

global $wpdb;

// Live Source Data Extraction
$batches    = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->options WHERE option_name LIKE 'imagify_optimize_media_batch_%'" );
$transients = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->options WHERE option_name LIKE '_transient_imagify_%'" );
$as_jobs    = 0;

if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}actionscheduler_actions'" ) ) {
    $as_jobs = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}actionscheduler_actions WHERE hook IN ('imagify_optimize_media', 'imagify_convert_next_gen') AND status IN ('pending', 'in-progress')" );
}

$reset_url = wp_nonce_url( admin_url( 'admin-post.php?action=imagify_ros_reset' ), 'imagify_ros_action' );
?>

<div class="card" style="margin-top: 20px; padding: 20px; border-left: 4px solid #00bbcf;">
    <h2 style="margin-top: 0; color: #00bbcf;">Reset Optimization State Engine</h2>
    <p>Monitor and terminate stuck queues or background processes in real time.</p>
    
    <table class="widefat fixed" style="margin-bottom: 20px; max-width: 600px;">
        <thead>
            <tr>
                <th>Resource Matrix</th>
                <th>Active Queue Count</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Media Batches</strong> (In-flight DB optimization blocks)</td>
                <td><mark><?php echo (int) $batches; ?></mark></td>
            </tr>
            <tr>
                <td><strong>Background Actions</strong> (Action Scheduler items)</td>
                <td><mark><?php echo (int) $as_jobs; ?></mark></td>
            </tr>
            <tr>
                <td><strong>Temporary Transients</strong> (Cached application logs/states)</td>
                <td><mark><?php echo (int) $transients; ?></mark></td>
            </tr>
        </tbody>
    </table>

    <a href="<?php echo esc_url( $reset_url ); ?>" class="button button-primary" style="background: #00bbcf; border-color: #008fa0;" onclick="return confirm('Force kill all current optimization tasks and clear internal data?');">
        Clear & Reset All States
    </a>
</div>
