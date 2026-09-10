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

// Add direct link to edit Events Page under Events CPT menu
add_action('admin_menu', function() {
    $events_page = get_page_by_path('events');
    $events_page_id = $events_page ? $events_page->ID : 8;
    add_submenu_page(
        'edit.php?post_type=event',
        'Events Page Settings',
        'Events Page Settings',
        'edit_pages',
        'post.php?post=' . $events_page_id . '&action=edit'
    );
});

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

            // TAB 5: Top Menu & Order Online Button
            array('key' => 'field_global_tab_order', 'label' => '5. Top Menu Order Button', 'type' => 'tab'),
            array(
                'key' => 'field_global_order_link',
                'label' => 'Direct Order Online 3rd-Party URL (Optional)',
                'name' => 'global_order_link',
                'type' => 'url',
                'instructions' => 'Залиште порожнім (за замовчуванням), щоб кнопка ORDER ONLINE у верхньому меню відкривала спливаюче вікно зі списком вендорів (де кожен вендор веде на свій 3rd-party сервіс замовлення). Якщо вказати URL тут — кнопка у верхньому меню одразу переходитиме на це пряме зовнішнє посилання.',
            ),
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
            array('key' => 'field_vendor_cuisine', 'label' => 'Hero Subtitle (e.g. BAR & BEVERAGES or BOBA. TEA. DESSERTS.)', 'name' => 'vendor_cuisine', 'type' => 'text', 'default_value' => 'BOBA. TEA. DESSERTS.', 'instructions' => 'Text displayed right above the Tagline in the hero section and on directory cards.'),
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
            array('key' => 'field_vendor_menu_label', 'label' => 'Menu Subtitle (Red Subheading e.g. POPULAR DRINKS)', 'name' => 'vendor_menu_label', 'type' => 'text', 'default_value' => 'POPULAR DRINKS'),
            array('key' => 'field_vendor_menu_title', 'label' => 'Menu Main Heading', 'name' => 'vendor_menu_title', 'type' => 'text', 'placeholder' => 'e.g. KUNG FU TEA MENU HIGHLIGHTS', 'instructions' => 'Main heading for menu section. If left empty, defaults to Vendor Name + MENU HIGHLIGHTS.'),
            array(
                'key' => 'field_vendor_menu_items',
                'label' => 'Menu Items List (Item Name, Price, Description)',
                'name' => 'vendor_menu_items',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => '+ Add Menu Item',
                'sub_fields' => array(
                    array(
                        'key' => 'field_vmenu_item_name',
                        'label' => 'Item Name',
                        'name' => 'item_name',
                        'type' => 'text',
                        'placeholder' => 'e.g. CLASSIC MILK TEA',
                    ),
                    array(
                        'key' => 'field_vmenu_item_price',
                        'label' => 'Price',
                        'name' => 'item_price',
                        'type' => 'text',
                        'placeholder' => 'e.g. $5.50',
                    ),
                    array(
                        'key' => 'field_vmenu_item_desc',
                        'label' => 'Item Description',
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
            array(
                'key' => 'field_vendor_description',
                'label' => 'Card Description Text',
                'name' => 'vendor_description',
                'type' => 'textarea',
                'rows' => 3,
                'instructions' => 'Description text shown on the vendor card in the directory (/vendor/).'
            ),
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
                'label' => 'Order Button Text',
                'name' => 'vendor_order_modal_text',
                'type' => 'text',
                'placeholder' => 'e.g. 🧋 KUNG FU TEA',
                'instructions' => 'Enter text and emoji displayed on the Order Online button (e.g. 🧋 KUNG FU TEA, 🍣 POKE BURRI, 🍜 LIFTING NOODLES).'
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

    // EVENTS PAGE SETTINGS (HERO & SECTIONS)
    acf_add_local_field_group(array(
        'key' => 'group_events_page',
        'title' => 'Events Page Settings',
        'fields' => array(
            // TAB 1: Hero Section
            array('key' => 'field_events_tab_hero', 'label' => '1. Hero Section', 'type' => 'tab'),
            array(
                'key' => 'field_events_hero_video',
                'label' => 'Hero Background Video (Фонове відео)',
                'name' => 'events_hero_video',
                'type' => 'file',
                'instructions' => 'Завантажте відео-файл (MP4 або WebM) для фону першого екрана. Якщо поле порожнє, показуватиметься фонове фото.',
                'return_format' => 'url',
                'mime_types' => 'mp4,webm,mov,m4v',
            ),
            array(
                'key' => 'field_events_hero_video_url',
                'label' => 'Hero Video URL (Посилання на відео, якщо зовнішнє)',
                'name' => 'events_hero_video_url',
                'type' => 'url',
                'instructions' => 'Або вставте пряме посилання на MP4/WebM відео.',
            ),
            array(
                'key' => 'field_events_hero_bg',
                'label' => 'Hero Background Image (Фонове фото / Постер)',
                'name' => 'events_hero_bg',
                'type' => 'image',
                'instructions' => 'Фонове фото першого екрана (або постер перед запуском відео).',
                'return_format' => 'url'
            ),
            array(
                'key' => 'field_events_hero_title',
                'label' => 'Hero Title (Головний заголовок)',
                'name' => 'events_hero_title',
                'type' => 'text',
                'default_value' => "EVENTS @ PH'EAST",
                'instructions' => 'Головний великий білий заголовок у першому екрані.'
            ),
            array(
                'key' => 'field_events_hero_subtitle',
                'label' => 'Hero Red Accent Subtitle (Червоний підзаголовок)',
                'name' => 'events_hero_subtitle',
                'type' => 'text',
                'default_value' => 'ALWAYS SOMETHING HAPPENING.',
                'instructions' => 'Червоний підзаголовок під головним заголовком.'
            ),
            array(
                'key' => 'field_events_hero_desc',
                'label' => 'Hero Description (Опис)',
                'name' => 'events_hero_desc',
                'type' => 'textarea',
                'rows' => 3,
                'default_value' => "From live music to game nights & special collabs.<br>There's always a reason to gather at PH'EAST.",
                'instructions' => 'Текст опису під червоним підзаголовком. Підтримує переносу рядків <br>.'
            ),

            // TAB 2: Upcoming Events Section
            array('key' => 'field_events_tab_upcoming', 'label' => '2. Upcoming Events Section', 'type' => 'tab'),
            array(
                'key' => 'field_events_sec_title',
                'label' => 'Upcoming Events Heading',
                'name' => 'events_sec_title',
                'type' => 'text',
                'default_value' => 'UPCOMING EVENTS',
                'instructions' => 'Червоний заголовок над каруселлю подій.'
            ),

            // TAB 3: Private Events & Bookings
            array('key' => 'field_events_tab_private', 'label' => '3. Private Events & Bookings', 'type' => 'tab'),
            array(
                'key' => 'field_events_private_title',
                'label' => 'Private Events Title',
                'name' => 'events_private_title',
                'type' => 'text',
                'default_value' => 'PRIVATE EVENTS & GROUP BOOKINGS',
            ),
            array(
                'key' => 'field_events_private_desc',
                'label' => 'Private Events Description',
                'name' => 'events_private_desc',
                'type' => 'textarea',
                'rows' => 3,
                'default_value' => "Planning a party, team outing, or special celebration? We've got the space and the flavor.",
            ),
            array(
                'key' => 'field_events_book_link',
                'label' => 'Book Event Button Link',
                'name' => 'events_book_link',
                'type' => 'text',
                'default_value' => '/contact',
                'instructions' => 'Посилання для кнопки бронювання заходу (за замовчуванням /contact).',
            ),
        ),
        'location' => array(
            array( array('param' => 'page_template', 'operator' => '==', 'value' => 'page-events.php') ),
            array( array('param' => 'page', 'operator' => '==', 'value' => '8') ),
        ),
        'hide_on_screen' => array('the_content'),
    ));

    // ABOUT PAGE SETTINGS
    acf_add_local_field_group(array(
        'key' => 'group_about_page',
        'title' => 'About Page Settings',
        'fields' => array(
            // TAB 1: Hero Section
            array('key' => 'field_about_tab_hero', 'label' => '1. Hero Section (Вступ / Перший екран)', 'type' => 'tab'),
            array(
                'key' => 'field_about_hero_image',
                'label' => 'Hero Featured Image (Головне фото першого екрана)',
                'name' => 'about_hero_image',
                'type' => 'image',
                'instructions' => 'Головне фото першого екрана сторінки About (за замовчуванням фото інтер\'єру).',
                'return_format' => 'url',
            ),
            array(
                'key' => 'field_about_hero_subtitle',
                'label' => 'Hero Subtitle (Червоний підзаголовок)',
                'name' => 'about_hero_subtitle',
                'type' => 'text',
                'default_value' => "ABOUT PH'EAST",
            ),
            array(
                'key' => 'field_about_hero_title',
                'label' => 'Hero Heading (Головний заголовок)',
                'name' => 'about_hero_title',
                'type' => 'textarea',
                'rows' => 2,
                'default_value' => "ASIAN STREET FOOD.<br>LOCAL SOUL.",
                'instructions' => 'Великий заголовок (підтримує тег <br>).',
            ),
            array(
                'key' => 'field_about_hero_desc',
                'label' => 'Hero Description (Текст опису)',
                'name' => 'about_hero_desc',
                'type' => 'textarea',
                'rows' => 4,
                'default_value' => "PH'EAST is Atlanta's destination for bold Asian street food, unforgettable vibes, and a community that comes together over great food and good times.",
            ),
            array(
                'key' => 'field_about_hero_banner_bg',
                'label' => 'Hero Full-Width Banner Image (Опціональний великий банер зверху)',
                'name' => 'about_hero_banner_bg',
                'type' => 'image',
                'instructions' => 'Опціонально: якщо сюди завантажити фото, у самому верху сторінки з\'явиться великий банер на всю ширину як на інших сторінках. Якщо залишити порожнім — першим екраном залишається стандартний акуратний блок.',
                'return_format' => 'url',
            ),
            array(
                'key' => 'field_about_hero_banner_title',
                'label' => 'Full-Width Banner Title (Заголовок великого банера)',
                'name' => 'about_hero_banner_title',
                'type' => 'text',
                'default_value' => "ABOUT PH'EAST",
            ),
            array(
                'key' => 'field_about_hero_banner_subtitle',
                'label' => 'Full-Width Banner Subtitle (Червоний підзаголовок банера)',
                'name' => 'about_hero_banner_subtitle',
                'type' => 'text',
                'default_value' => "ASIAN STREET FOOD. LOCAL SOUL.",
            ),

            // TAB 2: Story Section
            array('key' => 'field_about_tab_story', 'label' => '2. Our Story Section', 'type' => 'tab'),
            array(
                'key' => 'field_about_image',
                'label' => 'Story Image (Фото блоку історії)',
                'name' => 'about_image',
                'type' => 'image',
                'instructions' => 'Фото для блоку Our Story (за замовчуванням екстер\'єр).',
                'return_format' => 'url',
            ),
            array(
                'key' => 'field_about_subtitle',
                'label' => 'Story Subtitle (Червоний підзаголовок)',
                'name' => 'about_subtitle',
                'type' => 'text',
                'default_value' => 'OUR STORY',
            ),
            array(
                'key' => 'field_about_heading',
                'label' => 'Story Heading (Головний заголовок)',
                'name' => 'about_heading',
                'type' => 'textarea',
                'rows' => 2,
                'default_value' => "FROM THE STREETS<br>TO THE BATTERY.",
            ),
            array(
                'key' => 'field_about_text1',
                'label' => 'Story Paragraph 1',
                'name' => 'about_text1',
                'type' => 'textarea',
                'rows' => 3,
                'default_value' => "PH'EAST was born from a love of Asian street food and the energy of night markets. We set out to create a space in Atlanta where people could experience that same energy—bold flavors, late nights, and a whole lot of heart.",
            ),
            array(
                'key' => 'field_about_text2',
                'label' => 'Story Paragraph 2',
                'name' => 'about_text2',
                'type' => 'textarea',
                'rows' => 3,
                'default_value' => "Today, we're proud to support local vendors, showcase amazing talent, and welcome thousands of guests who make PH'EAST what it is.",
            ),
        ),
        'location' => array(
            array( array('param' => 'page_template', 'operator' => '==', 'value' => 'page-about.php') ),
            array( array('param' => 'page', 'operator' => '==', 'value' => '6') ),
        ),
        'hide_on_screen' => array('the_content'),
    ));

    // HOME PAGE SETTINGS (WITH TABS)
    acf_add_local_field_group(array(
        'key' => 'group_home_page',
        'title' => 'Home Page Settings',
        'fields' => array(
            // Tab 1: Hero
            array('key' => 'field_home_tab_hero', 'label' => 'Hero Section', 'type' => 'tab'),
            array(
                'key' => 'field_home_hero_video',
                'label' => 'Hero Background Video (Фонове відео)',
                'name' => 'home_hero_video',
                'type' => 'file',
                'instructions' => 'Завантажте відео-файл (MP4 або WebM) для фону першого екрана. Якщо поле порожнє, показуватиметься фонове фото.',
                'return_format' => 'url',
                'mime_types' => 'mp4,webm,mov,m4v',
            ),
            array(
                'key' => 'field_home_hero_video_url',
                'label' => 'Hero Video URL (Посилання на відео, якщо зовнішнє)',
                'name' => 'home_hero_video_url',
                'type' => 'url',
                'instructions' => 'Або вставте пряме посилання на MP4/WebM відео.',
            ),
            array('key' => 'field_home_hero_bg', 'label' => 'Hero Background Image (Фонове фото / Постер)', 'name' => 'home_hero_bg', 'type' => 'image', 'instructions' => 'Фонове фото (або постер перед запуском відео)', 'return_format' => 'url'),
            array('key' => 'field_home_hero_heading', 'label' => 'Hero Heading', 'name' => 'home_hero_heading', 'type' => 'text', 'default_value' => 'ONE FOOD HALL.<br>ENDLESS FLAVOR.'),
            array('key' => 'field_home_hero_subtitle', 'label' => 'Hero Subtitle', 'name' => 'home_hero_subtitle', 'type' => 'text', 'default_value' => 'Asian street food. Local hawkers.'),
            array('key' => 'field_home_hero_title', 'label' => 'Hero Main Title (Alt)', 'name' => 'home_hero_title', 'type' => 'text', 'default_value' => 'ASIAN STREET FOOD'),
            
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
