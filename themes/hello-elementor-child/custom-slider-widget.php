<?php
class Custom_Slider_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'custom_slider_widget';
    }

    public function get_title() {
        return __('Custom Slider', 'text-domain');
    }

    public function get_script_depends() {
        return ['slick-js']; // If you use Slick slider
    }

    public function get_style_depends() {
        return ['slick-css']; // If you use Slick slider
    }

    protected function _register_controls() {
        $this->start_controls_section('content_section', [
            'label' => __('Configuration', 'text-domain'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('image', [
            'label' => __('Choose Image', 'text-domain'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => [
                'url' => \Elementor\Utils::get_placeholder_image_src(),
            ],
        ]);
        // Add the text control for each image
    $repeater->add_control(
        'image_text', [
            'label' => __( 'Image Text', 'text-domain' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Image description', 'text-domain' ),
            'dynamic' => [
                'active' => true,
            ],
            'label_block' => true,
        ]
    );
     // Add the text control for the "Read More" button
     $repeater->add_control(
        'read_more_text', [
            'label' => __( 'Read More Text', 'text-domain' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Read More', 'text-domain' ),
            'dynamic' => [
                'active' => true,
            ],
            'label_block' => true,
        ]
    );
    // Add the URL control for the "Read More" button
    $repeater->add_control(
        'read_more_link', [
            'label' => __( 'Read More Link', 'text-domain' ),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => __( 'https://your-link.com', 'text-domain' ),
            'show_external' => true,
            'default' => [
                'url' => '',
                'is_external' => true,
                'nofollow' => false,
            ],
            'dynamic' => [
                'active' => true,
            ],
        ]
    );

        $this->add_control('slider_images', [
            'label' => __('Slider Images', 'text-domain'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ($settings['slider_images']) {
            echo '<div class="custom-slider-park">';
            foreach ($settings['slider_images'] as $item) {
                echo '<div class="slider-item-park">';
                echo '<img src="' . esc_url($item['image']['url']) . '">';
                echo '<div class="slider-text-read">';
                if ( ! empty( $item['image_text'] ) ) {
                    echo '<p class="slider-item-text">' . $item['image_text'] . '</p>';
                }
                if ( ! empty( $item['read_more_text'] ) && ! empty( $item['read_more_link']['url'] ) ) {
                    $target = $item['read_more_link']['is_external'] ? ' target="_blank"' : '';
                    $nofollow = $item['read_more_link']['nofollow'] ? ' rel="nofollow"' : '';
                    echo '<a class="slider-item-read-more" href="' . esc_url( $item['read_more_link']['url'] ) . '"' . $target . $nofollow . '>' . $item['read_more_text'] . '</a>';
                }
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
    }
    

    protected function _content_template() {
        // You can also define the frontend HTML rendering with JavaScript here for live editing preview
    }
}


?>