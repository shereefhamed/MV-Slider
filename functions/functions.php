<?php

function gat_slider_post_image(){
    return '<img src="'. MV_SLIDER_URL . 'assets/images/placeholder.png' .'" alt="" calss="img-fluid wp-post-image">';
}
if( !function_exists('mv_slider_options') ){
    function mv_slider_options(){
        $show_bullets = isset( MV_Slider_Setting::$options['mv_slider_bullet'] ) && MV_Slider_Setting::$options['mv_slider_bullet'] ==1 ? 
        true: 
        false;
        wp_enqueue_script(
            'mv-slider-options-jq',
            MV_SLIDER_URL . 'vendor/flexslider/flexslider.js',
            array('jquery'),
            MV_SLIDER_VERSION,
            true,
        );
        wp_localize_script(
            'mv-slider-options-jq',
            'SLIDER_OPTIONS',
            array(
                'controlNav'    =>  $show_bullets,
            ),
        );
    }
}