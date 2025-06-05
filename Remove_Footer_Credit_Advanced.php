<?php
/**
 * Plugin Name: Remove Footer Credit Advanced
 * Description: Completely remove any theme footer and replace it with admin-defined custom footer text.
 * Version: 1.0
 * Author: Mursalin Opurbo
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Add settings page
function rfc_add_admin_menu_adv() {
    add_options_page( 'Footer Credit Advanced', 'Footer Credit', 'manage_options', 'remove_footer_credit_advanced', 'rfc_options_page_adv' );
}
add_action( 'admin_menu', 'rfc_add_admin_menu_adv' );

// Register setting
function rfc_settings_init_adv() {
    register_setting( 'rfcSettingsAdv', 'rfc_footer_text_advanced' );
    add_settings_section( 'rfc_section_adv', __( 'Custom Footer Text', 'rfc' ), null, 'remove_footer_credit_advanced' );
    add_settings_field( 'rfc_field_adv', __( 'Footer Text', 'rfc' ), 'rfc_field_render_adv', 'remove_footer_credit_advanced', 'rfc_section_adv' );
}
add_action( 'admin_init', 'rfc_settings_init_adv' );

// Field render
function rfc_field_render_adv() {
    $value = get_option( 'rfc_footer_text_advanced', '' );
    echo '<textarea name="rfc_footer_text_advanced" rows="3" style="width:100%;">' . esc_textarea( $value ) . '</textarea>';
}

// Settings page
function rfc_options_page_adv() {
    ?>
    <div class="wrap">
        <h1>Footer Credit Settings (Advanced)</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'rfcSettingsAdv' );
            do_settings_sections( 'remove_footer_credit_advanced' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Replace theme footer with admin-defined footer
function rfc_replace_footer_with_custom() {
    $custom_text = wp_kses_post( get_option( 'rfc_footer_text_advanced', '' ) );

    // Remove default theme footer using JavaScript
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const footer = document.querySelector('footer');
            if (footer) footer.remove();
        });
    </script>";

    // Inject custom footer text
    if (!empty($custom_text)) {
        echo '<div class="custom-footer-credit" style="text-align:center;padding:20px;background:#f5f5f5;color:#333;font-size:14px;">' . $custom_text . '</div>';
    }
}
add_action( 'wp_footer', 'rfc_replace_footer_with_custom', 999 );
