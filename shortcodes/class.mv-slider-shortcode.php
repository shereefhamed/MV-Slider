<?php
if( !class_exists('MV_Slider_Shortcode') ){
    class MV_Slider_Shortcode{
        public function __construct() {
            add_shortcode( 'mv_slider',array( $this,'add_shortcode' ) );
        }

        public function add_shortcode( $atts=array(),$content=null,$shortcode_tag='' ){
            $atts = array_change_key_case( $atts,CASE_LOWER );
            extract(
                shortcode_atts(
                    array(
                        'id'        =>  '',
                        'orderby'   =>  'date',
                    ),
                    $atts,
                    $shortcode_tag,
                )
            );

            if( !empty( $id ) ){
                $id = array_map( 'absint',explode( ',',$id ) );
            }
            ob_start();
            require( MV_SLIDER_PATH. 'views/mv-slider-shortcode.php' );
            wp_enqueue_script( 'mv-slider-main-jq' );
            //wp_enqueue_script( 'mv-slider-options-jq' );
            wp_enqueue_style( 'mv-slider-main-css' );
            wp_enqueue_style( 'mv-slider-front-css' );
            mv_slider_options();
            return ob_get_clean();
        }
    }
}