<?php
/**
 * Plugin Name:       AMA Weather
 * Description:       A fancy weather block!
 * Version:           1.0.0
 * Author:            Charles Dyke
 * Author URI:        https://www.linkedin.com/in/charles-r-dyke
 * License:           GPL-2.0-or-later
 * Text Domain:       ama-weather
 */

// setup the settings page for this plugin
require_once(plugin_dir_path( __FILE__ ) . 'src/settings.php');


// setup the custom API routes
require_once(plugin_dir_path( __FILE__ ) . 'src/routes.php');

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/writing-your-first-block-type/
 */
add_action( 'init', 'create_block_ama_weather_block_init' );
function create_block_ama_weather_block_init() {
	register_block_type_from_metadata( __DIR__, [
		'render_callback' => 'render_weather_block',
	] );
}

function render_weather_block( array $attributes ): string {
	
	// setup variables
	$class = 'ama-weather-block';
	$title_class = 'ama-weather-block-title';
	$temperature = '';
	
	// get the block attributes
	$title = $attributes['content'];

	// get the current temperature
	$temperature = getCurrentTemperature();

	ob_start();
	?>
    <div class="<?php echo esc_attr( $class ); ?>">
        <!-- Block title here -->
        <p class="<?php echo esc_attr( $title_class ); ?>"><?php echo $title; ?></p>

        <!-- Current temperature here -->
        <?php if ($temperature == ''): ?>

        	<p class="ama-weather-block-not-found">Please enter a Zip Code and API Key in the plugin settings.</p>

        <?php else: ?>
	        
	        <?php echo $temperature; ?>
	    
	    <?php endif; ?>
    </div>
	<?php
	return ob_get_clean();
}


