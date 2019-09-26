<?php
/**
 * A badge widget.
 *
 * @since 1.0.0
 * @package Nicomv/Elemental
 * @subpackage Nicomv/Elemental/Components
 */

namespace Nicomv\Elemental\Components;

use Elementor\Widget_Base;

use Nicomv\Elemental\Includes\Utils;

use Nicomv\Elemental\Includes\Logger;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Represents a badge widget to be used inside elementor.
 *
 * @since 1.0.0
 * @package Nicomv/Elemental
 * @subpackage Nicomv/Elemental/Components
 */
class Badge_Widget extends Widget_Base {
	/**
	 * Widget name.
	 */
	public function get_name() {
		return 'nmv_badge_widget';
	}

	/**
	 * Widget title.
	 */
	public function get_title() {
		return 'Badge List';
	}

	/**
	 * Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-th-list';
	}

	/**
	 * Widget category.
	 */
	public function get_categories() {
		return [ 'nmv-elemental-general' ];
	}

	/**
	 * The controls on the widget.
	 */
	protected function _register_controls() {
		$this->register_content_controls();
		$this->register_container_controls();
		// $this->register_general_controls();
	}

	/**
	 * Renders the content.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$size = count( $settings['badge_cloud'] );
		$container_classes = Utils::parse_classes(
			$settings['container_class'],
			[
				'field',
				'is-grouped',
				'is-grouped-multiline',
			]
		);
		?>
		<div class="<?php echo esc_html( $container_classes ); ?>">
		<?php
		foreach ( $settings['badge_cloud'] as $index => $setting ) :
				?>
		<div class="control">
				<?php
				$this->render_badge( $setting, $settings );
				?>
		</div><!-- .control -->
				<?php
		endforeach;
		?>
		</div><!-- .field -->
		<?php
	}

	/**
	 * Renders a single badge.
	 *
	 * @since 1.0.0
	 * @param array $settings This badge specific settings.
	 * @param array $general_settings The entire settings array.
	 */
	private function render_badge( $settings, $general_settings ) {
		$title = $settings['badge_title'];
		$default_title_classes = [ 'is-primary', 'tag' ];
		$default_subtitle_classes = [ 'tag' ];

		if ( 'yes' === $general_settings['general_removebadgeradius'] ) {
				$default_title_classes[] = 'is-radiusless';
				$default_subtitle_classes[] = 'is-radiusless';
		}

		$hover_text = $settings['badge_hover_title'];
		$subtitle = $settings['badge_subtitle'];

		if ( $subtitle ) :
				$subtitle_class = Utils::parse_classes( $settings['badge_subtitle_class'], $default_subtitle_classes );
				$title_class = Utils::parse_classes( $settings['badge_title_class'], $default_title_classes );
				$badge_classes = Utils::parse_classes( $general_settings['general_badgeclasses'], [ 'tags', 'has-addons' ] );
				?>
		<div class="<?php echo esc_html( $badge_classes ); ?>" title="<?php echo esc_html( $hover_text ); ?>">
				<?php
				$this->render_badge_title( $title, $hover_text, $title_class, $this->is_keyword( $general_settings, $settings ) );
				?>
			<span class="<?php echo esc_html( $subtitle_class ); ?>">
				<?php
				echo esc_html( $subtitle );
				?>
			</span>
		</div>
				<?php
		else :
			$badge_classes = Utils::parse_classes( $general_settings['general_badgeclasses'], $default_title_classes );
			$this->render_badge_title( $title, $hover_text, $badge_classes, $this->is_keyword( $general_settings, $settings ) );
		endif;
	}

	/**
	 * Renders the badge title.
	 *
	 * @param string  $title The badge title.
	 * @param string  $hover_text The hover text.
	 * @param string  $classes The tag classes.
	 * @param boolean $is_keyword Specifies if the tag is a keyword or not.
	 */
	private function render_badge_title( $title, $hover_text, $classes, $is_keyword = false ) {
		$tag = '';
		$tag_end = '';

		if ( true === $is_keyword ) {
			$tag = '<strong';
			$tag_end = '</strong>';
		} else {
			$tag = '<span';
			$tag_end = '</span>';
		}

		if ( ! empty( $hover_text ) ) {
			$tag .= ' title="' . $hover_text . '"';
		}

		$tag .= ' class="' . $classes . '"';
		$tag .= '>';
		// phpcs:disable
		echo $tag . esc_html( $title ) . $tag_end;
		// phpcs:enable
	}

	/**
	 * Checks if a badge is a keyword or not.
	 *
	 * @param array $settings The array with all the settings available.
	 * @param array $badge_settings The badge specific settings.
	 * @return boolean true if the badge is a keyword.
	 */
	private function is_keyword( $settings, $badge_settings ) {
		if ( ! empty( $badge_settings['badge_iskeyword'] ) ) {
			return 0 === $badge_settings['badge_iskeyword'];
		}
		return 'yes' === $settings['general_arekeywords'];
	}

	/**
	 * Registers the content controls.
	 *
	 * @since 1.0.0
	 */
	private function register_content_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'nmv-elemental' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'general_removebadgeradius',
			[
				'label' => __( 'Remove rounded corners', 'nmv-elemental' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'input_type' => 'checkbox',
				'description' => __( 'Enable/Disable the rounded corners for the tags.', 'nmv-elemental' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'general_arekeywords',
			[
				'label' => __( 'Are keywords', 'nmv-elemental' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'input_type' => 'checkbox',
				'description' => __( 'If enabled, the badge titles will use the strong tag. Can be overriden on each item.', 'nmv-elemental' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'general_badgeclasses',
			[
				'label' => __( 'Badge Classes', 'nmv-elemental' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'description' => __( 'A list of comma separated CSS classes to add to each badge.', 'nmv-elemental' ),
			]
		);

		$this->add_control(
			'badge_cloud',
			[
				'label' => __( 'List of Badges', 'nmv-elemental' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'badge_title',
						'label' => __( 'Badge Title', 'nmv-elemental' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'input_type' => 'text',
						'placeholder' => __( 'Eg. Blender 3D', 'nmv-elemental' ),
						'default' => '',
					],
					[
						'name' => 'badge_hover_title',
						'label' => __( 'Badge Hover Title', 'nmv-elemental' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'input_type' => 'text',
						'placeholder' => __( 'A text displayed when the tag is hovered', 'nmv-elemental' ),
						'default' => '',
					],
					[
						'name' => 'badge_title_class',
						'label' => __( 'Badge Title Class', 'nmv-elemental' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'input_type' => 'text',
						'placeholder' => 'is-primary, is-red',
						'description' => __( 'A comma separated list of classes added to the title tag.', 'nmv-elemental' ),
						'default' => 'is-primary, is-radiusless',
					],
					[
						'name' => 'badge_subtitle',
						'label' => __( 'Badge Content', 'nmv-elemental' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'input_type' => 'text',
						'placeholder' => __( 'Eg. A+', 'nmv-elemental' ),
						'default' => '',
					],
					[
						'name' => 'badge_subtitle_class',
						'label' => __( 'Badge Subtitle Class', 'nmv-elemental' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'input_type' => 'text',
						'placeholder' => 'is-primary, is-red',
						'description' => __( 'A comma separated list of classes added to the subtitle tag.', 'nmv-elemental' ),
						'default' => 'is-radiusless',
					],
					[
						'name' => 'badge_iskeyword',
						'label' => __( 'Should this badge be a keyword?', 'nmv-elemental' ),
						'type' => \Elementor\Controls_Manager::CHOOSE,
						'description' => __( 'If active, the title tag becomes a strong tag. Overrides the Content option.', 'nmv-elemental' ),
						'default' => '',
						'options' => [
							[
								'title' => __( 'Yes', 'nmv-elemental' ),
								'icon' => 'fa fa-thumbs-up',
							],
							[
								'title' => __( 'No', 'nmv-elemental' ),
								'icon' => 'fa fa-thumbs-down',
							],
						],
					],
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register the controls that apply to the container.
	 *
	 * @since 1.0.0
	 */
	private function register_container_controls() {
		$this->start_controls_section(
			'container_section',
			[
				'label' => __( 'Container', 'nmv-elemental' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
			);

		$this->add_control(
			'container_class',
			[
				'label' => __( 'Container Classes', 'nmv-elemental' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => 'is-primary, is-red',
				'description' => __( 'A comma separated list of classes added to the container tag', 'nmv-elemental' ),
			]
			);

		$this->end_controls_section();
	}

	/**
	 * Registers the general settings, which affects the
	 * entire output.
	 *
	 * @since 1.0.0
	 */

	/*
	 * private function register_general_controls() {
		$this->start_controls_section(
			'general_section',
			[
				'label' => __( 'General', 'nmv-elemental' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'general_removebadgeradius',
			[
				'label' => __( 'Remove rounded corners', 'nmv-elemental' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'input_type' => 'checkbox',
				'description' => __( 'Enable/Disable the rounded corners for the tags', 'nmv-elemental' ),
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}
	*/
}
