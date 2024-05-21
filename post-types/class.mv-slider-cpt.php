<?php
if(!class_exists('MV_SLider_Post_type')){
    class MV_SLider_Post_Type{
        public function __construct() {
            add_action( 'init',array( $this,'create_post_type' ) );
            add_action( 'add_meta_boxes',array( $this, 'add_meta_boxs' ) );
            add_action( 'save_post',array( $this,'save_post' ),10,3 );
            add_filter( 'manage_mv-slider_posts_columns',array($this,'manage_mv_slider_posts_columns'));
            add_action( 'manage_mv-slider_posts_custom_column',array($this,'manage_mv_slider_posts_custom_column'),10,2);
            add_filter( 'manage_edit-mv-slider_sortable_columns',array($this,'manage_mv_slider_sortable_columns') );
        }

        public function create_post_type(){
            register_post_type('mv-slider', 
                array(
                    'label'         =>      esc_html__( 'Slider','mv-slider' ),
                    'description'   =>      esc_html__( 'MV SLider','mv-slider' ),
                    'labels'        =>      array(
                        'name'          =>  'Sliders',
                        'singular_name' =>  'Slider',
                    ),
                    'public'                =>      true,
                    'hierarchical'          =>      true,
                    'show_ui'               =>      true,
                    'show_in_menu'          =>      false,
                    'menu_position'         =>      5,
                    'show_in_admin_bar'     =>      true,
                    'show_in_nav_menus'     =>      true,
                    'can_export'            =>      true,
                    'has_archive'           =>      false,
                    'exclude_from_search'   =>      false,
                    'publicly_queryable'    =>      true,
                    'show_in_rest'          =>      true,
                    'menu_icon'             =>      'dashicons-slides',
                    //'register_meta_box_cb'  =>      array( $this,'add_meta_boxs' ),
                    'supports'              =>      array(
                        'title',
                        'editor',
                        'thumbnail',
                        'page-attributes',
                        //'custom-fields',
                    ),
                )
            );
        }

        public function add_meta_boxs(){
            add_meta_box(
                'mv_slider_meta_box',
                __('Link Options','mv-slider'),
                array( $this,'render_meta_box_content' ),
                'mv-slider',
                'normal',
                'default'
            );
        }

        public function render_meta_box_content($post){
            require_once( MV_SLIDER_PATH . 'views/mv-slider-metabox.php' );
        }

        public function save_post($post_id, $post, $update){
            if(isset($_POST['mv_slider_nonce'])){
                if(!wp_verify_nonce($_POST['mv_slider_nonce'],'mv_slider_nonce')){
                    return;
                }
            }

            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
                return;
            }

            if(isset($_POST['post_type']) && $_POST['post_type']=='mv-slider'){
                if(!current_user_can('edit_post',$post_id)){
                    return;
                }else if(!current_user_can('edit_page',$post_id)){
                    return;
                }
            }
            
            if(isset($_POST['action']) && $_POST['action']=='editpost'){
                $prev_link_text_value = get_post_meta($post_id, 'mv_slider_link_text', true);
                $prev_link_url_value = get_post_meta($post_id, 'mv_slider_link_url', true);
                $link_text = $_POST['mv_slider_link_text'];
                $link_url = $_POST['mv_slider_link_url'];
                if(empty($link_text)){
                    update_post_meta($post_id, 'mv_slider_link_text', 'Add Text Here');
                }else{
                    update_post_meta($post_id, 'mv_slider_link_text',sanitize_text_field( $link_text ), $prev_link_text_value);
                }
                if(empty($link_url)){
                    update_post_meta($post_id, 'mv_slider_link_url', '#');
                }else{
                    update_post_meta($post_id, 'mv_slider_link_url',sanitize_text_field( $link_url ), $prev_link_url_value);
                }
                
            }

        }

        public function manage_mv_slider_posts_columns($columns){
            $columns['mv_slider_text'] = esc_html__('Slider Text','mv-slider');
            $columns['mv_slider_link'] = esc_html__('Slider Link','mv-slider');
            return $columns;
        }

        public function manage_mv_slider_posts_custom_column($column,$post_id){
            switch ($column){
                case 'mv_slider_text':
                    echo esc_html( get_post_meta($post_id, 'mv_slider_link_text', true) );
                    break;
                case 'mv_slider_link':
                    echo esc_html( get_post_meta($post_id, 'mv_slider_link_url', true) );
            }
            
        } 

        public function manage_mv_slider_sortable_columns($columns){
            $columns['mv_slider_link'] = 'mv_slider_link';
            $columns['mv_slider_text'] = 'mv_slider_text';
            return $columns;
        }
    } 
}