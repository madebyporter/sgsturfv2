<?php
/**
 * Resource builder class. Compiles the plugin generating generated javascript
 * and CSS output. Distributed between below and above the fold (depends on plugin optimization settings).
 *
 * The resource load is certified by the WP Rocket <https://wp-rocket.me> to ensure that all cache and resources
 * are working properly on all sites and uses the latest technologies.
 *
 * @author appscreo
 * @package EasySocialShareButtons
 * @since 3.0
 * @version 5.0
 * @modified 7.3
 */

class ESSB_Plugin_Assets {
        
    /**
     * Control the version strings
     */
    public $resource_version = ESSB3_VERSION;
    
    /**
     * Load all scripts in the head instead of footer
     */
    public $scripts_in_head = false;
    
    /**
     * Include the dynamic styles into the site footer (for optimization purpose)
     */
    public $inline_css_footer = false;
    
    public $js_async = false;
    public $js_defer = false;
    public $js_head = false;
       
    // javascript code
    public $js_code_head = array();
    public $js_code = array();
    public $js_code_noncachable = array();
    public $js_static = array();
    public $js_static_nonasync = array();
    public $js_static_footer = array();
    public $js_static_noasync_footer = array();
    
    public $js_social_apis = array();
    
    public $precompiled_css_queue = array();
    public $precompiled_js_queue = array();
    
    public $active_resources = array();
    
    private $inline_styles = '';    
    private $precompile_css = false;
    private $precompile_js = false;   
    private $precompile_css_loaded = false;    
    private $plugin_deactivate = false;
    private $precompiled_footer = false;
    
    /**
     * Add preloading option for the plugin styles
     */
    private $precompile_css_preload = false; 
    private $precompile_css_filename = '';
    
    private static $instance = null;
    
    /**
     * Prevent multiple class instances
     */
    public static function instance() {
        
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        
        return self::$instance;        
    } 
    
    
    /**
     * Generate all the events
     */
    function __construct() {        
        add_filter( 'body_class', array($this, 'flag_body_class' ));
        
        $this->inline_css_footer = essb_option_bool_value('load_css_footer');
        
        /**
         * Determine the state of the pre-compiled mode. Pre-compiled mode collects all styles
         * and scripts and load them below the fold. Action is the same that cache plugins
         * does to optimize page load.
         */
        $precompiled_mode = false;
        $precompiled_mode_css = false;
        $precompiled_mode_js = false;
        
        if (defined('ESSB3_PRECOMPILED_RESOURCE')) {
            $precompiled_mode_resources = essb_option_value('precompiled_mode');
            
            if ($precompiled_mode_resources == '' || $precompiled_mode_resources == 'css') {
                $precompiled_mode_css = true;
            }
            if ($precompiled_mode_resources == '' || $precompiled_mode_resources == 'js') {
                $precompiled_mode_js = true;
            }
            
            if ($precompiled_mode_css) {
                $this->inline_css_footer = true;
            }
            
            $precompiled_mode = true;
            
            $this->precompile_css = $precompiled_mode_css;
            $this->precompile_js = $precompiled_mode_js;
            
            $this->precompiled_footer = essb_option_bool_value('precompiled_footer');
            $this->precompile_css_preload = essb_option_bool_value('precompiled_preload_css');
            
        }
        
        /**
         * Generating the dynamic CSS code
         */
        if (!$precompiled_mode_css) {
            if ($this->inline_css_footer) {
                add_action('essb_rs_footer', array($this, 'generate_custom_css'), 997);
            }
            else {
                add_action('essb_rs_head_enqueue', array($this, 'generate_custom_css'));
            }
            add_action('essb_rs_footer', array($this, 'generate_custom_footer_css'), 998);
        }
        else {
            add_action('essb_rs_footer', array($this, 'generate_custom_css_precompiled'), 996);
        }
        
        /**
         * Generate scripts
         */
        add_action('essb_rs_head', array($this, 'generate_custom_js'));
        
        if (!$precompiled_mode_js) {
            add_action('essb_rs_footer', array($this, 'generate_custom_footer_js'), 998);
        }
        else {
            add_action('essb_rs_footer', array($this, 'generte_custom_js_precompiled'), 996);
        }
        
        // initalize resource builder options based on settings
        $this->js_head = essb_option_bool_value('scripts_in_head');
        $this->js_async = essb_option_bool_value('load_js_async');
        $this->js_defer = essb_option_bool_value('load_js_defer');
        
        // static CSS and javascripts sources enqueue
        add_action ( 'wp_enqueue_scripts', array ($this, 'check_optimized_load' ), 1 );
        add_action ( 'wp_enqueue_scripts', array ($this, 'register_front_assets' ), 10 );
        
        // load pre-compiled mode cache if exist
        if ($precompiled_mode_css) {
            add_action ( 'wp_enqueue_scripts', array ($this, 'register_precompile_styles' ), 11 );
        }
        
        add_action('wp_head', array($this, 'header'));
        add_action('wp_footer', array($this, 'footer'), 999);
        
        if (essb_option_bool_value('remove_ver_resource')) {
            $this->resource_version = '';
        }
    }
    
    /**
     * Add extra body classes based on plugin settings
     * 
     * @param {array} $classes
     * @return {array}
     */
    public function flag_body_class($classes) {
        $classes[] = 'essb-'.ESSB3_VERSION;
        
        return $classes;
    }
    
    /**
     * Check if reCAPTCHA script needs to be loaded
     */
    public function should_load_recaptcha() {
        $recaptcha = essb_option_bool_value('mail_recaptcha') && ! empty( essb_sanitize_option_value('mail_recaptcha_site') ) && ! empty( essb_sanitize_option_value('mail_recaptcha_secret') );
        
        if ($recaptcha && essb_sanitize_option_value('mail_function') == 'form') {
            return true;
        }
        else {
            return false;
        }
    }
    
    /**
     * Additional check if the settings are made to load resources only posts where plugin is running
     */
    public function check_optimized_load() {
        if ($this->is_optimized_deactivated()) {
            $this->deactivate_actions();
        }   
                
        $this->plugin_deactivate = essb_is_plugin_deactivated_on();
        
        /**
         * AMP Bind
         */
        if (class_exists('ESSBAmpSupport')) {
            if (function_exists('amp_is_request') && amp_is_request()) {
                $this->deactivate_actions();
                $this->plugin_deactivate = true;
            }
        }        
    }
    
    /**
     * Is plugin deactivated on the current loading content
     *
     * @return boolean
     */
    public function is_optimized_deactivated() {
        $r = false;
        
        if (essb_option_value('optimize_load') == 'selected') {
            $active_types = essb_option_value('display_in_types');
            $active_type = get_post_type();
            
            if ($active_type == '') {
                $active_type = 'post';
            }
            if (!is_array($active_types)) {
                $active_types = array();
            }
            
            if (!in_array($active_type, $active_types)) {
                $r = true;
            }
            
            // deactivating plugin resources on list pages if the option is not activated
            if (is_archive() || is_front_page() || is_search() || is_tag() || is_post_type_archive() || is_home()) {
                if (!in_array('all_lists', $active_types)) {
                    $r = true;
                }
            }
            
            /**
             * Frontpage check
             */
            if (is_front_page() && !in_array('homepage', $active_types)) {
                $r = true;
            }
        }
        else if (essb_option_value('optimize_load') == 'post') {
            $optimize_load_id = essb_sanitize_option_value('optimize_load_id');
            if ($optimize_load_id != '') {
                $excule_from = explode(',', $optimize_load_id);
                
                $excule_from = array_map('trim', $excule_from);
                if (!in_array(get_the_ID(), $excule_from, false)) {
                    $r = true;
                }
            }
        }
        
        /**
         * Supporting user deactivation of the resource load using a filter call
         */
        if (has_filter('essb_resource_builder_deactivate')) {
            $r = apply_filters('essb_resource_builder_deactivate', $r);
        }
        
        return $r;
    }
    
    /**
     * Generate code that is required to run on the page header
     */
    public function header() {
        do_action('essb_rs_head');
    }
    
    /**
     * Generate code that is to run on the footer
     */
    public function footer() {
        
        // since version 4 we introduce new mail form code added here
        /**
         * @since 8.3 A new loading key mail_form replacing the old mail. This is due to optimizations 
         * where the mail styles are no longer part of the mail CSS.
         */
        if ($this->is_activated('mail_form')) {
            essb_depend_load_function('essb_rs_mailform_build', 'lib/core/resource-snippets/essb_rs_code_mailform.php');
        }
        
        do_action('essb_rs_footer');
    }
    
    
    /**
     * Cloning disabled
     */
    public function __clone() {
    }
    
    /**
     * Serialization disabled
     */
    public function __sleep() {
    }
    
    /**
     * De-serialization disabled
     */
    public function __wakeup() {
    }
    
    
    /**
     * Deactivate plugin actions that are responsible for the optimized resource loading
     */
    public function deactivate_actions() {
        remove_action('wp_head', array($this, 'header'));
        remove_action('wp_footer', array($this, 'footer'), 999);
        remove_action('wp_enqueue_scripts', array ($this, 'register_front_assets' ), 10 );
    }
    
    /**
     * @param unknown $key
     */
    public function activate_resource($key = '') {
        ESSB_Static_CSS_Loader::$active_resources[$key] = true;
    }
    
    /**
     * @param unknown $key
     * @return boolean
     */
    public function is_activated($key = '') {
        return isset(ESSB_Static_CSS_Loader::$active_resources[$key]);
    }
    
    /**
     * Remove from the list script key
     * 
     * @param string $key
     */
    public function remove_static_js_resource($key) {
        if (isset($this->js_static_nonasync[$key])) {
            unset ($this->js_static_nonasync[$key]);
        }
        
        if (isset($this->js_static[$key])) {
            unset ($this->js_static[$key]);
        }
    }
    
    /**
     * add_static_resource
     *
     * @param unknown_type $file_with_path
     * @param unknown_type $key
     * @param unknown_type $type
     */
    public function add_static_resource($file_with_path, $key, $type = '', $noasync = false) {
        if ($type == 'css') {
            ESSB_Static_CSS_Loader::register_header_style($key, $file_with_path);
        }
        if ($type == 'js') {
            if ($noasync) {
                $this->js_static_nonasync[$key] = $file_with_path;
            }
            else {
                $this->js_static[$key] = $file_with_path;
            }
        }
    }
    
    public function add_static_resource_footer($file_with_path, $key, $type = '', $noasync = false) {
        if ($type == 'css') {
            ESSB_Static_CSS_Loader::register_footer_style($key, $file_with_path);
        }
        if ($type == 'js') {
            if ($noasync) {
                // @since 3.0.4 - double check for the twice counter load script
                if (!isset($this->js_static_nonasync[$key])) {
                    $this->js_static_noasync_footer[$key] = $file_with_path;
                }
            }
            else {
                // @since 3.0.4 - double check for the twice counter load script
                if (!isset($this->js_static[$key])) {
                    $this->js_static_footer[$key] = $file_with_path;
                }
            }
        }
    }
    
    /**
     * @param unknown $file_with_path
     * @param unknown $key
     */
    public function add_static_footer_css($file_with_path, $key) {
        ESSB_Static_CSS_Loader::register_footer_style($key, $file_with_path);
    }
    
    /**
     * add_css
     *
     * @param string $code
     * @param string $key
     * @param string $location
     */
    public function add_css($code, $key = '', $location = 'head') {
        if ($key != '') {
            ESSB_Dynamic_CSS_Builder::register_dynamic_code($key, $code, $location == 'head' ? 'header' : 'footer');
        }
        else {
            ESSB_Dynamic_CSS_Builder::register_static_code($code, $location == 'head' ? 'header' : 'footer');
        }
    }
    
    public function add_social_api($key) {
        $this->js_social_apis[$key] = 'loaded';
    }
    
    /**
     * add_js
     *
     * @param string $code
     * @param bool $minify
     * @param string $key
     * @param string $position
     */
    public function add_js($code, $minify = false, $key = '', $position = 'footer', $noncachble = false) {
        if ($minify) {
            $code = trim(preg_replace('/\s+/', ' ', $code));
        }
        
        if ($key != '') {
            if ($position == 'footer') {
                if ($noncachble) {
                    $this->js_code_noncachable[$key] = $code;
                }
                else {
                    $this->js_code[$key] = $code;
                }
            }
            else {
                $this->js_code_head[$key] = $code;
            }
        }
        else {
            if ($position == 'footer') {
                if ($noncachble) {
                    $this->js_code_noncachable[] = $code;
                }
                else {
                    $this->js_code[] = $code;
                }
            }
            else {
                $this->js_code_head[] = $code;
            }
        }
    }
    
    /**
     * Enqueue all front CSS and javascript files
     */
    function enqueue_style_single_css($key, $file, $version) {
        if (!$this->precompile_css) {
            wp_enqueue_style ( $key, $file, false, $this->resource_version, 'all' );
            
            if ($key == $this->core_style_id() && $this->inline_styles != '') {
                wp_add_inline_style($key, $this->inline_styles);
            }
        }
        else {
            $this->precompiled_css_queue[$key] = $file;
        }
    }
    
    /**
     * @return string
     */
    function core_style_id() {
        return 'easy-social-share-buttons';
    }
    
    /**
     * @return string
     */
    function core_script_id() {
        return 'easy-social-share-buttons-core';
    }
    
    /**
     * @return boolean
     */
    function is_static_cache_running() {
        return class_exists('ESSBStaticCache');
    }
    
    /**
     * Check if the core style of plugin is loaded to determine the custom resource
     * load mode
     *
     * @return boolean
     */
    function is_core_style_loaded() {
        $r = false;
        
        /**
         * @since 8.0 replace use_stylebuilder with dont_load_css
         */
        if (!essb_option_bool_value('dont_load_css') && !$this->precompile_css && !$this->inline_css_footer) {
            $r = ESSB_Static_CSS_Loader::style_loaded($this->core_style_id());
        }
        
        return $r;
    }
    
    /**
     * @param unknown $code
     * @return unknown
     */
    function sanitize_css_output($code) {
        return wp_strip_all_tags($code);
    }
    
    /**
     * Checking if the plugin core script is loaded with the enqueue functions
     * @return boolean
     */
    function is_core_script_loaded() {
        $r = false;
        
        if (!$this->precompile_js && !$this->js_async && !$this->js_defer) {
            foreach ($this->js_static as $key => $file) {
                if ($key === $this->core_script_id()) {
                    $r = true;
                }
            }
        }
        
        return $r;
    }
    
    /**
     * Add to the header pre-compiled cached styles
     */
    function register_precompile_styles() {
        if ($this->plugin_deactivate || $this->precompiled_footer) {
            return;
        }
        
        $cache_key = 'essb-precompiled'.(essb_is_mobile() ? '-mobile': '');
        
        if (essb_option_bool_value('precompiled_unique')) {
            $cache_key .= ESSBPrecompiledResources::get_unique_identifier();
        }
        
        if (essb_option_bool_value('precompiled_post')) {
            $post_key = essb_get_page_id();
            if ($post_key) {
                $cache_key .= '-' . $post_key;
            }
        }
        
        $cached_data = ESSBPrecompiledResources::get_resource($cache_key, 'css');
        
        if ($cached_data != '') {
            if ($this->precompile_css_preload) {
                $this->precompile_css_filename = $cached_data;
                add_action('wp_head', array($this, 'preload_css_styles'));
            }
            else {
                wp_enqueue_style ( 'essb-compiledcache', $cached_data, false, $this->resource_version, 'all' );
                $this->precompile_css_loaded = true;
            }
        }
    }
    
    /**
     * Preloading styles
     */
    public function preload_css_styles() {
        if ($this->precompile_css_filename != '') {
            essb_manual_preload_css_file($this->precompile_css_filename);
        }
    }
    
    /**
     * Register plugin front assets
     */
    function register_front_assets() {
        if ($this->plugin_deactivate) {
            return;
        }
        
        /**
         * Loading inline the custom plugin styles. They will load based on the settings for optimization of the user
         */
        do_action('essb_rs_head_enqueue');
        
        $load_in_footer = ($this->js_head) ? false : true;
        
        /**
         * @since 8.0 replace use_stylebuilder with dont_load_css
         */
        if (!essb_option_bool_value('dont_load_css')) {
            // enqueue all css registered files
            
            /**
             * Registering static CSS styles. The core style ID will be loaded
             * last in the queue to prevent visual issues
             */
            $loading_core = array('active' => false, 'key' => '', 'file' => '');
            foreach (ESSB_Static_CSS_Loader::get('header') as $key => $file) {
                if ($key == $this->core_style_id()) {
                    $loading_core['active'] = true;
                    $loading_core['key'] = $key;
                    $loading_core['file'] = $file;
                    continue;
                }
                
                if ($key == 'easy-social-share-buttons-profles') {
                    if (!essb_is_module_deactivated_on('profiles')) {
                        $this->enqueue_style_single_css($key, $file, $this->resource_version);
                    }
                }
                else if ($key == 'easy-social-share-buttons-nativeskinned' || $key == 'essb-fontawsome' || $key == 'essb-native-privacy') {
                    if (!essb_is_module_deactivated_on('native')) {
                        $this->enqueue_style_single_css($key, $file, $this->resource_version);
                    }
                }
                else {
                    $this->enqueue_style_single_css($key, $file, $this->resource_version);
                }
            }
            
            if ($loading_core['active']) {
                $this->enqueue_style_single_css($loading_core['key'], $loading_core['file'], $this->resource_version);
            }
        }
        else {
            // using build in builder styles
            $user_styles = essb_option_value('stylebuilder_css');
            if (!is_array($user_styles)) {
                $user_styles = array();
            }
            
            if (count($user_styles) > 0) {
                $this->enqueue_style_single_css('essb-userstyles', content_url('easysocialsharebuttons-assets/essb-userselection.min.css'), $this->resource_version);
            }
        }
        
        foreach ($this->js_static_nonasync as $key => $file) {
            wp_enqueue_script ( $key, $file, array ( 'jquery' ), $this->resource_version, $load_in_footer );
        }
        
        // load scripts when no async or deferred is selected
        if (!$this->js_async && !$this->js_defer) {
            foreach ($this->js_static as $key => $file) {
                if (!$this->precompile_js) {
                    wp_enqueue_script ( $key, $file, array ( 'jquery' ), $this->resource_version, $load_in_footer );
                }
                else {
                    $this->precompiled_js_queue[$key] = $file;
                }
            }
        }
        
        /**
         * reCAPTCHA
         */
        if ($this->should_load_recaptcha()) {
            essb_depend_load_function('essb_rs_mailform_build', 'lib/core/resource-snippets/essb_rs_code_mailform.php');
            essb_register_mail_recaptcha();
        }
    }
    
    
    /**
     * Compile the custom CSS generated by addition changes inside plugin settings
     *
     */
    function generate_custom_css() {       
        if ($this->plugin_deactivate) {
            return;
        }
        
        $cache_slug = 'essb-css-head';
        
        if (defined ( 'ESSB3_CACHE_ACTIVE_RESOURCE' )) {
            global $post;
            
            if (isset ( $post )) {
                $cache_key = $cache_slug . $post->ID;
                
                if (essb_dynamic_cache_load_css ( $cache_key )) {
                    return;
                }
            }
        }
        
        
        $css_code = '';
        
        $css_code = apply_filters ( 'essb_css_buffer_head', $css_code );
        $css_code .= ESSB_Dynamic_CSS_Builder::compile_header();
        
        $css_code = trim ( preg_replace ( '/\s+/', ' ', $css_code ) );
        
        if ($css_code != '') {
            /**
             * Checking the current cache state. If the internal cache runs than the plugin
             * will store the data in a static resource
             */
            if (defined ( 'ESSB3_CACHE_ACTIVE_RESOURCE' )) {
                if (isset ( $post )) {
                    $cache_key = $cache_slug . $post->ID;
                    ESSBDynamicCache::put_resource ( $cache_key, $css_code, 'css' );
                    essb_dynamic_cache_load_css ( $cache_key );
                    return;
                }
            }
            
            if ($this->is_core_style_loaded()) {
                $this->inline_styles = ESSB_Dynamic_CSS_Builder::sanitize_css_output($css_code);
            }
            else {
                echo '<style type="text/css" id="easy-social-share-buttons-inline-css" media="all">'.ESSB_Dynamic_CSS_Builder::sanitize_css_output($css_code).'</style>';
            }
        }
    }
    
    /**
     * Generate additional CSS code below the fold (from the page footer)
     */
    function generate_custom_footer_css() {
        global $post;
        
        if ($this->plugin_deactivate) {
            return;
        }
        
        foreach ( ESSB_Static_CSS_Loader::get('footer') as $key => $file ) {
            printf ( '<link rel="stylesheet" id="%1$s" href="%2$s" type="text/css" media="all" />', $key, esc_url($file) );
        }
        
        
        $cache_slug = 'essb-css-footer';
        if (isset ( $post )) {
            if (defined ( 'ESSB3_CACHE_ACTIVE_RESOURCE' )) {
                $cache_key = $cache_slug . $post->ID;
                
                if (essb_dynamic_cache_load_css ( $cache_key )) {
                    return;
                }
            }
        }
        
        $css_code = '';
                
        $css_code .= apply_filters ( 'essb_css_buffer_footer', $css_code );
        $css_code .= ESSB_Dynamic_CSS_Builder::compile_footer();
        
        $css_code = trim ( preg_replace ( '/\s+/', ' ', $css_code ) );
        if ($css_code != '') {
            if (isset ( $post )) {
                
                if (defined ( 'ESSB3_CACHE_ACTIVE_RESOURCE' )) {
                    $cache_key = $cache_slug . $post->ID;
                    
                    ESSBDynamicCache::put_resource ( $cache_key, $css_code, 'css' );
                    
                    essb_dynamic_cache_load_css ( $cache_key );
                    return;
                }
            }
            echo '<style type="text/css" id="easy-social-share-buttons-footer-inline-css" media="all">'.$this->sanitize_css_output($css_code).'</style>';
        }
        
    }
    
    /**
     * Generate a pre-compiled static plugin resources inside a file. The compile action
     * will execute only if the cache is not present or not existing.
     */
    function generate_custom_css_precompiled() {
        if ($this->plugin_deactivate) {
            return;
        }
        
        if ($this->precompile_css_loaded) {
            return;
        }
        
        $cache_key = 'essb-precompiled'.(essb_is_mobile() ? '-mobile': '');
        
        if (essb_option_bool_value('precompiled_unique')) {
            $cache_key .= ESSBPrecompiledResources::get_unique_identifier();
        }
        
        if (essb_option_bool_value('precompiled_post')) {
            $post_key = essb_get_page_id();
            if ($post_key) {
                $cache_key .= '-' . $post_key;
            }
        }
        
        $cached_data = ESSBPrecompiledResources::get_resource($cache_key, 'css');
        
        if ($cached_data != '') {
            if ($this->precompile_css_preload) {
                essb_manual_preload_css_file($cached_data);
                return;
            }
            else {
                echo "<link rel='stylesheet' id='essb-compiledcache-css'  href='".esc_url($cached_data)."' type='text/css' media='all' />";
                return;
            }
        }
        
        /**
         * @since 8.7
         * @var array $loaded_urls Prevent loading duplicated files in the cache
         */
        $loaded_urls = array();
        
        $static_content = array();        
        $styles = array();
               
        $css_code = '';
        $css_code = apply_filters ( 'essb_css_buffer_head', $css_code );
        $css_code .= ESSB_Dynamic_CSS_Builder::compile_header();
        
        $css_code = trim ( preg_replace ( '/\s+/', ' ', $css_code ) );
        $styles[] = $css_code;
                
        // parsing inlinde enqueue styles
        $current_site_url = get_site_url();
        foreach ($this->precompiled_css_queue as $key => $file) {
            
            if (in_array($file, $loaded_urls)) {
                continue;
            }
            
            $relative_path = ESSBPrecompiledResources::get_asset_relative_path($current_site_url, $file);
            $css_code = file_get_contents( ABSPATH . $relative_path );
            $css_code = trim(preg_replace('/\s+/', ' ', $css_code));
            
            if ($key == "essb-social-image-share") {
                $css_code = str_replace('../', ESSB3_PLUGIN_URL . '/lib/modules/social-image-share/assets/', $css_code);
            }
            if ($key == "easy-social-share-buttons-profiles" || $key == "easy-social-share-buttons-display-methods" || $key == 'easy-social-share-buttons') {
                $css_code = str_replace('../', ESSB3_PLUGIN_URL . '/assets/', $css_code);
            }
            if ($key == "essb-social-followers-counter") {
                $css_code = str_replace('../', ESSB3_PLUGIN_URL . '/lib/modules/social-followers-counter/assets/', $css_code);
            }
            
            $styles[] = $css_code;
            
            $static_content[$key] = $file;
            $loaded_urls[] = $file;
        }
        
        foreach (ESSB_Static_CSS_Loader::get('footer') as $key => $file) {
            if (in_array($file, $loaded_urls)) {
                continue;
            }            
            
            $relative_path = ESSBPrecompiledResources::get_asset_relative_path($current_site_url, $file);
            $css_code = file_get_contents( ABSPATH . $relative_path );
            $css_code = trim(preg_replace('/\s+/', ' ', $css_code));
            
            if ($key == "essb-social-image-share") {
                $css_code = str_replace('../', ESSB3_PLUGIN_URL . '/lib/modules/social-image-share/assets/', $css_code);
            }
            if ($key == "easy-social-share-buttons-profiles" || $key == "easy-social-share-buttons-display-methods" || $key == 'easy-social-share-buttons') {
                $css_code = str_replace('../', ESSB3_PLUGIN_URL . '/assets/', $css_code);
            }
            if ($key == "essb-social-followers-counter") {
                $css_code = str_replace('../', ESSB3_PLUGIN_URL . '/lib/modules/social-followers-counter/assets/', $css_code);
            }
            
            $styles[] = $css_code;
            
            $static_content[$key] = $file;
            $loaded_urls[] = $file;
        }
        
        $css_code = '';
        
        $css_code .= apply_filters ( 'essb_css_buffer_footer', $css_code );
        $css_code .= ESSB_Dynamic_CSS_Builder::compile_footer();
        
        $css_code = trim ( preg_replace ( '/\s+/', ' ', $css_code ) );
        $styles[] = $css_code;
        
        $toc = array();
        
        foreach ( $static_content as $handle => $item_content )
            $toc[] = sprintf( ' - %s', $handle.'-'.$item_content );
            if (essb_option_bool_value('precompiled_post')) {
                $post_key = essb_get_page_id();
                if ($post_key) {
                    $toc[] = ' - Post ID: ' . $post_key;
                }
            }
            
            $styles[] = sprintf( "\n\n\n/* TOC:\n%s\n*/", implode( "\n", $toc ) );
            
            ESSBPrecompiledResources::put_resource($cache_key, implode(' ', $styles), 'css');
            
            $cached_data = ESSBPrecompiledResources::get_resource($cache_key, 'css');
            
            if ($cached_data != '') {
                if ($this->precompile_css_preload) {
                    essb_manual_preload_css_file($cached_data);
                    return;
                }
                else {
                    echo "<link rel='stylesheet' id='essb-compiledcache-css' href='".esc_url($cached_data)."' type='text/css' media='all' />";
                    return;
                }
            }
    }
    
    
    /**
     * generte_custom_js_precompiled
     *
     * Generate custom javascript code in head of page that will not be cached
     */
    
    function generte_custom_js_precompiled() {
        if ($this->plugin_deactivate) {
            return;
        }
        
        // -- loading non cachble and noasync code first
        if (count($this->js_social_apis) > 0) {
            if (!essb_is_module_deactivated_on('native')) {
                essb_depend_load_function('essb_load_social_api_code', 'lib/core/resource-snippets/essb-rb-socialapi.php');
                foreach ($this->js_social_apis as $network => $loaded) {
                    essb_load_social_api_code($network);
                }
            }
        }
        
        if (count($this->js_static_noasync_footer)) {
            foreach ($this->js_static_noasync_footer as $key => $file) {
                $this->manual_script_load($key, $file);
            }
        }
        
        // loading in precompiled cache mode
        $cache_key = "essb-precompiled".(essb_is_mobile() ? "-mobile": "");
        if (essb_option_bool_value('precompiled_unique')) {
            $cache_key .= ESSBPrecompiledResources::get_unique_identifier();
        }
        
        if (essb_option_bool_value('precompiled_post')) {
            $post_key = essb_get_page_id();
            if ($post_key) {
                $cache_key .= '-' . $post_key;
            }
        }
        
        $cached_data = ESSBPrecompiledResources::get_resource($cache_key, 'js');
        
        /**
         * Check for already stored cache. If so than the code will generate the static link to the file
         * and stop further loading
         */
        if ($cached_data != '') {
            echo "<script type='text/javascript' src='".esc_url($cached_data)."' async></script>";
            return;
        }
        
        $static_content = array();
        $scripts = array();
        $current_site_url = get_site_url();
        
        $scripts[] = implode(" ", $this->js_code);
        
        if (count($this->js_static) > 0) {
            foreach ($this->js_static as $key => $file) {
                $relative_path = ESSBPrecompiledResources::get_asset_relative_path($current_site_url, $file);
                $code = file_get_contents( ABSPATH . $relative_path );
                
                $scripts[] = $code;
                
                $static_content[$key] = $file;
            }
        }
        
        if (count($this->js_static_footer)) {
            foreach ($this->js_static_footer as $key => $file) {
                $relative_path = ESSBPrecompiledResources::get_asset_relative_path($current_site_url, $file);
                $code = file_get_contents( ABSPATH . $relative_path );
                
                $scripts[] = $code;
                
                $static_content[$key] = $file;
            }
        }
        
        $js_code = '';
        $js_code = apply_filters('essb_js_buffer_footer', $js_code);
        $scripts[] = $js_code;
        
        $toc = array();
        
        foreach ( $static_content as $handle => $item_content )
            $toc[] = sprintf( ' - %s', $handle.'-'.$item_content );
        
            if (essb_option_bool_value('precompiled_post')) {
                $post_key = essb_get_page_id();
                if ($post_key) {
                    $toc[] = ' - Post ID: ' . $post_key;
                }
            }
            
            $scripts[] = sprintf( "\n\n\n/* TOC:\n%s\n*/", implode( "\n", $toc ) );
            
            ESSBPrecompiledResources::put_resource($cache_key, implode(' ', $scripts), 'js');
            
            $cached_data = ESSBPrecompiledResources::get_resource($cache_key, 'js');
            
            if ($cached_data != '') {
                echo "<script type='text/javascript' src='".esc_url($cached_data)."' async></script>";
            }
    }
    
    /**
     * Generate the plugin setup data that will load the custom setup scripts. They are critical and non-cached
     *
     */
    function generate_custom_js() {
        if ($this->plugin_deactivate) {
            return;
        }
        
        $js_code = '';
        
        if (count($this->js_code_head) > 0) {
            $js_code = '';
            foreach ($this->js_code_head as $code) {
                $js_code .= $code;
            }
        }
        $js_code = apply_filters('essb_js_buffer_head', $js_code);
        
        if ($this->is_core_script_loaded() && $js_code != '' && !$this->is_static_cache_running()) {
            wp_add_inline_script($this->core_script_id(), $js_code);
        }
        else if ($js_code != '') {
            echo "\n";
            echo sprintf('<script type="text/javascript">%1$s</script>', $js_code);
        }
    }
    
    /**
     * Additional footer scripts that will be loaded
     */
    function generate_custom_footer_js() {
        global $post;
        
        /**
         * Validating plugin runtime. If plugin is deactivated we will not continue
         * to run anymore
         */
        if ($this->plugin_deactivate) {
            return;
        }
        
        $cache_slug = "essb-js-footer";
        if (count($this->js_social_apis) > 0) {
            if (!essb_is_module_deactivated_on('native')) {
                essb_depend_load_function('essb_load_social_api_code', 'lib/core/resource-snippets/essb-rb-socialapi.php');
                foreach ($this->js_social_apis as $network => $loaded) {
                    essb_load_social_api_code($network);
                }
            }
        }
        
        /**
         * Optimized script include as async or deferred (if active from control panel)
         */
        if (count($this->js_static) > 0) {
            if ($this->js_defer || $this->js_async) {
                essb_load_static_script($this->js_static, $this->js_async);
            }
        }
        
        /**
         * Loading additional scripts that are included on the runtime process
         */
        if (count($this->js_static_footer)) {
            if ($this->js_defer || $this->js_async) {
                essb_load_static_script($this->js_static_footer, $this->js_async);
            }
            else {
                foreach ($this->js_static_footer as $key => $file) {
                    $this->manual_script_load($key, $file);
                }
            }
        }
        
        /**
         * Critical non-async scripts (async may prevent plugin from work)
         */
        if (count($this->js_static_noasync_footer)) {
            foreach ($this->js_static_noasync_footer as $key => $file) {
                $this->manual_script_load($key, $file);
            }
        }
        
        if (count($this->js_code_noncachable)) {
            echo implode(" ", $this->js_code_noncachable);
        }
        
        // dynamic footer javascript that can be cached
        $cache_slug = "essb-js-footer";
        
        if (isset($post)) {
            if (defined('ESSB3_CACHE_ACTIVE_RESOURCE')) {
                $cache_key = $cache_slug.$post->ID;
                
                if (essb_dynamic_cache_load_js($cache_key)) { return; }
            }
        }
        
        
        $js_code = '';
        foreach ($this->js_code as $single) {
            $js_code .= $single;
        }
        $js_code = apply_filters('essb_js_buffer_footer', $js_code);
        if (isset($post)) {
            if (defined('ESSB3_CACHE_ACTIVE_RESOURCE')) {
                $cache_key = $cache_slug.$post->ID;
                
                ESSBDynamicCache::put_resource($cache_key, $js_code, 'js');
                
                essb_dynamic_cache_load_js($cache_key);
                return;
            }
        }
        echo '<script type="text/javascript">'.$js_code.'</script>';
        
    }
    
    /**
     * Function manually enqueue script used by plugin
     * @param unknown_type $key
     * @param unknown_type $file
     */
    public function manual_script_load($key, $file) {
        $ver_string = "";
        
        if (!empty($this->resource_version)) {
            $ver_string = "?ver=".$this->resource_version;
        }
        
        essb_manual_script_load($key, $file, $ver_string);
    }
}

/** static called functions for resource generation **/
if (!function_exists('essb_manual_preload_css_file')) {
    function essb_manual_preload_css_file($url = '') {
        echo '<link rel="preload" href="'.esc_url($url).'" as="style" onload="this.rel=\'stylesheet\'">';
    }
}


if (!function_exists('essb_manual_script_load')) {
    function essb_manual_script_load($key, $file, $ver_string = '') {
        echo '<script type="text/javascript" src="'.$file.$ver_string.'"></script>';
    }
}

if (!function_exists('essb_dynamic_cache_load_css')) {
    function essb_dynamic_cache_load_css($cache_key = '') {
        $cached_data = ESSBDynamicCache::get_resource($cache_key, 'css');
        
        if ($cached_data != '') {
            echo "<link rel='stylesheet' id='".esc_attr($cache_key)."' href='".esc_url($cached_data)."' type='text/css' media='all' />";
            return true;
        }
        else {
            return false;
        }
        
    }
}

if (!function_exists('essb_dynamic_cache_load_js')) {
    function essb_dynamic_cache_load_js($cache_key) {
        $cached_data = ESSBDynamicCache::get_resource($cache_key, 'js');
        
        if ($cached_data != '') {
            echo "<script type='text/javascript' src='".esc_url($cached_data)."' defer></script>";
            return true;
        }
        else {
            return false;
        }
    }
}

if (!function_exists('essb_load_static_script')) {
    function essb_load_static_script($list, $async) {
        $result = '';
        $load_mode = ($async) ? "po.async=true;" : "po.defer=true;";
        
        foreach ($list as $key => $file) {
            $result .= ('(function() { var po = document.createElement(\'script\'); po.type = \'text/javascript\'; '.$load_mode.'; po.src = \''.esc_url($file).'\'; var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s); })();');
        }
        
        if ($result != '') {
            echo '<script type="text/javascript">'.$result.'</script>';
        }
    }
}