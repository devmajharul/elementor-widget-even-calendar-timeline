<?php
/**
 * Plugin Name: MMI Addons
 * Plugin URI: https://mmi.com
 * Description: A custom elementor widget
 * Version: 1.0.0
 * Author: Md. Majharul Islam
 * Author URI: https://devmajharul.com
 * Text Domain: majharul_islam
 */

 if( ! defined( 'ABSPATH' ) ) exit();

final class MMI_Addons {

    // Plugin version
    const VERSION = '1.0.0';

    // Minimum Elementor Version
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    // Minimum PHP Version
    const MINIMUM_PHP_VERSION = '7.0';

    // Instance
    private static $_instance = null;

    public static function instance() {
        if( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        // Call Constants Method
        $this->define_constants();
        add_action( 'wp_enqueue_scripts', [ $this, 'scripts_styles' ] );
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function define_constants() {
        define( 'MYEW_PLUGIN_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
        define( 'MYEW_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
    }

    public function scripts_styles() {

        wp_enqueue_style( 'fontawesome','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css', [], rand(), 'all' );
        wp_enqueue_style( 'bootstrapcs','https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css', [], rand(), 'all' );
        ;
        wp_enqueue_style( 'even-calendar-main', MYEW_PLUGIN_URL . 'assets/css/even-calendar-main.css', [], rand(), 'all' );
        wp_enqueue_style( 'even-calendar', MYEW_PLUGIN_URL . 'widgets/css/even-calendar.css', [], rand(), 'all' );
        // main css
        wp_enqueue_style( 'public-css', MYEW_PLUGIN_URL . 'assets/css/public.min.css', [], rand(), 'all' );

        wp_enqueue_script( 'util', MYEW_PLUGIN_URL . 'assets/js/util.js', [ 'jquery' ], rand(), true );
        wp_enqueue_script( 'swipeevenc', MYEW_PLUGIN_URL . 'assets/js/swipe-content.js', [ 'jquery' ], rand(), true );
        wp_enqueue_script( 'evencjs', MYEW_PLUGIN_URL . 'assets/js/even-calendar.js', [ 'jquery' ], rand(), true );
        wp_enqueue_script( 'bootjs','https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js', [ 'jquery' ], rand(), true );
        // main js
        wp_enqueue_script( 'myew-script', MYEW_PLUGIN_URL . 'assets/js/public.min.js', [ 'jquery' ], rand(), true );

    }

    public function i18n() {
       load_plugin_textdomain( 'majharul_islam', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    public function init() {
        // Check if the ELementor installed and activated
        if( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        if( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        if( ! version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        add_action( 'elementor/init', [ $this, 'init_category' ] );
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
    }


    // new widget add
    public function init_widgets() {
        
        require_once MYEW_PLUGIN_PATH . '/widgets/even-calendar.php';
    }


    // new catagory add
    public function init_category() {
        Elementor\Plugin::instance()->elements_manager->add_category(
            'my_catagory',
            [
                'title' => 'MMI Addons'
            ],
            1
        );
    }




    // Don't Touch
    /**
    * Admin Notice
    * Warning when the site doesn't have Elementor installed or activated
    * @since 1.0.0
    */
    public function admin_notice_missing_main_plugin() {
        if( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );
        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated', 'majharul_islam' ),
            '<strong>'.esc_html__( 'MMI Addons', 'majharul_islam' ).'</strong>',
            '<strong>'.esc_html__( 'Elementor', 'majharul_islam' ).'</strong>'
        );

        printf( '<div class="notice notice-warning is-dimissible"><p>%1$s</p></div>', $message );
    }

    /**
    * Admin Notice
    * Warning when the site doesn't have a minimum required Elementor version.
    * @since 1.0.0
    */
    public function admin_notice_minimum_elementor_version() {
        if( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );
        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater', 'majharul_islam' ),
            '<strong>'.esc_html__( 'MMI Addons', 'majharul_islam' ).'</strong>',
            '<strong>'.esc_html__( 'Elementor', 'majharul_islam' ).'</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dimissible"><p>%1$s</p></div>', $message );
    }

    /**
    * Admin Notice
    * Warning when the site doesn't have a minimum required PHP version.
    * @since 1.0.0
    */
    public function admin_notice_minimum_php_version() {
        if( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );
        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater', 'majharul_islam' ),
            '<strong>'.esc_html__( 'MMI Addons', 'majharul_islam' ).'</strong>',
            '<strong>'.esc_html__( 'PHP', 'majharul_islam' ).'</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dimissible"><p>%1$s</p></div>', $message );
    }

}

MMI_Addons::instance();