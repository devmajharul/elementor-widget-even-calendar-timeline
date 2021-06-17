<?php
namespace Elementor;

class Even_Calendar_Widgets extends Widget_Base {

    public function get_name() {
        return  'even_calendar';
    }

    public function get_title() {
        return esc_html__( 'MMI Even Calendar', 'majharul_islam' );
    }

    public function get_script_depends() {
        return [
            'myew-script'
        ];
    }

    public function get_icon() {
        return ' eicon-calendar';
    }

    public function get_categories() {
        return [ 'my_catagory' ];
    }


    public function _register_controls() {

        // Slider content
        $this->start_controls_section(
            'even_calendar_content_section',
            [
                'label' => __( 'Calendar Content', 'MMI Addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'calendar_img',
            [
                'label' => __( 'Choose Image', 'majharul_islam' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'calendar_title', [
                'label' => __( 'Slider Title', 'majharul_islam' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Title#1' , 'majharul_islam' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'calendar_slide_date', [
                'label' => __( 'Even Slide Date', 'majharul_islam' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( '15/01/2010' , 'majharul_islam' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'calendar_slide_year', [
                'label' => __( 'Even Slide Year', 'majharul_islam' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( '2010' , 'majharul_islam' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'public_view_date', [
                'label' => __( 'Content view date', 'majharul_islam' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( '19 oct 2020' , 'majharul_islam' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
          'calendar_description',
          [
            'label' => __( 'Description', 'majharul_islam' ),
            'type' => Controls_Manager::WYSIWYG,
            'default' => __( 'Default description', 'majharul_islam' ),
            'placeholder' => __( 'Type your description here', 'majharul_islam' ),
          ]
        );

        $this->add_control(
            'calendar_list_even',
            [
                'label' => __( 'Calendar List', 'majharul_islam' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'calendar_title' => __( 'Title #1', 'majharul_islam' ),
                        'calendar_description' => __('1.Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque reiciendis delectus temporibus, corporis culpa placeat iste sed tenetur, fuga laudantium.','majharul_islam'),
                    ],

                    [
                        'calendar_title' => __( 'Title #2', 'majharul_islam' ),
                        'calendar_description' => __('2.Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque reiciendis delectus temporibus, corporis culpa placeat iste sed tenetur.','majharul_islam'),
                    ],
                    
                ],
                'title_field' => '{{{ calendar_title }}}',
            ]
        );

        $this->end_controls_section();


        // Style Tab
        $this->style_tab();
    }

    private function style_tab() {}

    protected function render() {
        $settings = $this->get_settings_for_display();

        ?>
        
        <script><?php echo 'document.getElementsByTagName("html")[0].className += " js";'; ?></script>
          <section class="cd-h-timeline js-cd-h-timeline margin-bottom-md">
            <div class="cd-h-timeline__container container">
              <div class="cd-h-timeline__dates">
                <div class="cd-h-timeline__line">
                 
                  <ol>
                    <!-- <li><a href="#0" data-date="15/01/2015" class="cd-h-timeline__date cd-h-timeline__date--selected">Staring</a></li> -->

                    <?php 
                    $i = 1;
                    foreach ($settings['calendar_list_even'] as $calendar_date) : ?>
                    <li><a href="#0" data-date="<?php echo $calendar_date['calendar_slide_date'];?>" class="cd-h-timeline__date <?php if($i===1){echo 'cd-h-timeline__date--selected';}?>"> <?php if($i===1) { echo 'Staring';} else{ echo ''.$calendar_date['calendar_slide_year'].'';} ?></a></li>
                    <?php $i++; endforeach;?>
                  </ol>
                  
                  <span class="cd-h-timeline__filling-line" aria-hidden="true"></span>
                </div> <!-- .cd-h-timeline__line -->
              </div> <!-- .cd-h-timeline__dates -->
          
              <ul>
                <li><a href="#0" class="text-replace cd-h-timeline__navigation cd-h-timeline__navigation--prev cd-h-timeline__navigation--inactive">Prev</a></li>
                <li><a href="#0" class="text-replace cd-h-timeline__navigation cd-h-timeline__navigation--next">Next</a></li>
              </ul>
            </div> <!-- .cd-h-timeline__container -->

            <div class="cd-h-timeline__events">
              <ol>

              <?php foreach ($settings['calendar_list_even'] as $calendar_info) : ?>
                <li class="cd-h-timeline__event">
                  <div class="cd-h-timeline__event-content container">
                    <div class="row align-items-center">
                      <div class="col-md-6">
                        <img src="<?php echo $calendar_info['calendar_img']['url']; ?>" alt="image">
                      </div>
                      <div class="col-md-6">
                        <div class="event_content">
                          <h6><?php echo $calendar_info['public_view_date']; ?></h6>
                          <h4><?php echo $calendar_info['calendar_title']; ?></h4>
                          <p><?php echo $calendar_info['calendar_description']; ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>

              </ol>
            </div> <!-- .cd-h-timeline__events -->
          </section>

        <?php
    }


}
Plugin::instance()->widgets_manager->register_widget_type( new Even_Calendar_Widgets() );