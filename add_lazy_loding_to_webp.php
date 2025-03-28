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

/**
 * Add lazy loading via JavaScript for WebP images in the footer
 */
function lazy_load_footer_webp_with_js() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Target only images in the footer
        const footer = document.querySelector('footer');
        if (!footer) return;

        // Find all WebP images without 'loading=lazy'
        const webpImages = footer.querySelectorAll('img[src$=".webp"]:not([loading])');
        
        webpImages.forEach(img => {
            img.setAttribute('loading', 'lazy');
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'lazy_load_footer_webp_with_js', 100);
?>
