<?php
/**
 * Plugin Name: Visual Composer Maced Google Maps
 * Plugin URI:
 * Version: 1.2.9
 * Author: macerier
 * Author URI:
 * Description: Simply creates google maps with Visual Composer or via shortcode. Modified by Dan Fisher
 * License: GPL2
 */
class vcMacedGmap {

    function __construct() {
        // Plugin Details
        $this->plugin = new stdClass();
        $this->plugin->name = 'visual-composer-maced-google-maps'; // Plugin Folder
        $this->plugin->displayName = 'Visual Composer Maced Google Maps'; // Plugin Name
        $this->plugin->version = '1.2.9';
        $this->plugin->folder = WP_PLUGIN_DIR . '/' . $this->plugin->name; // Full Path to Plugin Folder
        $this->plugin->url = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));

        add_action('plugins_loaded', array(
            &$this,
            'loadLanguageFiles'
        ));
        add_shortcode('vcmacedgmap', array(
            &$this,
            'GmapShortcode'
        ));

        if (function_exists('vc_map')) {
            vc_map(array(
                'name' => __('Maced Google Maps', $this->plugin->name),
                'base' => 'vcmacedgmap',
                'description' => __('Visual Composer Maced Google Maps', $this->plugin->name),
                'category' => __('Content', $this->plugin->name),
                'icon' => 'icon-wpb-map-pin',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('Google Map API Key', $this->plugin->name),
                        'param_name' => 'api',
                        'value' => '',
                        'description' => __('Usage of the Google Maps APIs now requires a key. Please read more about <a href="https://danfisher.ticksy.com/article/7834">here</a>', $this->plugin->name)
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Google Maps Lat', $this->plugin->name),
                        'param_name' => 'lat',
                        'value' => '40.665105',
                        'description' => __('The map will appear only if this field is filled correctly.<br />Example: <b>40.665105</b>', $this->plugin->name)
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Google Maps Lng', $this->plugin->name),
                        'param_name' => 'lng',
                        'value' => '-73.993928',
                        'description' => __('The map will appear only if this field is filled correctly.<br />Example: <b>-73.993928</b>', $this->plugin->name)
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Zoom', $this->plugin->name),
                        'param_name' => 'zoom',
                        'value' => '13'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Height', $this->plugin->name),
                        'param_name' => 'height',
                        'value' => '200'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Controls', $this->plugin->name),
                        'param_name' => 'controls',
                        'value' => array(
                            '' => __('Zoom', $this->plugin->name),
                            'mapType' => __('Map Type', $this->plugin->name),
                            'streetView' => __('Street View', $this->plugin->name),
                            'zoom mapType' => __('Zoom & Map Type', $this->plugin->name),
                            'zoom streetView' => __('Zoom & Street View', $this->plugin->name),
                            'mapType streetView' => __('Map Type & Street View', $this->plugin->name),
                            'zoom mapType streetView' => __('Zoom, Map Type & Street View', $this->plugin->name),
                            'hide' => __('Hide All', $this->plugin->name)
                        )
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Border', $this->plugin->name),
                        'param_name' => 'border',
                        'value' => array(
                            0 => __('No', $this->plugin->name),
                            1 => __('Yes', $this->plugin->name)
                        ),
                        'description' => __('Show map border', $this->plugin->name)
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Shape', 'js_composer'),
                        'param_name' => 'shape',
                        'value' => array(
                            __( 'Default', $this->plugin->name ) => 'default',
                            __( 'Top Wave', $this->plugin->name ) => 'top_wave',

                        ),
                        'description' => __('Choose the shape type for the map.', $this->plugin->name)
                    ),
                    array(
                        'type'        => 'checkbox',
                        'heading'     => __( 'Add Map Toggle?', $this->plugin->name ),
                        'param_name'  => 'toggle',
                        'description' => __( 'You can open and close the map.', $this->plugin->name ),
                        'value'       => array(
                            __( 'Yes', $this->plugin->name ) => 'true'
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Toggle Open Text', $this->plugin->name),
                        'param_name' => 'toggle_open',
                        'value' => 'Open Google Map',
                        'description' => __('Text for Toggle Link if map is closed.', $this->plugin->name)
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Toggle Closed Text', $this->plugin->name),
                        'param_name' => 'toggle_closed',
                        'value' => 'Close Google Map',
                        'description' => __('Text for Toggle Link if map is open.', $this->plugin->name)
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Marker Icon', 'js_composer'),
                        'param_name' => 'icn',
                        'value' => '',
                        'description' => __('Select image from media library. Use  .png for best results.', $this->plugin->name)
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Styles', 'js_composer'),
                        'param_name' => 'styles',
                        'value' => array(
                            __( 'Default', $this->plugin->name ) => 'default',
                            __( 'Ultra Light with Labels', $this->plugin->name ) => 'ultra_light_with_labels',
                            __( 'Unsaturated Browns', $this->plugin->name ) => 'unsaturated_browns',
                            __( 'Subtle Grayscale', $this->plugin->name ) => 'subtle_grayscale',
                            __( 'Shades of Grey', $this->plugin->name ) => 'shades_of_grey',
                            __( 'Blue water', $this->plugin->name ) => 'blue_water',
                            __( 'Pale Dawn', $this->plugin->name ) => 'pale_dawn',
                            __( 'Apple Maps-esque', $this->plugin->name ) => 'apple_maps_esque',
                            __( 'Blue Essence', $this->plugin->name ) => 'blue_essence',
                            __( 'Light Dream', $this->plugin->name ) => 'light_dream',
                            __( 'Midnight Commander', $this->plugin->name ) => 'midnight_commander',
                            __( 'Light Monochrome', $this->plugin->name ) => 'light_monochrome',
                            __( 'Paper', $this->plugin->name ) => 'paper',
                            __( 'Retro', $this->plugin->name ) => 'retro',

                        ),
                        'description' => __('Choose one of predefined Google Map styles. See examples on http://snazzymaps.com', $this->plugin->name)
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => __('Additional Markers | Lat,Lng', $this->plugin->name),
                        'param_name' => 'latlng',
                        'value' => '',
                        'description' => __('Separate Lat,Lang with <b>coma</b> [ , ]<br />Separate multiple Markers with <b>semicolon</b> [ ; ]<br />Example: <b>-33.88,151.21;-33.89,151.22</b>', $this->plugin->name)
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Box | Title', $this->plugin->name),
                        'param_name' => 'titl',
                        'value' => ''
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => __('Box | Address', $this->plugin->name),
                        'param_name' => 'content',
                        'value' => '',
                        'description' => __('HTML and shortcodes tags allowed.', $this->plugin->name)
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Box | Telephone', $this->plugin->name),
                        'param_name' => 'telephone',
                        'value' => ''
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Box | Email', $this->plugin->name),
                        'param_name' => 'email',
                        'value' => ''
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Box | Website', $this->plugin->name),
                        'param_name' => 'www',
                        'value' => ''
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Custom | Classes', $this->plugin->name),
                        'param_name' => 'classes',
                        'value' => '',
                        'description' => __('Custom CSS Item Classes Names. Multiple classes should be separated with SPACE', $this->plugin->name)
                    )
                )
            ));
        }
    }

    function GmapShortcode($attr, $content = null)
    {
        extract(shortcode_atts(array(
            'lat' => '40.665105',
            'lng' => '-73.993928',
            'api' => '',
            'zoom' => 13,
            'height' => 200,
            'controls' => '',
            'border' => '',
            'toggle' => '',
            'toggle_open' => 'Open Google Map',
            'toggle_closed' => 'Close Google Map',
            'shape' => '',
            'icn' => '',
            'styles' => '',
            'titl' => '',
            'telephone' => '',
            'email' => '',
            'www' => '',
            'latlng' => '',
            'uid' => uniqid()
        ), $attr));

        // border
        if ($border) {
            $class = 'has_border';
        } else {
            $class = 'no_border';
        }
        if ($icn) {
            $icn = wp_get_attachment_url($icn);
        }

        // controls
        $zoomControl = $mapTypeControl = $streetViewControl = 'false';
        if (! $controls)
            $zoomControl = 'true';
        if (strpos($controls, 'zoom') !== false)
            $zoomControl = 'true';
        if (strpos($controls, 'mapType') !== false)
            $mapTypeControl = 'true';
        if (strpos($controls, 'streetView') !== false)
            $streetViewControl = 'true';

        $google_api_key = '';
        if ( $api != '' ) {
            $google_api_key = '&key=' . $api;
        }

        wp_enqueue_script('google-maps', '//maps.google.com/maps/api/js?sensor=false' . esc_attr( $google_api_key ), false, $this->plugin->version, true);
        // wp_enqueue_style($this->plugin->name . '-base', plugins_url($this->plugin->name . '/css/base.css', $this->plugin->name), false, $this->plugin->version);

        $output = '<script>';

        $output .= '(function($, window) {';
            $output .= '$(document).ready(function($) {';

            // <![CDATA[
            $output .= 'function google_maps_' . $uid . '(){';

            $output .= 'var latlng = new google.maps.LatLng(' . $lat . ',' . $lng . ');';

            $output .= 'var myOptions = {';
            $output .= 'zoom                : ' . intval($zoom) . ',';
            $output .= 'center              : latlng,';
            $output .= 'mapTypeId           : google.maps.MapTypeId.ROADMAP,';
            if ( $styles == 'ultra_light_with_labels') {
                $output .= 'styles  : [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}],';
            } elseif ( $styles == 'unsaturated_browns') {
                $output .= 'styles  : [{"elementType":"geometry","stylers":[{"hue":"#ff4400"},{"saturation":-68},{"lightness":-4},{"gamma":0.72}]},{"featureType":"road","elementType":"labels.icon"},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"hue":"#0077ff"},{"gamma":3.1}]},{"featureType":"water","stylers":[{"hue":"#00ccff"},{"gamma":0.44},{"saturation":-33}]},{"featureType":"poi.park","stylers":[{"hue":"#44ff00"},{"saturation":-23}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"hue":"#007fff"},{"gamma":0.77},{"saturation":65},{"lightness":99}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"gamma":0.11},{"weight":5.6},{"saturation":99},{"hue":"#0091ff"},{"lightness":-86}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"lightness":-48},{"hue":"#ff5e00"},{"gamma":1.2},{"saturation":-23}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"saturation":-64},{"hue":"#ff9100"},{"lightness":16},{"gamma":0.47},{"weight":2.7}]}],';

            } elseif ( $styles == 'subtle_grayscale') {
                $output .= 'styles  : [{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}],';

            } elseif ( $styles == 'shades_of_grey') {
                $output .= 'styles  : [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}],';

            } elseif ( $styles == 'blue_water') {
                $output .= 'styles  : [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]}],';

            } elseif ( $styles == 'pale_dawn') {
                $output .= 'styles  : [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2e5d4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]}],';

            } elseif ( $styles == 'apple_maps_esque') {
                $output .= 'styles  : [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}],';

            } elseif ( $styles == 'blue_essence') {
                $output .= 'styles  : [{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#e0efef"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"hue":"#1900ff"},{"color":"#c0e8e8"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"visibility":"on"},{"lightness":700}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#7dcdcd"}]}],';

            } elseif ( $styles == 'light_dream') {
                $output .= 'styles  : [{"featureType":"landscape","stylers":[{"hue":"#FFBB00"},{"saturation":43.400000000000006},{"lightness":37.599999999999994},{"gamma":1}]},{"featureType":"road.highway","stylers":[{"hue":"#FFC200"},{"saturation":-61.8},{"lightness":45.599999999999994},{"gamma":1}]},{"featureType":"road.arterial","stylers":[{"hue":"#FF0300"},{"saturation":-100},{"lightness":51.19999999999999},{"gamma":1}]},{"featureType":"road.local","stylers":[{"hue":"#FF0300"},{"saturation":-100},{"lightness":52},{"gamma":1}]},{"featureType":"water","stylers":[{"hue":"#0078FF"},{"saturation":-13.200000000000003},{"lightness":2.4000000000000057},{"gamma":1}]},{"featureType":"poi","stylers":[{"hue":"#00FF6A"},{"saturation":-1.0989010989011234},{"lightness":11.200000000000017},{"gamma":1}]}],';

            } elseif ( $styles == 'midnight_commander') {
                $output .= 'styles  : [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"color":"#000000"},{"lightness":13}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#144b53"},{"lightness":14},{"weight":1.4}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#08304b"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#0c4152"},{"lightness":5}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#0b434f"},{"lightness":25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#0b3d51"},{"lightness":16}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"transit","elementType":"all","stylers":[{"color":"#146474"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#021019"}]}],';

            } elseif ( $styles == 'light_monochrome') {
                $output .= 'styles  : [{"featureType":"administrative.locality","elementType":"all","stylers":[{"hue":"#2c2e33"},{"saturation":7},{"lightness":19},{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":-2},{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"hue":"#e9ebed"},{"saturation":-90},{"lightness":-8},{"visibility":"simplified"}]},{"featureType":"transit","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":10},{"lightness":69},{"visibility":"on"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":-78},{"lightness":67},{"visibility":"simplified"}]}],';

            } elseif ( $styles == 'paper') {
                $output .= 'styles  : [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"simplified"},{"hue":"#0066ff"},{"saturation":74},{"lightness":100}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"off"},{"weight":0.6},{"saturation":-85},{"lightness":61}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"simplified"},{"color":"#5f94ff"},{"lightness":26},{"gamma":5.86}]}],';

            } elseif ( $styles == 'retro') {
                $output .= 'styles  : [{"featureType":"administrative","stylers":[{"visibility":"off"}]},{"featureType":"poi","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"water","stylers":[{"visibility":"simplified"}]},{"featureType":"transit","stylers":[{"visibility":"simplified"}]},{"featureType":"landscape","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"visibility":"off"}]},{"featureType":"road.local","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"water","stylers":[{"color":"#84afa3"},{"lightness":52}]},{"stylers":[{"saturation":-17},{"gamma":0.36}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#3f518c"}]}],';

            }
            $output .= 'zoomControl         : ' . $zoomControl . ',';
            $output .= 'mapTypeControl      : ' . $mapTypeControl . ',';
            $output .= 'streetViewControl   : ' . $streetViewControl . ',';
            $output .= 'scrollwheel         : false';
            $output .= '};';

            $output .= 'var map = new google.maps.Map(document.getElementById("google-map-area-' . $uid . '"), myOptions);';

            $output .= 'var marker = new google.maps.Marker({';
            $output .= 'position            : latlng,';
            if ($icn)
                $output .= 'icon    : "' . $icn . '",';
            $output .= 'map                 : map';
            $output .= '});';

            // additional markers
            if ($latlng) {

                // remove white spaces
                $latlng = str_replace(' ', '', $latlng);

                // explode array
                $latlng = explode(';', $latlng);

                // set bounds
                $output .= 'var bounds = new google.maps.LatLngBounds();';

                foreach ($latlng as $k => $v) {

                    $markerID = $k + 1;
                    $markerID = 'marker' . $markerID;

                    $output .= 'var ' . $markerID . ' = new google.maps.Marker({';
                    $output .= 'position            : new google.maps.LatLng(' . $v . '),';
                    if ($icn)
                        $output .= 'icon    : "' . $icn . '",';
                    $output .= 'map                 : map';
                    $output .= '});';

                    $output .= 'bounds.extend(new google.maps.LatLng(' . $v . '));';
                }

                $output .= 'bounds.extend(latlng);map.fitBounds(bounds);';
            }

            $output .= '}';

            $output .= 'google_maps_' . $uid . '();';

                if ( $toggle == 'true') {
                    $output .= 'var button     = $("#gMapTrigger_' . $uid . '");';
                    $output .= 'var button_txt = $("#gMapTrigger_' . $uid . ' span");';

                    $output .= '$("#gMapWrapper_' . $uid . '").on("hidden.bs.collapse", function () {';
                        $output .= 'button_txt.data("text-original", button_txt.text());';
                        $output .= 'button_txt.text(button.data("text-swap"));';
                    $output .= '});';
                    $output .= '$("#gMapWrapper_' . $uid . '").on("shown.bs.collapse", function () {';
                        $output .= 'button_txt.data("text-swap", button.text());';
                        $output .= 'button_txt.text(button_txt.data("text-original"));';
                    $output .= '});';
                }
            $output .= '});';
        $output .= '})(jQuery, window);';
        // ]]>
        $output .= '</script>' . "\n";

        $output .= '<div class="google-map-wrapper ' . $class . '" id="gmapHolder_' . $uid . '">';

        $rocket_data = get_option('rocket_data');
        if ( isset($rocket_data['rocket__opt_content_bg_color']) || !empty($rocket_data['rocket__opt_content_bg_color']) ) {
          $separator_shape_background = $rocket_data['rocket__opt_content_bg_color'];
        } else {
          $separator_shape_background = '#f1f2f4';
        }

        if ( $toggle == 'true') {
            $output .= '<div class="hr-scroll-bottom"><a data-toggle="collapse" data-parent="#gmapHolder_' . $uid . '" href="#gMapWrapper_' . $uid . '" aria-expanded="true" aria-controls="gMapWrapper_' . $uid . '" id="gMapTrigger_' . $uid . '" data-text-swap="' . $toggle_open . '"><span>' . $toggle_closed . '</span></a></div>';
            if ($shape == 'top_wave') {
                $shape = 'google-map-shap-wave-top';
            }
            $output .= '<div class="google-map-inner-wrapper collapse in" id="gMapWrapper_' . $uid . '">';
                $output .= '<svg class="google-map-separator" xmlns="http://www.w3.org/2000/svg" version="1.0" width="1200" fill="' . $separator_shape_background . '" height="30" viewBox="0 0 1200 30" preserveAspectRatio="none"><path d="M0,0S1.209,1.508,200.671,7.031C375.088,15.751,454.658,30,600,30V0H0ZM1200,0s-90.21,1.511-200.671,7.034C824.911,15.751,745.342,30,600,30V0h600Z"/></svg>';
                $output .= '<div class="google-map" id="google-map-area-' . $uid . '" style="width:100%; height:' . intval($height) . 'px;">&nbsp;</div>';
            $output .= '</div>';
        } else {
            $output .= '<div class="google-map" id="google-map-area-' . $uid . '" style="width:100%; height:' . intval($height) . 'px;">&nbsp;</div>';
        }

        if ($titl || $content) {

            $output .= '<div class="google-map-contact-wrapper" id="gMapWrapper_' . $uid . '">';
            $output .= '<div class="get_in_touch">';
            if ($titl)
                $output .= '<h5>' . $titl . '</h5>';
            $output .= '<div class="get_in_touch_wrapper">';
            $output .= '<ul>';
            if ($content) {
                $output .= '<li class="address">';
                $output .= '<span class="icon"><i class="fa fa-map"></i></span>';
                $output .= '<span class="address_wrapper">' . do_shortcode($content) . '</span>';
                $output .= '</li>';
            }
            if ($telephone) {
                $output .= '<li class="phone">';
                $output .= '<span class="icon"><i class="fa fa-phone"></i></span>';
                $output .= '<p><a href="tel:' . str_replace(' ', '', $telephone) . '">' . $telephone . '</a></p>';
                $output .= '</li>';
            }
            if ($email) {
                $output .= '<li class="mail">';
                $output .= '<span class="icon"><i class="fa fa-envelope"></i></span>';
                $output .= '<p><a href="mailto:' . $email . '">' . $email . '</a></p>';
                $output .= '</li>';
            }
            if ($www) {
                $output .= '<li class="www">';
                $output .= '<span class="icon"><i class="fa fa-link"></i></span>';
                $output .= '<p><a target="_blank" href="http://' . $www . '">' . $www . '</a></p>';
                $output .= '</li>';
            }
            $output .= '</ul>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }

        $output .= '</div>' . "\n";


        return $output;
    }

    /**
     * Loads plugin textdomain
     */
    function loadLanguageFiles()
    {
        load_plugin_textdomain($this->plugin->name, false, $this->plugin->name . '/languages/');
    }
}
$vcMacedGmap = new vcMacedGmap();
