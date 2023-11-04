<?php
/**
 * Manage plugin activations
 *
 * @author appscreo
 * @since 4.2
 * @package EasySocialShareButtons
 */

class ESSBActivationManager {
    private static $option = 'essb4-activation';
    private static $api = 'https://api.socialsharingplugin.com/';
    private static $manager_url = 'http://activation.socialsharingplugin.com/manage/';
    private static $activate_url = 'https://activation.socialsharingplugin.com/';
    private static $option_data;
    private static $option_latest_version = 'essb4-latest-version';
    private static $latest_version;
    private static $benefit_url = "https://socialsharingplugin.com/direct-customer-benefits/";   
    
    private static function load() {
        $activation_data = get_option(self::$option);
        
        if (!$activation_data) {
            $activation_data = array();
        }
        
        self::$option_data = $activation_data;
        
        self::$latest_version = '';
        if (!self::$latest_version) {
            self::$latest_version = ESSB3_VERSION;
        }
    }
    
    public static function init() {
        self::load();
    }
    
    public static function saveVersion($version = '') {
        if ($version != '') {
            update_option(self::$option_latest_version, $version, 'false');
            self::$latest_version = $version;
        }
    }
    
    public static function existNewVersion() {
        if (version_compare ( ESSB3_VERSION, self::$latest_version, '<' )) {
            return true;
        }
        else {
            return false;
        }
    }
    
    public static function getLatestVersion() {
        return self::$latest_version;
    }
    
    public static function getApiUrl($callback = '') {
        if ($callback == 'manager') {
            return self::$manager_url;
        }
        else if ($callback == 'activate') {
            return self::$activate_url;
        }
        else if ($callback == 'api') {
            return self::$api;
        }
        else if ($callback == 'activate_domain') {
            return self::$activate_url.'?domain='.self::domain();
        }
        else {
            return self::$api;
        }
    }
    
    public static function activateManual($purchase_code = '', $activation_code = '', $domain = '') {
        $encrypted = hash ( 'sha1', $purchase_code . $domain );
        
        if ($encrypted == $activation_code) {
            self::activate($purchase_code, $activation_code);
            return '100';
        }
        else {
            return '200';
        }
    }
    
    public static function activate($purchase_code = '', $activation_code = '') {
        if (!self::$option_data) {
            self::$option_data = array();
        }
        
        if (!is_array(self::$option_data)) {
            self::$option_data = array();
        }
        
        self::$option_data['activated'] = 'true';
        self::$option_data['purchase_code'] = $purchase_code;
        self::$option_data['activation_code'] = $activation_code;
        
        update_option(self::$option, self::$option_data);
    }
    
    public static function deactivate() {
        if (!self::$option_data) {
            self::$option_data = array();
        }
        
        if (!is_array(self::$option_data)) {
            self::$option_data = array();
        }
        
        self::$option_data['activated'] = 'false';
        self::$option_data['activation_code'] = '';
        update_option(self::$option, self::$option_data);
    }
    
    public static function isActivated() {
        $isActivated = false;
        
        if (self::$option_data) {
            if (is_array(self::$option_data)) {
                $state = isset(self::$option_data['activated']) ? self::$option_data['activated'] : '';
                if ($state == 'true') {
                    $isActivated = true;
                }
            }
        }
        
        
        return $isActivated;
    }
    
    /***
     * Control plugin theme integration to stop notice that plugin is not activated
     *
     * @return boolean Integration state
     */
    public static function isThemeIntegrated() {
        $is_integrated = false;
        
        return apply_filters('essb_is_theme_integrated', $is_integrated);
    }
    
    public static function getPurchaseCode() {
        $purchase_code = '';
        if (self::$option_data) {
            if (is_array(self::$option_data)) {
                $purchase_code = isset(self::$option_data['purchase_code']) ? self::$option_data['purchase_code'] : '';
            }
        }
        
        if ($purchase_code == '') {
            $purchase_code = essb_option_value('purchase_code');
        }
        
        return $purchase_code;
    }
    
    public static function getMaskedPurchaseCode() {
        $purchase_code = '';
        if (self::$option_data) {
            if (is_array(self::$option_data)) {
                $purchase_code = isset(self::$option_data['purchase_code']) ? self::$option_data['purchase_code'] : '';
            }
        }
        
        if ($purchase_code == '') {
            $purchase_code = essb_option_value('purchase_code');
        }
        
        if ($purchase_code != '') {
            $max_length = strlen($purchase_code);
            $mask_length = round(strlen($purchase_code) / 2, 0);
            
            $purchase_code = substr($purchase_code, 0, $mask_length);
            
            for ($i = $mask_length; $i < $max_length; $i++) {
                $purchase_code .= '*';
            }
        }
        
        return $purchase_code;
    }
    
    public static function getActivationCode() {
        $activation_code = '';
        if (self::$option_data) {
            if (is_array(self::$option_data)) {
                $activation_code = isset(self::$option_data['activation_code']) ? self::$option_data['activation_code'] : '';
            }
        }
        
        return $activation_code;
    }
    
    public static function isStagingSite($domain) {
        
        $result = false;
        
        if (strpos($domain, '.local') !== false) {
            $result = true;
        }
        if (strpos($domain, '.localhost') !== false) {
            $result = true;
        }
        if (strpos($domain, '.dev') !== false) {
            $result = true;
        }
        if (strpos($domain, '.wpstagecoach') !== false) {
            $result = true;
        }
        if (strpos($domain, '.wpengine.com') !== false) {
            $result = true;
        }
        if (strpos($domain, '.pantheon.io') !== false) {
            $result = true;
        }
        if (strpos($domain, 'localhost') !== false) {
            $result = true;
        }
        if (strpos($domain, 'dev.') !== false) {
            $result = true;
        }
        if (strpos($domain, 'staging.') !== false) {
            $result = true;
        }
        if (strpos($domain, 'staging-') !== false) {
            $result = true;
        }
        
        return $result;
    }
    
    public static function domain() {
        $url = self::getSiteURL();
        $parse = parse_url($url);
        $domain_only = isset($parse['host']) ? $parse['host'] : '';
        
        return self::getDomain($domain_only);
    }
    
    public static function getDomain($domain) {
        if(preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $domain, $matches)) {
            return $matches['domain'];
        }
        else {
            return $domain;
        }
    }
    
    public static function getSiteURL() {
        return site_url();
    }
    
    public static function isDevelopment() {
        return self::isStagingSite(self::getSiteURL());
    }
    
    public static function getBenefitURL() {
        return self::$benefit_url;
    }
    
    /**
     * Deactivate plugin license on plugin uninstall
     */
    public static function deactivate_license_uninstall() {
        try {
            $hash = self::getActivationCode();
            $code = self::getPurchaseCode();
            $api_url = self::getApiUrl('api') . 'deactivate.php';
            
            $api_params = array(
                'hash' => $hash,
                'code' => $code
            );
            
            $response = wp_remote_post($api_url, array('timeout' => 15, 'sslverify' => true, 'body' => $api_params));
        }
        catch (Exception $e) {
        }
    }
}

/**
 * Register activation action only if the administration is running
 */

if (!function_exists('essb_admin_register_activation_action')) {
    add_action ( 'init', 'essb_admin_register_activation_action');
    
    function essb_admin_register_activation_action() {
        if (is_admin()) {
            add_action ( 'wp_ajax_essb_process_activation', 'essb_action_process_activation' );
        }
    }
    
    /**
     * Process the activation requests of the plugin
     */
    function essb_action_process_activation() {
        $purchase_code = sanitize_text_field(isset($_REQUEST['purchase_code']) ? $_REQUEST['purchase_code'] : '');
        $activation_code = sanitize_text_field(isset($_REQUEST['activation_code']) ? $_REQUEST['activation_code'] : '');
        $state = sanitize_text_field(isset($_REQUEST['activation_state']) ? $_REQUEST['activation_state'] : '');
        $domain = sanitize_text_field(isset($_REQUEST['domain']) ? $_REQUEST['domain'] : '');
        $version = sanitize_text_field(isset($_REQUEST['version']) ? $_REQUEST['version'] : '');
        
        $execute_code = -1;
        
        if ($state == 'activate' && $purchase_code != '' && $activation_code != '') {
            ESSBActivationManager::activate($purchase_code, $activation_code);
            $execute_code = 1;
        }
        
        if ($state == 'deactivate') {
            ESSBActivationManager::deactivate();
            $execute_code = 2;
        }
        
        if ($state == 'manual' && $purchase_code != '' && $activation_code != '') {
            $execute_code = ESSBActivationManager::activateManual($purchase_code, $activation_code, $domain);
        }
        
        if ($state == 'version_check' && $version != '') {
            ESSBActivationManager::saveVersion($version);
            $execute_code = ESSBActivationManager::existNewVersion() ? ESSBActivationManager::getLatestVersion() : '';
        }
        
        die(json_encode(array('code' => $execute_code)));
        exit;
    }
}
