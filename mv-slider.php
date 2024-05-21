<?php
/**
 * Plugin Name: MV Slider
 * Plugin URI: https://google.com
 * Description: This a test plugin
 * Version: 1.0
 * Author: Shereef Hamed
 * Author URI: https://elafcorp.com/shereef
 * Text Domin: mv-slider
 * Domain Path: /languages
 */

if( !defined('ABSPATH') ){
    exit;
}

if(!class_exists('MV_Slider')){
    class MV_Slider{
        public function __construct() {
            $this->define_plugin_contstants();
            require_once( MV_SLIDER_PATH . 'functions/functions.php' );
            require_once( MV_SLIDER_PATH.'post-types/class.mv-slider-cpt.php' );
            require_once(MV_SLIDER_PATH. 'mv_slider_setting.php');
            require_once( MV_SLIDER_PATH . 'shortcodes/class.mv-slider-shortcode.php' );
            add_action( 'admin_menu',array($this,'add_menu') );
            $mv_slider_post_type = new MV_SLider_Post_Type();
            $mv_slider_setting = new MV_Slider_Setting();
            $mv_slider_shortcode = new MV_Slider_Shortcode();
            add_action( 'wp_enqueue_scripts',array( $this,'register_scripts' ),999 );
            add_action( 'admin_enqueue_scripts',array( $this,'register_admin_scriptis' ) );
            $this->load_textdomain();
        }

        public function define_plugin_contstants(){
            define( 'MV_SLIDER_PATH',plugin_dir_path(__FILE__) );
            define( 'MV_SLIDER_URL',plugin_dir_url(__FILE__) );
            define( 'MV_SLIDER_VERSION','1.0.0' );
        }

        public static function activate(){
            update_option('rewrite_rules','');

        }

        public static function deactivate(){
            flush_rewrite_rules();
            unregister_post_type('mv-slider');

        }

        public static function uninstall(){
            delete_option( 'mv_slider_options' );
            $posts = get_posts(
                array(
                    'post_type' =>  'mv-slider',
                    'numnumberposts'    =>  -1,
                    'status'    =>  'any',
                )
            );
            foreach( $posts as $post ){
                wp_delete_post(
                    $post->ID,
                    true,
                );
            }
        }

        public function add_menu(){
            add_menu_page(
                'MV Slider Option', 
                'MV Slider', 
                'manage_options', 
                'mv_slider_admin', 
                array($this,'mv_slider_setting_page'), 
                'dashicons-slides'
            );
            add_submenu_page(
                'mv_slider_admin',
                'Manage Slides',
                'Manage Slides',
                'manage_options',
                'edit.php?post_type=mv-slider',
                null,
                null,
            );
            add_submenu_page(
                'mv_slider_admin',
                'Add new Slide',
                'Add New Slide',
                'manage_options',
                'post-new.php?post_type=mv-slider',
                null,
                null,
            );
            /* add_submenu_page(
                'mv_slider_admin',
                esc_html__('All Slides','mv-slider'),
                esc_html__('All Slides','mv-slider'),
                'manage_options',
                'edit.php?post_type=mv-slider',
            );
            add_submenu_page(
                'mv_slider_admin',
                esc_html__('Add new Slide','mv-slider'),
                esc_html__('Add New Slide','mv-slider'),
                'manage_options',
                'post-new.php?post_type=mv-slider',
            ); */
        }

        /* public function add_menu(){
            add_plugins_page(
                'MV Slider Option', 
                'MV Slider', 
                'manage_options', 
                'mv_slider_admin', 
                array($this,'mv_slider_setting_page'), 
            );
        } */

        /* public function add_menu(){
            add_theme_page(
                'MV Slider Option', 
                'MV Slider', 
                'manage_options', 
                'mv_slider_admin', 
                array($this,'mv_slider_setting_page'), 
            );
        } */

        /* public function add_menu(){
            add_options_page(
                'MV Slider Option', 
                'MV Slider', 
                'manage_options', 
                'mv_slider_admin', 
                array($this,'mv_slider_setting_page'), 
            );
        } */

        public function mv_slider_setting_page(){
            if( !current_user_can('manage_options') ){
                return;
            }
            if( isset( $_GET['settings-updated'] ) ){
                add_settings_error( 
                    'mv_slider_options', 
                    'mv_slider_message',
                    'Setting updated',
                    'success',
                );
            }
            settings_errors( 'mv_slider_options' );
            require (MV_SLIDER_PATH . 'views/setting_page.php');
        }

        public function register_scripts(){
            wp_register_script(
                'mv-slider-main-jq',
                MV_SLIDER_URL . 'vendor/flexslider/jquery.flexslider-min.js',
                array('jquery'),
                MV_SLIDER_VERSION,
                true,
            );
            wp_register_style(
                'mv-slider-main-css',
                MV_SLIDER_URL . 'vendor/flexslider/flexslider.css',
                array(),
                MV_SLIDER_VERSION,
                'all',
            );
            wp_register_style(
                'mv-slider-front-css',
                MV_SLIDER_URL . 'assets/css/frontend.css',
                array(),
                MV_SLIDER_VERSION,
                'all'
            );
        }

        public function register_admin_scriptis(){
            global $typenow;
            if( $typenow=='mv-slider' ){
                wp_enqueue_style(
                    'mv-slider-admin',
                    MV_SLIDER_URL . 'assets/css/admin.css',
                );
            }
        }

        public function load_textdomain(){
            load_plugin_textdomain(
                'mv-slider',
                false,
                dirname( plugin_basename( __FILE__ ) ) . '/languages/'
            );
        }
    }
}

if(class_exists('MV_Slider')){
    register_activation_hook( __FILE__,array( 'MV_Slider','activate' ) );
    register_deactivation_hook( __FILE__,array( 'MV_Slider','deactivate') );
    register_uninstall_hook( __FILE__,array( 'MV_Slider','uninstall' ) );
    $mv_slider = new MV_Slider();
}