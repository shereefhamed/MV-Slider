<h1><?php echo empty( $content )? esc_html( MV_Slider_Setting::$options['mv_slider_title'] ): esc_html( $content );  ?></h1>
<div class="flexslider mv-slider <?php echo isset( MV_Slider_Setting::$options['mv_slider_style'] )? esc_attr( MV_Slider_Setting::$options['mv_slider_style'] ):'style-1' ?>">
  <ul class="slides">
    <?php
    $args = array(
        'post_type' => 'mv-slider',
        'post_status'   => 'publish',
        'post__in'  => $id,
        'orderby' => $orderby
    );
    $wp_query = new WP_Query($args);
    if( $wp_query->have_posts() ):
        while( $wp_query->have_posts() ):
            $wp_query->the_post();
            $button_text = get_post_meta( get_the_ID(),'mv_slider_link_text' , true );
            $button_link = get_post_meta( get_the_ID(),'mv_slider_link_url' , true );
    ?>
            <li>
                <?php 
                if( has_post_thumbnail() ){
                    the_post_thumbnail( 'full',array( 'class' =>  'img-fluid' ) ); 
                }else{
                    echo gat_slider_post_image();
                }
                ?>
                <div class="mvs-container">
                    <div class="slider-details-container">
                        <div class="wrapper">
                            <div class="slider-title">
                                <h2><?php  the_title() ?></h2>
                            </div>
                            <div class="slider-description">
                                <div class="subtitle"><?php the_content() ?></div>
                                <a class="link" href="<?php echo esc_attr( $button_link ) ?>"><?php echo  esc_html( $button_text ) ?></a>
                            </div>
                        </div>
                    </div>              
                </div>
            </li>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
  </ul>
</div>