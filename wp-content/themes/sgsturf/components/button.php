<?php
function custom_button_shortcode($atts) {
    $atts = shortcode_atts(array(
        'link' => '#',
        'class' => '',
        'label' => 'Button'
    ), $atts);

    $custom_classes = isset($atts['custom_class']) ? sanitize_html_class($atts['custom_class']) : '';

    $combined_classes = trim($atts['class'] . ' ' . $custom_classes);

    ob_start();
    ?>

    <a href="<?php echo esc_url($atts['link']); ?>" class="button <?php echo $combined_classes; ?>">
        <span class="button-label"><?php echo esc_html($atts['label']); ?></span>
        <span class="button-arrow">
            <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18.1978 8.94987L12.2806 15.029L11.3766 16L9.48636 14.058L10.4315 13.1293L14.0886 9.32981H2.17207H0.857143V6.62797H2.17207H14.0886L10.4315 2.87071L9.48636 1.89974L11.3766 0L12.2806 0.970976L18.1978 7.05013L19.1429 7.97889L18.1978 8.94987Z" fill="#242423"/>
            </svg>
        </span>
    </a>

    <?php
    return ob_get_clean();
}
