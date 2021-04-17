<?php

// setup the custom API route for getting the temperature
add_action( 'rest_api_init', function () {
	register_rest_route( 'ama-weather/v1', '/temperature', array(
		'methods' => 'GET',
		'callback' => 'getRestCurrentTemperature',
		'args' => array(
			'units' => array(
				'type' => 'string',
				'enum' => array( 'imperial', 'metric', 'standard' ),
				'validate_callback' => 'prefix_units_arg_validate_callback',
				'sanitize_callback' => 'prefix_units_arg_sanitize_callback',
			),
		),
		'permission_callback' => function () {
			return current_user_can( 'edit_others_posts' );
		}
	) );
} );


// callback to validate the units argument
function prefix_units_arg_validate_callback( $value, $request, $param )
{
	// If the argument is not a string then return an error
    if ( ! is_string( $value ) ) {
        return new WP_Error( 'rest_invalid_param', esc_html__( 'The argument must be a string.', 'ama-weather' ), array( 'status' => 400 ) );
    }
 
    // Get the registered attributes for this endpoint request
    $attributes = $request->get_attributes();
 
    // Grab the filter param schema
    $args = $attributes['args'][ $param ];
 
    // If the filter param is not a value in our enum then we should return an error
    if ( ! in_array( $value, $args['enum'], true ) ) {
        return new WP_Error( 'rest_invalid_param', sprintf( __( '%s is not one of %s' ), $param, implode( ', ', $args['enum'] ) ), array( 'status' => 400 ) );
    }
}


// callback to sanitize the units argument
function prefix_units_arg_sanitize_callback( $value, $request, $param )
{
	return sanitize_text_field( $value );
}


// get the current temperature from the Rest API (for the wp editor)
function getRestCurrentTemperature( WP_REST_Request $request )
{
	// get the units parameter
	$units = $request['units'];

	// get the temperature
	$temperature = getCurrentTemperature($units);

	// return the response to the browser
	return rest_ensure_response($temperature);
}


// get the current temperature from the API
function getCurrentTemperature( string $units = 'imperial' ) : string
{
	// get the saved settings
	$apikey = get_option('ama_weather_settings_apikey');
	$zipcode = get_option('ama_weather_settings_zipcode');

	// setup variables
	$units_value = ($units == 'imperial') ? 'F' : ( ($units == 'metric') ? 'C' : 'K' );
	$units_html = '<sup class="ama-weather-block-temperature-units">&#730;' . $units_value . '</sup>';

	// IF the zipcode and apikey exist, then get the weather values
	if ($apikey && $zipcode)
	{
		// IF the temperature transient value is not found, then make a new api request
		if ( false === ( $temperature = get_transient('ama_weather_temperature') ) )
		{
			// create the url
			$url = 'https://api.openweathermap.org/data/2.5/weather?zip=' . $zipcode . ',us&units=' . $units . '&appid=' . $apikey;

			// get the response
			$response = wp_remote_get($url);

			// parse the response
			$json = json_decode($response['body']);
			
			// get the values from the response
			$temperature_value = round($json->main->temp);

			// save the value in a transient that expires after 1 hour
			set_transient('ama_weather_temperature', $temperature_value, 60 * MINUTE_IN_SECONDS);
		}
		else
		{
			// get the value from the saved transient
			$temperature_value = get_transient('ama_weather_temperature');
		}

		// setup the temperature variable
		$temperature = '<p class="ama-weather-block-temperature">' . $temperature_value . $units_html . '</p>';
	}
	else
	{
		// the zipcode or apikey were not saved in the settings, so return not found
		$temperature = '';
	}

	return $temperature;
}

