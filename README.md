# Live Example

URL: https://ama.secondchancemtg.com/weather-post/

# American Marketing Association Coding Assignment

Thank you so much for your interest in the software developer position at AMA. We're thrilled that you are taking the
next step in our hiring process.

You will have 5 business days from the date you receive this assignment to finish this task

## Prerequisites
* Node v14
* PHP 7+
* A functioning local WordPress site
* An API key for OpenWeather. You should have received this with your invitation email.

## How to complete this assignment
1. Clone this repo to your local WordPress plugins directory.
2. Finish the plugin to fulfill the requirements.
3. When complete, push up your work to your own Github repo and send us the link!

Please don't hesitate to reach out if you have any questions or need clarification on this assigment!

## The Problem
One of the AMA chapters has requested a weather widget for their site. You have met with the chapter volunteers to
gather all of the requirements:
1. The widget will be a Gutenberg block that allows it to be placed on any page or post.
1. The widget must only show the current temperature of a given zip code. 
1. The title of the widget can be set with a block attribute.
1. The widget will look the same whether you are in the post editor or on the frontend of the site.
1. Weather data must be retrieved through the API from https://openweathermap.org/current
   
## Technical requirements
1. The desired zip code and API key should be saved in a settings page on the WP admin. Please use the [WP Settings API](https://developer.wordpress.org/plugins/settings/settings-api/).
1. When retrieving the current weather data in the post editor, use a custom REST API route.
1. The weather data should be stored in a transient after retrieval to reduce the number of external API calls.

## Notes
* We have created this boilerplate plugin for you to get you up and running quickly. It is based on
[@wordpress/create-block](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/).
  The base block should be available in the post editor after activating this plugin.
* Don't forget good WP security practices (data validation, sanitization, etc.).
* This assignment should take less than 6 hours to complete.
