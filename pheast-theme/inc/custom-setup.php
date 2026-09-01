<?php

// Disable Gutenberg Block Editor to restore clean Classic Editor
add_filter('use_block_editor_for_post_type', '__return_false', 100);

// Register Gallery CPT and Taxonomy
function pheast_register_gallery() {
    register_taxonomy('gallery_category', 'gallery_item', array(
        'labels' => array(
            'name'          => 'Gallery Categories',
            'singular_name' => 'Gallery Category',
            'add_new_item'  => 'Add New Category',
            'edit_item'     => 'Edit Category',
            'search_items'  => 'Search Categories',
            'all_items'     => 'All Categories'
        ),
        'public'       => true,
        'hierarchical' => true,
        'show_in_rest' => false
    ));

    register_post_type('gallery_item', array(
        'labels' => array(
            'name'          => 'Gallery Images',
            'singular_name' => 'Gallery Image',
            'add_new'       => 'Add New Image',
            'add_new_item'  => 'Add New Gallery Image',
            'edit_item'     => 'Edit Gallery Image',
            'all_items'     => 'All Gallery Images'
        ),
        'public'      => true,
        'supports'    => array('title', 'thumbnail'),
        'taxonomies'  => array('gallery_category'),
        'menu_icon'   => 'dashicons-format-gallery',
        'show_in_rest'=> false
    ));
}
add_action('init', 'pheast_register_gallery');

// Register Custom Post Types (Vendors & Events)
function pheast_register_cpts() {
    register_post_type('vendor', array(
        'labels'      => array(
            'name'          => 'Vendors',
            'singular_name' => 'Vendor',
            'add_new'       => 'Add New Vendor',
            'add_new_item'  => 'Add New Vendor',
            'edit_item'     => 'Edit Vendor',
            'all_items'     => 'All Vendors'
        ),
        'public'      => true,
        'has_archive' => true,
        'menu_icon'   => 'dashicons-store',
        'supports'    => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'rewrite'     => array('slug' => 'vendors'),
        'show_in_rest'=> false
    ));

    register_post_type('event', array(
        'labels'      => array(
            'name'          => 'Events',
            'singular_name' => 'Event',
            'add_new'       => 'Add New Event',
            'add_new_item'  => 'Add New Event',
            'edit_item'     => 'Edit Event',
            'all_items'     => 'All Events'
        ),
        'public'      => true,
        'has_archive' => true,
        'menu_icon'   => 'dashicons-calendar-alt',
        'supports'    => array('title', 'editor', 'thumbnail'),
        'rewrite'     => array('slug' => 'events'),
        'show_in_rest'=> false
    ));

    // Register Order Inquiries Post Type
    register_post_type('order_inquiry', array(
        'labels'      => array(
            'name'          => 'Order Inquiries',
            'singular_name' => 'Order Inquiry',
            'all_items'     => 'All Inquiries',
            'edit_item'     => 'View Inquiry',
        ),
        'public'      => false,
        'show_ui'     => true,
        'show_in_menu'=> true,
        'menu_icon'   => 'dashicons-email-alt2',
        'menu_position' => 26,
        'supports'    => array('title'),
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => 'do_not_allow',
        ),
        'map_meta_cap' => true,
    ));
}
add_action('init', 'pheast_register_cpts');

// Custom Columns for Order Inquiries in WP Admin
add_filter('manage_order_inquiry_posts_columns', function($columns) {
    return array(
        'cb'       => '<input type="checkbox" />',
        'title'    => 'Customer Name',
        'vendor'   => 'Target Vendor',
        'phone'    => 'Phone Number',
        'email'    => 'Email',
        'notes'    => 'Order / Inquiry Details',
        'date'     => 'Date Received'
    );
});

add_action('manage_order_inquiry_posts_custom_column', function($column, $post_id) {
    switch ($column) {
        case 'vendor':
            $v = get_post_meta($post_id, '_inquiry_vendor', true);
            echo '<span style="color:#D41F3C; font-weight:bold;">' . esc_html($v ?: "PH'EAST General") . '</span>';
            break;
        case 'phone':
            $p = get_post_meta($post_id, '_inquiry_phone', true);
            echo $p ? '<a href="tel:' . esc_attr($p) . '">' . esc_html($p) . '</a>' : '—';
            break;
        case 'email':
            $e = get_post_meta($post_id, '_inquiry_email', true);
            echo $e ? '<a href="mailto:' . esc_attr($e) . '">' . esc_html($e) . '</a>' : '—';
            break;
        case 'notes':
            $n = get_post_meta($post_id, '_inquiry_notes', true);
            echo esc_html(wp_trim_words($n, 12, '...'));
            break;
    }
}, 10, 2);

// Register ACF Fields
function my_acf_add_local_field_groups() {
    if (function_exists('acf_add_local_field_group')):

    // FOOTER & GLOBAL SITE SETTINGS (WITH CLEAN TABS)
    acf_add_local_field_group(array(
        'key' => 'group_global_settings',
        'title' => 'Footer Settings',
        'fields' => array(
            // TAB 1: Social Media Links
            array('key' => 'field_footer_tab_social', 'label' => '1. Social Media Links', 'type' => 'tab'),
            array('key' => 'field_footer_social_heading', 'label' => 'Social Block Heading', 'name' => 'footer_social_heading', 'type' => 'text', 'default_value' => 'FOLLOW PH\'EAST'),
            array('key' => 'field_global_ig', 'label' => 'Instagram URL', 'name' => 'global_ig', 'type' => 'url', 'default_value' => 'https://www.instagram.com/pheastfoodhall/'),
            array('key' => 'field_global_fb', 'label' => 'Facebook URL', 'name' => 'global_fb', 'type' => 'url', 'default_value' => 'https://www.facebook.com/pheastfoodhall/'),
            array('key' => 'field_global_yelp', 'label' => 'Yelp URL', 'name' => 'global_yelp', 'type' => 'url', 'default_value' => 'https://www.yelp.com/biz/pheast-food-hall-atlanta'),
            array('key' => 'field_global_tiktok', 'label' => 'TikTok URL', 'name' => 'global_tiktok', 'type' => 'url', 'default_value' => '#'),
            
            // TAB 2: Location & Address
            array('key' => 'field_footer_tab_addr', 'label' => '2. Location & Address', 'type' => 'tab'),
            array('key' => 'field_footer_addr_heading', 'label' => 'Address Block Heading', 'name' => 'footer_addr_heading', 'type' => 'text', 'default_value' => 'THE BATTERY ATLANTA'),
            array('key' => 'field_global_address', 'label' => 'Address Text', 'name' => 'global_address', 'type' => 'textarea', 'default_value' => "925 Battery Ave SE Ste. 1100,\nAtlanta, GA 30339"),
            array('key' => 'field_global_maps_link', 'label' => 'Google Maps URL (Optional)', 'name' => 'global_maps_link', 'type' => 'url'),

            // TAB 3: Operating Hours
            array('key' => 'field_footer_tab_hours', 'label' => '3. Operating Hours', 'type' => 'tab'),
            array('key' => 'field_footer_hours_heading', 'label' => 'Hours Block Heading', 'name' => 'footer_hours_heading', 'type' => 'text', 'default_value' => 'HOURS'),
            array('key' => 'field_global_hours', 'label' => 'Hours Text', 'name' => 'global_hours', 'type' => 'textarea', 'default_value' => "Sun - Thurs: 11 AM - Midnight\nFri & Sat: 11 AM - 1 AM"),
            
            // TAB 4: Logo & Newsletter
            array('key' => 'field_footer_tab_brand', 'label' => '4. Logo & Newsletter', 'type' => 'tab'),
            array('key' => 'field_footer_logo', 'label' => 'Footer White Logo Image', 'name' => 'footer_logo', 'type' => 'image', 'return_format' => 'url', 'instructions' => 'Custom white logo shown in footer. Leave empty to use default.'),
            array('key' => 'field_footer_news_heading', 'label' => 'Newsletter Heading', 'name' => 'footer_news_heading', 'type' => 'text', 'default_value' => 'STAY UPDATED'),
            array('key' => 'field_footer_news_subtext', 'label' => 'Newsletter Subtext', 'name' => 'footer_news_subtext', 'type' => 'text', 'default_value' => 'Join our mailing list for the latest news!'),
            array('key' => 'field_footer_news_placeholder', 'label' => 'Email Input Placeholder', 'name' => 'footer_news_placeholder', 'type' => 'text', 'default_value' => 'Enter your email'),
        ),
        'location' => array(
            array( array('param' => 'options_page', 'operator' => '==', 'value' => 'theme-general-settings') ),
        ),
    ));

    // VENDOR PAGE SETTINGS
    acf_add_local_field_group(array(
        'key' => 'group_vendor_page',
        'title' => 'Vendor Page Settings',
        'fields' => array(
            array('key' => 'field_vendor_page_hero_bg', 'label' => 'Hero Background Image', 'name' => 'vendor_page_hero_bg', 'type' => 'image', 'return_format' => 'url'),
            array('key' => 'field_vendor_page_toptext', 'label' => 'Hero Top Text', 'name' => 'vendor_page_toptext', 'type' => 'text', 'default_value' => 'EXPLORE THE FOOD HALL'),
            array('key' => 'field_vendor_page_title', 'label' => 'Hero Title', 'name' => 'vendor_page_hero_title', 'type' => 'text', 'default_value' => 'SIX FLAVORS. ONE ROOF.'),
            array('key' => 'field_vendor_page_subtitle', 'label' => 'Hero Subtitle', 'name' => 'vendor_page_hero_subtitle', 'type' => 'textarea', 'default_value' => 'From craft beer and boba tea to sushi burritos and authentic ramen, discover our curated lineup of Asian street food vendors at The Battery Atlanta.'),
            array('key' => 'field_vendor_page_dir_title', 'label' => 'Directory Title', 'name' => 'vendor_page_dir_title', 'type' => 'text', 'default_value' => 'MEET OUR VENDORS'),
            array('key' => 'field_vendor_page_dir_sub', 'label' => 'Directory Subtitle', 'name' => 'vendor_page_dir_sub', 'type' => 'text', 'default_value' => 'Click any vendor card to learn more & view featured menus.')
        ),
        'location' => array(
            array( array('param' => 'page_template', 'operator' => '==', 'value' => 'page-vendor.php') )
        ),
    ));

    // VENDOR CPT FIELDS (FULL TEMPLATE CUSTOMIZATION WITH CLEAN SECTIONS)
    acf_add_local_field_group(array(
        'key' => 'group_vendor_fields',
        'title' => 'Vendor Details & Page Template',
        'fields' => array(
            // TAB 1: Hero Section & Background
            array('key' => 'field_vendor_tab_hero', 'label' => '1. Hero Section', 'type' => 'tab'),
            array('key' => 'field_vendor_hero_bg', 'label' => 'Hero Background Image', 'name' => 'vendor_hero_bg', 'type' => 'image', 'return_format' => 'url', 'instructions' => 'Full-screen hero background image.'),
            array('key' => 'field_vendor_hero_title_accent', 'label' => 'Hero Title Red Accent Part (e.g. KUNG<br>FU)', 'name' => 'vendor_hero_title_accent', 'type' => 'text', 'instructions' => 'Upper red accent words. Leave empty to auto-split post title.'),
            array('key' => 'field_vendor_hero_title_white', 'label' => 'Hero Title White Part (e.g. TEA)', 'name' => 'vendor_hero_title_white', 'type' => 'text', 'instructions' => 'Lower white title words beside the stamp icon.'),
            array('key' => 'field_vendor_stamp_logo', 'label' => 'Stamp / Symbol Icon (Beside Title)', 'name' => 'vendor_stamp_logo', 'type' => 'image', 'return_format' => 'url', 'instructions' => 'Symbol stamp placed next to the white title word (e.g. Kung Fu Tea stamp).'),
            array('key' => 'field_vendor_tagline', 'label' => 'Tagline (e.g. Taiwanese tea culture in Atlanta.)', 'name' => 'vendor_tagline', 'type' => 'text', 'default_value' => 'Taiwanese tea culture in Atlanta.'),
            array('key' => 'field_vendor_hero_btn1_text', 'label' => 'Button 1 Text', 'name' => 'vendor_hero_btn1_text', 'type' => 'text', 'default_value' => 'ORDER ONLINE'),
            array('key' => 'field_vendor_hero_btn1_link', 'label' => 'Button 1 Link (Optional)', 'name' => 'vendor_hero_btn1_link', 'type' => 'text', 'instructions' => 'Leave empty to open the Order Online popup modal.'),
            array('key' => 'field_vendor_hero_btn2_text', 'label' => 'Button 2 Text', 'name' => 'vendor_hero_btn2_text', 'type' => 'text', 'default_value' => 'VIEW MENU'),
            array('key' => 'field_vendor_hero_btn2_link', 'label' => 'Button 2 Anchor Link', 'name' => 'vendor_hero_btn2_link', 'type' => 'text', 'default_value' => '#vendor-menu'),

            // TAB 2: Brand Story (About Section)
            array('key' => 'field_vendor_tab_about', 'label' => '2. Brand Story (About)', 'type' => 'tab'),
            array('key' => 'field_vendor_about_photo', 'label' => 'Storefront / Food Photo (in Red Neon Frame)', 'name' => 'vendor_about_photo', 'type' => 'image', 'return_format' => 'url', 'instructions' => 'Square photo shown inside glowing red neon frame.'),
            array('key' => 'field_vendor_about_heading', 'label' => 'Story Section Heading', 'name' => 'vendor_about_heading', 'type' => 'text', 'default_value' => "BREWED WITH TRADITION.<br>SERVED WITH STYLE."),
            array('key' => 'field_vendor_about_desc', 'label' => 'Story Description Paragraphs', 'name' => 'vendor_about_desc', 'type' => 'textarea', 'rows' => 6, 'instructions' => 'Brand description paragraphs. If empty, the main Classic Editor content will be used.'),

            // TAB 3: Menu Highlights Section
            array('key' => 'field_vendor_tab_menu', 'label' => '3. Menu Highlights', 'type' => 'tab'),
            array('key' => 'field_vendor_menu_label', 'label' => 'Menu Subtitle (Червоний надзаголовок e.g. POPULAR DRINKS)', 'name' => 'vendor_menu_label', 'type' => 'text', 'default_value' => 'POPULAR DRINKS'),
            array('key' => 'field_vendor_menu_title', 'label' => 'Menu Main Heading (Головна назва меню)', 'name' => 'vendor_menu_title', 'type' => 'text', 'placeholder' => 'e.g. KUNG FU TEA MENU HIGHLIGHTS', 'instructions' => 'Головний заголовок блоку меню. Якщо залишити порожнім, автоматично підтягується назва закладу + MENU HIGHLIGHTS.'),
            array(
                'key' => 'field_vendor_menu_items',
                'label' => 'Menu Items List (Позиції меню: Назва, Ціна, Опис)',
                'name' => 'vendor_menu_items',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => '+ Add Menu Item (Додати страву)',
                'sub_fields' => array(
                    array(
                        'key' => 'field_vmenu_item_name',
                        'label' => 'Item Name (Назва)',
                        'name' => 'item_name',
                        'type' => 'text',
                        'placeholder' => 'e.g. CLASSIC MILK TEA',
                    ),
                    array(
                        'key' => 'field_vmenu_item_price',
                        'label' => 'Price (Ціна)',
                        'name' => 'item_price',
                        'type' => 'text',
                        'placeholder' => 'e.g. $5.50',
                    ),
                    array(
                        'key' => 'field_vmenu_item_desc',
                        'label' => 'Item Description (Опис)',
                        'name' => 'item_description',
                        'type' => 'textarea',
                        'rows' => 3,
                        'placeholder' => 'Ingredients, notes or flavor description...',
                    ),
                )
            ),

            // TAB 4: Other Vendors Section
            array('key' => 'field_vendor_tab_other', 'label' => '4. Other Vendors Section', 'type' => 'tab'),
            array('key' => 'field_vendor_other_heading_accent', 'label' => 'Carousel Heading Red Accent', 'name' => 'vendor_other_heading_accent', 'type' => 'text', 'default_value' => 'FIVE FLAVORS.'),
            array('key' => 'field_vendor_other_heading_main', 'label' => 'Carousel Heading Main', 'name' => 'vendor_other_heading_main', 'type' => 'text', 'default_value' => 'ONE ROOF.'),

            // TAB 5: Directory Card Settings
            array('key' => 'field_vendor_tab_card', 'label' => '5. Card Settings', 'type' => 'tab'),
            array('key' => 'field_vendor_logo', 'label' => 'Vendor Logo Image', 'name' => 'vendor_logo', 'type' => 'image', 'return_format' => 'url', 'instructions' => 'Logo shown on directory card and other vendors carousel.'),
            array('key' => 'field_vendor_card_show', 'label' => 'Show Vendor Card on Website?', 'name' => 'vendor_card_show', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1, 'instructions' => 'Toggle OFF to hide this vendor card from directory and home page.'),
            array('key' => 'field_vendor_card_title', 'label' => 'Card Display Title (Optional)', 'name' => 'vendor_card_title', 'type' => 'text', 'instructions' => 'Title shown on vendor card. Leave empty to use main title.'),
            array('key' => 'field_vendor_cuisine', 'label' => 'Cuisine Type / Red Subtitle (e.g. BOBA. TEA. DESSERTS.)', 'name' => 'vendor_cuisine', 'type' => 'text', 'default_value' => 'BOBA. TEA. DESSERTS.', 'instructions' => 'Cuisine / category shown on directory cards and vendor page.'),
            array('key' => 'field_vendor_btn_text', 'label' => 'Card Button Text', 'name' => 'vendor_btn_text', 'type' => 'text', 'default_value' => 'ORDER ONLINE'),
            array('key' => 'field_vendor_hours', 'label' => 'Operating Hours (Optional)', 'name' => 'vendor_hours', 'type' => 'text'),
            array('key' => 'field_vendor_website', 'label' => 'Official Website URL (Optional)', 'name' => 'vendor_website', 'type' => 'url'),

            // TAB 6: Order Online Button & Modal Settings
            array('key' => 'field_vendor_tab_order_btn', 'label' => '6. Order Online Button', 'type' => 'tab'),
            array(
                'key' => 'field_vendor_order_modal_show',
                'label' => 'Show in "Order Online" Modal?',
                'name' => 'vendor_order_modal_show',
                'type' => 'true_false',
                'default_value' => 1,
                'ui' => 1,
                'instructions' => 'Toggle OFF to hide this vendor from Order Online popup list.'
            ),
            array(
                'key' => 'field_vendor_order_modal_text',
                'label' => 'Order Button Text (Текст на кнопці в меню Order Online)',
                'name' => 'vendor_order_modal_text',
                'type' => 'text',
                'placeholder' => 'e.g. 🧋 KUNG FU TEA',
                'instructions' => 'Введіть текст та емодзі, які відображатимуться на кнопці в спливаючому вікні «ORDER ONLINE» (наприклад: 🧋 KUNG FU TEA, 🍣 POKE BURRI, 🍜 LIFTING NOODLES).'
            ),
            array(
                'key' => 'field_vendor_order_modal_link',
                'label' => 'External Direct Order Link (Optional)',
                'name' => 'vendor_order_modal_link',
                'type' => 'text',
                'instructions' => 'If filled, clicking this vendor in modal navigates to this external order URL directly.'
            ),
        ),
        'location' => array(
            array( array('param' => 'post_type', 'operator' => '==', 'value' => 'vendor') ),
        ),
        'position' => 'normal',
        'style' => 'default',
    ));

    // EVENT CPT FIELDS
    acf_add_local_field_group(array(
        'key' => 'group_event_fields',
        'title' => 'Event Details',
        'fields' => array(
            array('key' => 'field_event_date', 'label' => 'Date', 'name' => 'event_date', 'type' => 'date_picker', 'display_format' => 'F j, Y', 'return_format' => 'F j, Y'),
            array('key' => 'field_event_time', 'label' => 'Time', 'name' => 'event_time', 'type' => 'text'),
            array('key' => 'field_event_location', 'label' => 'Location', 'name' => 'event_location', 'type' => 'text', 'default_value' => "PH'EAST"),
            array('key' => 'field_event_link', 'label' => 'Ticket / Event Link', 'name' => 'event_link', 'type' => 'url'),
        ),
        'location' => array(
            array( array('param' => 'post_type', 'operator' => '==', 'value' => 'event') ),
        ),
    ));

    // HOME PAGE SETTINGS (WITH TABS)
    acf_add_local_field_group(array(
        'key' => 'group_home_page',
        'title' => 'Home Page Settings',
        'fields' => array(
            // Tab 1: Hero
            array('key' => 'field_home_tab_hero', 'label' => 'Hero Section', 'type' => 'tab'),
            array('key' => 'field_home_hero_bg', 'label' => 'Hero Background Image', 'name' => 'home_hero_bg', 'type' => 'image', 'return_format' => 'url'),
            array('key' => 'field_home_hero_subtitle', 'label' => 'Hero Subtitle', 'name' => 'home_hero_subtitle', 'type' => 'text', 'default_value' => 'A FAR EAST STREET FEAST.'),
            array('key' => 'field_home_hero_title', 'label' => 'Hero Main Title', 'name' => 'home_hero_title', 'type' => 'text', 'default_value' => 'ASIAN STREET FOOD'),
            
            // Tab 2: Hawker Concept
            array('key' => 'field_home_tab_about', 'label' => 'Hawker Concept', 'type' => 'tab'),
            array('key' => 'field_home_about_text', 'label' => 'Hawker Market Concept Description', 'name' => 'home_about_text', 'type' => 'textarea', 'default_value' => "PH'EAST is where you come together for asian street food, live sports, events, and unforgettable vibes. From noodles and boba to cocktails and music, every visit feels like stepping into a modern Asian street market right in the heart of The Battery."),
            
            // Tab 3: Photo Carousel
            array('key' => 'field_home_tab_carousel', 'label' => 'Atmosphere Photo Carousel', 'type' => 'tab'),
            array(
                'key' => 'field_home_carousel_photos',
                'label' => 'Carousel Photo List',
                'name' => 'home_carousel_photos',
                'type' => 'gallery',
                'instructions' => 'Add, reorder, or remove photos displayed in the atmosphere photo carousel on the homepage.',
                'return_format' => 'array',
                'insert' => 'append',
                'library' => 'all',
            ),
        ),
        'location' => array(
            array( array('param' => 'page_template', 'operator' => '==', 'value' => 'front-page.php') ),
            array( array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page') ),
            array( array('param' => 'page', 'operator' => '==', 'value' => '5') ),
        ),
        'hide_on_screen' => array('the_content'),
    ));

    // CONTACT PAGE SETTINGS
    acf_add_local_field_group(array(
        'key' => 'group_contact_page',
        'title' => 'Contact Page Settings',
        'fields' => array(
            array('key' => 'field_contact_hero_bg', 'label' => 'Hero Background Image', 'name' => 'contact_hero_bg', 'type' => 'image', 'return_format' => 'url'),
            array('key' => 'field_contact_hero_title', 'label' => 'Hero Title', 'name' => 'contact_hero_title', 'type' => 'text', 'default_value' => 'CONTACT PH\'EAST'),
            array('key' => 'field_contact_hero_sub_red', 'label' => 'Hero Red Accent Subtitle', 'name' => 'contact_hero_sub_red', 'type' => 'text', 'default_value' => 'WE\'D LOVE TO HEAR FROM YOU.'),
            array('key' => 'field_contact_hero_subtitle', 'label' => 'Hero Subtitle Description', 'name' => 'contact_hero_subtitle', 'type' => 'textarea', 'default_value' => "Questions, feedback, or partnership inquiries? Send us a message and we'll get back to you soon."),
            array('key' => 'field_contact_form_title', 'label' => 'Form Title', 'name' => 'contact_form_title', 'type' => 'text', 'default_value' => 'SEND US A MESSAGE'),
            array('key' => 'field_contact_get_in_touch_title', 'label' => 'Get In Touch Title', 'name' => 'contact_get_in_touch_title', 'type' => 'text', 'default_value' => 'GET IN TOUCH'),
            array(
                'key' => 'field_contact_phones',
                'label' => 'Vendor Phone Numbers',
                'name' => 'contact_phones',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Phone Line',
                'sub_fields' => array(
                    array('key' => 'field_contact_phone_name', 'label' => 'Vendor Name / Label', 'name' => 'name', 'type' => 'text', 'default_value' => 'TAPS@PH\'EAST'),
                    array('key' => 'field_contact_phone_number', 'label' => 'Phone Number', 'name' => 'phone', 'type' => 'text', 'default_value' => '(678) 247-8137'),
                )
            ),
            array('key' => 'field_contact_faq_title', 'label' => 'FAQ Section Title', 'name' => 'contact_faq_title', 'type' => 'text', 'default_value' => 'FREQUENTLY ASKED QUESTIONS'),
            array(
                'key' => 'field_contact_faqs',
                'label' => 'Frequently Asked Questions (Accordion)',
                'name' => 'contact_faqs',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => 'Add FAQ',
                'sub_fields' => array(
                    array('key' => 'field_contact_faq_q', 'label' => 'Question', 'name' => 'question', 'type' => 'text'),
                    array('key' => 'field_contact_faq_a', 'label' => 'Answer', 'name' => 'answer', 'type' => 'textarea'),
                )
            )
        ),
        'location' => array(
            array( array('param' => 'page_template', 'operator' => '==', 'value' => 'page-contact.php') ),
            array( array('param' => 'page', 'operator' => '==', 'value' => '7') ),
        ),
        'hide_on_screen' => array('the_content'),
    ));

    // GALLERY ITEM CPT
    acf_add_local_field_group(array(
        'key' => 'group_gallery_item_cpt',
        'title' => 'Gallery Image Settings',
        'fields' => array(
            array(
                'key' => 'field_gal_item_image',
                'label' => 'Photo',
                'name' => 'gallery_photo',
                'type' => 'image',
                'return_format' => 'url',
                'preview_size' => 'medium'
            ),
            array(
                'key' => 'field_gal_item_tall',
                'label' => 'Tall Image (Span 2 rows)?',
                'name' => 'is_tall',
                'type' => 'true_false',
                'ui' => 1
            )
        ),
        'location' => array(
            array( array('param' => 'post_type', 'operator' => '==', 'value' => 'gallery_item') )
        )
    ));

    endif;
}
add_action('acf/init', 'my_acf_add_local_field_groups');
?>
