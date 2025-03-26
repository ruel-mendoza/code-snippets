<?php
/**
 * Registers the visitor ID (VID) via AJAX.
 *
 * This function is called via AJAX to register a visitor ID stored in a cookie.
 * It checks for the presence of the VID, validates the nonce, and stores the VID
 * (or performs other actions) as needed.
 *
 * @return void
 */
function dc_register_vid() {
    // Verify the nonce to prevent CSRF attacks.
    check_ajax_referer( 'your_nonce_action', '_wpnonce' ); // Replace 'your_nonce_action' with the actual nonce action used in your JavaScript.

    // Check if the 'Visitor Details' cookie exists.
    if ( isset( $_COOKIE['Visitor Details'] ) ) {
        // Extract the VID from the cookie value.
        $visitor_details = sanitize_text_field( $_COOKIE['Visitor Details'] );
        preg_match( '/VID=([^;]+)/', $visitor_details, $matches );

        if ( isset( $matches[1] ) ) {
            $vid = sanitize_text_field( $matches[1] );

            // You can now process the $vid.
            // For example, you might want to:
            // 1. Store it in the user's meta data if they are logged in.
            // 2. Store it in a custom database table for anonymous users.
            // 3. Perform other tracking or analysis based on the VID.

            // Example: Logging the VID (for debugging purposes)
            error_log( 'Visitor ID registered: ' . $vid );

            // Example: Storing the VID in a transient (for anonymous users, expires after a while)
            set_transient( 'visitor_id_' . $vid, time(), DAY_IN_SECONDS ); // Store timestamp, can be used for tracking activity

            // Example: Storing the VID in user meta if logged in
            if ( is_user_logged_in() ) {
                $user_id = get_current_user_id();
                update_user_meta( $user_id, 'visitor_id', $vid );
            }

            // Send a success response back to the JavaScript.
            wp_send_json_success( 'VID registered successfully.' );

        } else {
            // VID not found in the cookie.
            wp_send_json_error( 'VID not found in cookie.' );
        }
    } else {
        // 'Visitor Details' cookie not found.
        wp_send_json_error( 'Visitor Details cookie not found.' );
    }

    // It's good practice to always exit after sending a JSON response.
    wp_die();
}
add_action( 'wp_ajax_dc_register_vid', 'dc_register_vid' );
add_action( 'wp_ajax_nopriv_dc_register_vid', 'dc_register_vid' ); // Allow for non-logged-in users as well.
