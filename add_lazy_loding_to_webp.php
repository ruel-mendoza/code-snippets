<?php 
// Add lazyloading on webp image
function add_lazy_loading_to_webp( $html ) {
    // Extract the src attribute from the <img> tag
    if ( preg_match( '/src=["\']([^"\']+\.webp)["\']/i', $html, $matches ) || preg_match( '/src=["\']([^"\']+\.png)["\']/i', $html, $matches ) ) {
        // Add lazy loading if not already present
        if ( strpos( $html, 'loading=' ) === false ) {
            $html = str_replace( '<img', '<img loading="lazy"', $html );
        }
    }
    return $html;
}
add_filter( 'wp_get_attachment_image', 'add_lazy_loading_to_webp' );
?>
