<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\widget;
use MatthiasWeb\RealMediaLibrary\WPLR\base;
use MatthiasWeb\RealMediaLibrary\WPLR\general;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

/**
 * Simple widget that creates an HTML element into which React renders
 */
class Widget extends \WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'description' => 'A widget that demonstrates using React.'
		);
		parent::__construct( WPLR_RML_TD . 'react-demo', 'React Demo Widget', $widget_ops);
	}

	public function widget($args, $instance) {
		echo $args['before_widget'];
		?>
			<div class="react-demo-wrapper"></div>
		<?php
		echo $args['after_widget'];
	}

	public function update($new_instance, $old_instance) {
        // Silence is golden.
	}

	public function form($instance) {
	    // Silence is golden.
	}
}