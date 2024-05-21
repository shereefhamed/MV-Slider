<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <?php  
    $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'main-options';
    ?>
    <h1 class="nav-tab-wrapper">
        <a href="?page=mv_slider_admin&tab=main-options" class="nav-tab <?php echo $active_tab=='main-options' ? 'nav-tab-active': '' ?>"><?php _e('Main Options','mv-slider') ?></a>
        <a href="?page=mv_slider_admin&tab=additional-options" class="nav-tab <?php echo $active_tab=='additional-options' ? 'nav-tab-active': '' ?>"><?php _e('Additional Options','mv-slider') ?></a>
    </h1>
    <form action="options.php" method="post">
        <?php 
        if( $active_tab=='main-options' ){
            settings_fields('mv_slider_group');
            do_settings_sections('mv-slider_page1');
        }else{
            settings_fields('mv_slider_group');
            do_settings_sections('mv-slider_page2');
        }
        submit_button(__('Save Settings','mv-slider'));
        ?>
    </form>
</div>