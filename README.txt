# CF Custom Image Sizes README

The CF Custom Image Sizes plugin creates an easy way to add custom image sizes to WordPress.  Custom sizes are added via a filter `cf_custom_image_sizes`.

## Implementation

This plugin is implemented by adding a filter to the `cf_custom_image_sizes` filter application.  The added filter could be coded either into another plugin or into the functions.php file in the theme.

## Dependencies

There are no dependencies for this plugin, beyond PHP extensions (e.g., "GD") which are required by WordPress Core, to manipulate image sizes.  If WordPress can create the custom thumbnail sizes, etc., this plugin will work.

## Example

	function cf_add_custom_image_sizes_config($sizes) {
		$cf_custom_image_sizes = array(
			'new_rectangular_logo' => array(
				'size_w' => 95,
				'size_h' => 37,
				'crop' => true
			),
			'nifty_square_logo' => array(
				'size_w' => 50,
				'size_h' => 50,
				'crop' => false
			),
		);
		return array_merge($sizes, $cf_custom_image_sizes);
	}
	add_filter('cf_custom_image_sizes', 'cf_add_custom_image_sizes_config');

## Available Filters

`cf_custom_image_sizes` => defines which new sizes WordPress will create
