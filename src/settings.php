<?php

/**
 * custom option and settings
 */
function ama_weather_settings_init() {
    // Register a new zip code setting
    register_setting( 'ama_weather', 'ama_weather_settings_zipcode' );

    // Register a new API key setting
    register_setting( 'ama_weather', 'ama_weather_settings_apikey' );

    // register a new Open Weather Map section
    add_settings_section(
        'ama_weather_openweathermap_section',
        'Open Weather Map', 'ama_weather_openweathermap_section_callback',
        'ama_weather'
    );
 
    // register a new zip code field
    add_settings_field(
        'ama_weather_settings_zipcode',
        'Zip Code', 'ama_weather_openweathermap_zipcode_callback',
        'ama_weather',
        'ama_weather_openweathermap_section'
    );

    // register a API key field
    add_settings_field(
        'ama_weather_settings_apikey',
        'API Key', 'ama_weather_openweathermap_apikey_callback',
        'ama_weather',
        'ama_weather_openweathermap_section'
    );
}

/**
 * Register our ama_weather_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'ama_weather_settings_init' );


/**
 * Custom option and settings:
 *  - callback functions
 */


// openweathermap section content callback
function ama_weather_openweathermap_section_callback() {
    echo '<p>Enter a valid zip code and Open Weather Map API Key below.</p>';
}
 
// zip code field content callback
function ama_weather_openweathermap_zipcode_callback() {
    // get the value of the setting we've registered with register_setting()
    $setting = get_option('ama_weather_settings_zipcode');
    // output the field
    ?>
    <input type="text" name="ama_weather_settings_zipcode" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
    <?php
}

// API key field content callback
function ama_weather_openweathermap_apikey_callback() {
    // get the value of the setting we've registered with register_setting()
    $setting = get_option('ama_weather_settings_apikey');
    // output the field
    ?>
    <input type="text" name="ama_weather_settings_apikey" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
    <?php
}


/**
 * Add the top level menu page.
 */
function ama_weather_options_page() {
    add_menu_page(
        'AMA Weather',
        'AMA Weather Options',
        'manage_options',
        'ama_weather',
        'ama_weather_options_page_html'
    );
}


/**
 * Register our ama_weather_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'ama_weather_options_page' );


/**
 * Top level menu callback function
 */
function ama_weather_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
 
    // add error/update messages
 
    // check if the user has submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'ama_weather_messages', 'ama_weather_message', __( 'Settings Saved', 'ama_weather' ), 'updated' );
    }
 
    // show error/update messages
    settings_errors( 'ama_weather_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "ama_weather"
            settings_fields( 'ama_weather' );
            // output setting sections and their fields
            // (sections are registered for "ama_weather", each field is registered to a specific section)
            do_settings_sections( 'ama_weather' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}

