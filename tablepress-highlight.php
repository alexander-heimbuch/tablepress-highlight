<?php
/**
 * WordPress plugin "TablePress Highlight" main file, responsible for initiating the plugin
 *
 * @package TablePress Plugins
 * @author Alexander Heimbuch
 * @version 0.1
 */

/*
Plugin Name: TablePress Extension: Highlight
Plugin URI: http://aktivstoff.de/
Description: Extend TablePress tables with the ability to highlight rows and columns on hover
Version: 0.1
Author: Alexander Heimbuch
Author URI: http://aktivstoff.de
Author email: kontakt@aktivstoff.de
Text Domain: tablepress
Domain Path: /i18n
License: GPL 2
*/

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

add_action( 'tablepress_run', array( 'TablePress_Highlight', 'init' ) );

class TablePress_Highlight {

    protected static $slug = 'tablepress-highlight';
    protected static $version = '0.1';

    public static function init() {
        add_filter( 'tablepress_shortcode_table_default_shortcode_atts', array( __CLASS__, 'shortcode_table_default_shortcode_atts' ) );
        add_filter( 'tablepress_table_render_options', array( __CLASS__, 'table_render_options' ), 10, 2 );
        add_filter( 'tablepress_table_js_options', array( __CLASS__, 'table_js_options' ), 10, 3 );
        add_filter( 'tablepress_table_output', array( __CLASS__, 'table_output' ), 10, 3 );
    }

    public static function shortcode_table_default_shortcode_atts( $default_atts ) {
        $default_atts['highlight-rows'] = '';
        $default_atts['highlight-cols'] = '';

        return $default_atts;
    }

    public static function table_render_options( $render_options, $table ) {
        if ( strlen( $render_options['highlight-rows'] ) == 0 ) {
            $render_options['highlight-rows'] = null;
        } else {
            $render_options['highlight-rows'] = trim( $render_options['highlight-rows'] );
        }

        if ( strlen( $render_options['highlight-cols'] ) == 0 ) {
            $render_options['highlight-cols'] = null;
        } else {
            $render_options['highlight-cols'] = trim( $render_options['highlight-cols'] );
        }

        return $render_options;
    }

    public static function table_js_options( $js_options, $table_id, $render_options ) {
        if( !$render_options['highlight-rows'] && ! $render_options['highlight-cols'] ) {
            return $js_options;
        }

        wp_enqueue_script( self::$slug, plugins_url( 'tablepress-highlight.js', __FILE__ ), array( 'tablepress-datatables' ), self::$version, true );

        return $js_options;
    }

    public static function table_output( $output, $table, $render_options ) {
        if( !$render_options['highlight-rows'] && ! $render_options['highlight-cols'] ) {
            return $output;
        }

        $options = array();

        $options['rows'] = $render_options['highlight-rows'];
        $options['cols'] = $render_options['highlight-cols'];

        return $output . '<script>
            if (window.TABLE_HIGHLIGHT === undefined) {
                window.TABLE_HIGHLIGHT = {};
            }

            window.TABLE_HIGHLIGHT["' . $render_options['html_id'] . '"] = JSON.parse(\'' . json_encode( $options ) . '\');
        </script>';
    }
}
?>
