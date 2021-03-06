<?php

    /**
     * ReduxFramework Barebones Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }

    // This is your option name where all the Redux data is stored.
    $opt_name = "grosh_meta";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => __( 'Grosh Options', 'redux-framework-demo' ),
        'page_title'           => __( 'Grosh Theme Options', 'redux-framework-demo' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => true,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => true,
        // Show the time the page took to load, etc
        'update_notice'        => true,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '_options',
        // Page slug used to denote the panel
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!

        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        //'compiler'             => true,

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'light',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $args['admin_bar_links'][] = array(
        'id'    => 'redux-docs',
        'href'  => '#',
        'title' => __( 'Documentation', 'redux-framework-demo' ),
    );

    $args['admin_bar_links'][] = array(
        //'id'    => 'redux-support',
        'href'  => '#',
        'title' => __( 'Support', 'redux-framework-demo' ),
    );

    $args['admin_bar_links'][] = array(
        'id'    => 'redux-extensions',
        'href'  => '#',
        'title' => __( 'Extensions', 'redux-framework-demo' ),
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'][] = array(
        'url'   => '#',
        'title' => 'Visit us on GitHub',
        'icon'  => 'el el-github'
        //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
    );
    $args['share_icons'][] = array(
        'url'   => '#',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => '#',
        'title' => 'Follow us on Twitter',
        'icon'  => 'el el-twitter'
    );
    $args['share_icons'][] = array(
        'url'   => 'h#',
        'title' => 'Find us on LinkedIn',
        'icon'  => 'el el-linkedin'
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '', 'redux-framework-demo' ), $v );
    } else {
        $args['intro_text'] = __( '', 'redux-framework-demo' );
    }

    // Add content after the form.
    $args['footer_text'] = __( '' );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */

    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1', 'redux-framework-demo' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'redux-framework-demo' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */

    // -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title' => __( 'Options', 'redux-framework-demo' ),
        'id'    => 'basic',
        'desc'  => __( 'Basic fields as subsections.', 'redux-framework-demo' ),
        'icon'  => 'el el-home'
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Homepage', 'redux-framework-demo' ),
        'desc'       => '',
        'id'         => 'homepage-subsection',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'slider-homepage-select',
                'type'     => 'select',
                'multi'    => true,
                'title'    => __('Slider', 'redux-framework-demo'), 
                'desc'     => __('You can change your homepage slider.', 'redux-framework-demo'),
                'data'  => 'posts',
                'args'  => array(
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                    'orderby'        => 'title',
                    'order'          => 'ASC',
                )
            ),
            
            array(
                'id'       => 'title-step',
                'type'     => 'text',
                'title'    => __('Text Step', 'redux-framework-demo'),
                'subtitle' => __('', 'redux-framework-demo'),
                'desc'     => __('Change text for step how it works', 'redux-framework-demo'),
                'validate' => '',
                'msg'      => '',
                'default'  => 'How it works' 
            ),
            array(
                'id'          => 'show-step',
                'type'        => 'slides',
                'title'       => __('Show Step', 'redux-framework-demo'),
                'subtitle'    => __('You can change and updated Step How it Works', 'redux-framework-demo'),
                'placeholder' => array(
                    'title'           => __('Title', 'redux-framework-demo'),
                    'description'     => __('Description', 'redux-framework-demo'),
                    'url'             => __('URL', 'redux-framework-demo'),
                ),
            ),
            array(
                'id'          => 'show-slides',
                'type'        => 'slides',
                'title'       => __('Show Options', 'redux-framework-demo'),
                'subtitle'    => __('You can change and updated Show Option on Homepage', 'redux-framework-demo'),
                'placeholder' => array(
                    'title'           => __('Title', 'redux-framework-demo'),
                    'description'     => __('Description', 'redux-framework-demo'),
                    'url'             => __('URL', 'redux-framework-demo'),
                ),
            ),
            array(
                'id'       => 'title-popular-scenes',
                'type'     => 'text',
                'title'    => __('Text Popular Scenes', 'redux-framework-demo'),
                'subtitle' => __('', 'redux-framework-demo'),
                'desc'     => __('Change text for Popular Scene', 'redux-framework-demo'),
                'validate' => '',
                'msg'      => '',
                'default'  => 'Popular Scenes' 
            ),
            array(
                'id'       => 'feature-product-home',
                'type'     => 'select',
                'multi'    => true,
                'title'    => __( 'Select Product', 'redux-framework-demo' ),
                'desc'     => __( 'Product feature on homepage', 'redux-framework-demo' ),
                'data'  => 'posts',
                'args'  => array(
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                    'orderby'        => 'title',
                    'order'          => 'ASC',
                )
            )
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Product Rental Price', 'redux-framework-demo' ),
        'desc'       => '',
        'id'         => 'price-subsection',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'base-image-price',
                'type'     => 'text',
                'title'    => __('Base Image Price', 'redux-framework-demo'),
                'subtitle' => __('', 'redux-framework-demo'),
                'desc'     => __('', 'redux-framework-demo'),
                'validate' => 'numeric',
                'msg'      => 'Only accept number'
            ),
            array(
                'id'       => 'recurring-image-price',
                'type'     => 'text',
                'title'    => __('Recurring Image Price', 'redux-framework-demo'),
                'subtitle' => __('', 'redux-framework-demo'),
                'desc'     => __('', 'redux-framework-demo'),
                'validate' => 'numeric',
                'msg'      => 'Only accept number'
            ),
            array(
                'id'       => 'base-motion-price',
                'type'     => 'text',
                'title'    => __('Base Motion Price', 'redux-framework-demo'),
                'subtitle' => __('', 'redux-framework-demo'),
                'desc'     => __('', 'redux-framework-demo'),
                'validate' => 'numeric',
                'msg'      => 'Only accept number'
            ),
            array(
                'id'       => 'recurring-motion-price',
                'type'     => 'text',
                'title'    => __('Recurring Motion Price', 'redux-framework-demo'),
                'subtitle' => __('', 'redux-framework-demo'),
                'desc'     => __('', 'redux-framework-demo'),
                'validate' => 'numeric',
                'msg'      => 'Only accept number'
            ),
            array(
                'id'       => 'package-bundle-price',
                'type'     => 'text',
                'title'    => __('Package Bundle Price', 'redux-framework-demo'),
                'subtitle' => __('', 'redux-framework-demo'),
                'desc'     => __('', 'redux-framework-demo'),
                'validate' => 'numeric',
                'msg'      => 'Only accept number'
            ),
            array(
                'id'       => 'recurring-bundle-package-price',
                'type'     => 'text',
                'title'    => __('Recurring Package Bundle Price', 'redux-framework-demo'),
                'subtitle' => __('', 'redux-framework-demo'),
                'desc'     => __('', 'redux-framework-demo'),
                'validate' => 'numeric',
                'msg'      => 'Only accept number'
            )
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'FAQ Page', 'redux-framework-demo' ),
        'desc'       => '',
        'id'         => 'faq-subsection',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'faq',
                'type'     => 'select',
                'multi'    => true,
                'title'    => __('FAQ', 'redux-framework-demo'), 
                'desc'     => __('You can change or order you faq.', 'redux-framework-demo'),
                'data'  => 'terms',
                'args'  => array(
                    'taxonomies' => array( 'faq_category' ),
                    'hide_empty' => false,
                )
            )
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Checkout Page', 'redux-framework-demo' ),
        'desc'       => '',
        'id'         => 'checkout-subsection',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'term-condition-link',
                'type'     => 'select',
                'multi'    => false,
                'title'    => __( 'Select Page for Terms & Conditions', 'redux-framework-demo' ),
                'desc'     => __( 'Link to show Terms & Conditions', 'redux-framework-demo' ),
                'data'  => 'posts',
                'args'  => array(
                    'post_type'      => 'page',
                    'posts_per_page' => -1,
                    'orderby'        => 'title',
                    'order'          => 'ASC',
                )
            )
        )
    ) );

    /*
     * <--- END SECTIONS
     */
