<?php
if(!class_exists('MV_Slider_Setting')){
    class MV_Slider_Setting{
        public static $options;

        public function __construct() {
            self::$options = get_option('mv_slider_options');
            add_action('admin_init', array($this,'admin_init'));
        }

        public function admin_init(){
            register_setting(
                'mv_slider_group',
                'mv_slider_options',
                array( $this,'mv_slider_validator' ),
            );
            add_settings_section(
                'mv-slider-manin-section',
                __('How Does it Work?','mv-slider'),
                null,
                'mv-slider_page1',
            );
            add_settings_field(
                'mv_slider_shortcode',
                'Shortcode',
                array($this,'mv_slider_shortcode_cb_function'),
                'mv-slider_page1',
                'mv-slider-manin-section',
            );
            
            add_settings_section(
                'mv-slider-second-section',
                'Other plugin Options',
                null,
                'mv-slider_page2',
            );
            add_settings_field(
                'mv_slider_title',
                'Slider Title',
                array($this,'mv_slider_title_cb_function'),
                'mv-slider_page2',
                'mv-slider-second-section',
            );
            add_settings_field(
                'mv_slider_bullet',
                'Display Bullet',
                array($this,'display_bullet_cb_function'),
                'mv-slider_page2',
                'mv-slider-second-section',
            );
            add_settings_field(
                'mv_slider_style',
                'Choose Syle',
                array($this,'style_cb_function'),
                'mv-slider_page2',
                'mv-slider-second-section',
                array(
                    'items' =>  array(
                        'style-1',
                        'style-2',
                    ),
                    'classes'   =>  'mv-slider-style',
                )
            );
        }

        public function mv_slider_shortcode_cb_function(){
            ?>
            <span>Copy this shortcode [mv_slider] to any page</span>
            <?php
        }

        public function mv_slider_title_cb_function(){
            ?>
            <input 
            type="text" 
            name="mv_slider_options[mv_slider_title]" 
            id="mv_slider_title" 
            value="<?php echo isset(self::$options['mv_slider_title'])? self::$options['mv_slider_title'] :'' ?>"
            >
            <?php
        }

        public function display_bullet_cb_function(){
            ?>
            <input 
            type="checkbox" 
            name="mv_slider_options[mv_slider_bullet]" 
            id="mv_slider_bullet"
            value="1"
            <?php 
            if(isset(self::$options['mv_slider_bullet'])){
                checked("1", self::$options['mv_slider_bullet'], true) ;
            }
            ?>
            >
            <label for="mv_slider_bullet">Display bullets</label>
            <?php
        }

        public function style_cb_function($args){
            ?>
            <select class="<?php echo esc_attr($args['classes']) ?>" name="mv_slider_options[mv_slider_style]" id="mv_slider_style">
                <?php foreach($args['items'] as $item): ?>
                    <option 
                        value="<?php echo esc_attr($item); ?>"
                        <?php isset(self::$options['mv_slider_style'])? selected($item, self::$options['mv_slider_style'], true): '' ?>>
                        <?php echo esc_html( ucfirst( $item) ); ?>
                    </option>
                <?php endforeach; ?>
                <!-- <option 
                value="style-1"
                <?php 
                if(isset(self::$options['mv_slider_style'])){
                    selected('style-1', self::$options['mv_slider_style'], true);
                }
                ?>
                >Style-1</option>
                <option value="style-2"
                <?php isset(self::$options['mv_slider_style'])? selected('style-2', self::$options['mv_slider_style'], true) : '';?>
                >Style-2</option> -->
            </select>
            <?php
        }

        public function mv_slider_validator($inputs){
            $new_inputs = [];
            foreach( $inputs as $key=>$vlaue ){
                //$new_inputs[$key]=sanitize_text_field( $vlaue );
                switch ($key){
                    case 'mv_slider_title':
                        if( empty( $vlaue ) ){
                            add_settings_error( 
                                'mv_slider_options', 
                                'mv_slider_message',
                                'Please enter title',
                                'error',
                            );
                            $vlaue = 'Please enter value';
                        }
                        $new_inputs[$key]=sanitize_text_field( $vlaue );
                        break;
                    default :
                        $new_inputs[$key]=sanitize_text_field( $vlaue );
                        break;
                }
            }
            return $new_inputs;
        }
    }
}