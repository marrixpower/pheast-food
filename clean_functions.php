<?php
error_reporting(0);
@ini_set('display_errors', 0);

// Disable WordPress emoji script & SVG image replacement
function pheast_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_title', 'wp_staticize_emoji');
    remove_filter('wp_title', 'wp_staticize_emoji');
}
add_action('init', 'pheast_disable_emojis');

function pheast_enqueue_scripts() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700;800&family=Bebas+Neue&family=Outfit:wght@800;900&family=Fredoka:wght@600;700&family=Inter:wght@400;600;700;800;900&display=swap', array(), null);
    wp_enqueue_style('pheast-style', get_stylesheet_uri(), array(), filemtime(get_stylesheet_directory() . '/style.css'));
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
    wp_enqueue_script('pheast-app', get_template_directory_uri() . '/app.js', array(), filemtime(get_stylesheet_directory() . '/app.js'), true);
    wp_localize_script('pheast-app', 'pheast_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'pheast_enqueue_scripts');
add_theme_support('title-tag');
add_theme_support('post-thumbnails');

// Register Menu
function pheast_register_menus() {
    add_theme_support('menus');
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'pheast')
    ));
}
add_action('after_setup_theme', 'pheast_register_menus');

// Add classes to menu links
function pheast_menu_link_atts($atts, $item, $args) {
    if ($args->theme_location == 'primary') {
        $atts['class'] = 'nav-link';
        if (in_array('current-menu-item', $item->classes) || in_array('current_page_item', $item->classes)) {
            $atts['class'] .= ' active';
        }
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'pheast_menu_link_atts', 10, 3);

// ACF Fields for About Page
add_action('acf/init', 'pheast_about_acf_fields');
function pheast_about_acf_fields() {
    if (function_exists('acf_add_local_field_group')):
        acf_add_local_field_group(array(
            'key' => 'group_about_page',
            'title' => 'About Page Settings',
            'fields' => array(
                array(
                    'key' => 'field_about_subtitle',
                    'label' => 'Subtitle (e.g. OUR STORY)',
                    'name' => 'about_subtitle',
                    'type' => 'text',
                    'default_value' => 'OUR STORY',
                ),
                array(
                    'key' => 'field_about_heading',
                    'label' => 'Main Heading',
                    'name' => 'about_heading',
                    'type' => 'text',
                    'default_value' => 'FROM THE STREETS<br>TO THE BATTERY.',
                ),
                array(
                    'key' => 'field_about_text1',
                    'label' => 'Paragraph 1',
                    'name' => 'about_text1',
                    'type' => 'textarea',
                    'default_value' => 'PH\'EAST was born from a love of Asian street food and the energy of night markets. We set out to create a space in Atlanta where people could experience that same energy—bold flavors, late nights, and a whole lot of heart.',
                ),
                array(
                    'key' => 'field_about_text2',
                    'label' => 'Paragraph 2',
                    'name' => 'about_text2',
                    'type' => 'textarea',
                    'default_value' => 'Today, we\'re proud to support local vendors, showcase amazing talent, and welcome thousands of guests who make PH\'EAST what it is.',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page_template',
                        'operator' => '==',
                        'value' => 'page-about.php',
                    ),
                ),
                array(
                    array(
                        'param' => 'page',
                        'operator' => '==',
                        'value' => '6',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'acf_after_title',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'the_content',
            ),
        ));
    endif;
}

// Add ACF Options Page for Footer & Global Settings
add_action('acf/init', 'pheast_acf_op_init');
function pheast_acf_op_init() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title'    => 'Footer & Site Settings',
            'menu_title'    => 'Footer Settings',
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false,
            'icon_url'      => 'dashicons-admin-generic',
            'position'      => 59
        ));
    }
}

// AJAX Handler for Order Inquiry Submissions
function pheast_handle_order_submission() {
    $name = isset($_POST['name']) ? sanitize_text_field(trim($_POST['name'])) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field(trim($_POST['phone'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(trim($_POST['email'])) : '';
    $vendor = isset($_POST['vendor']) ? sanitize_text_field(trim($_POST['vendor'])) : "PH'EAST Food Hall";
    $notes = isset($_POST['notes']) ? sanitize_textarea_field(trim($_POST['notes'])) : '';

    // Validate Name
    if (empty($name) || mb_strlen($name) < 2 || preg_match('/^[\d\W]+$/u', $name)) {
        wp_send_json_error(array('message' => 'Please provide a valid name (at least 2 letters).'));
    }

    // Validate Phone Number
    $digits = preg_replace('/[^0-9]/', '', $phone);
    $is_repeated = (bool) preg_match('/^(\d)\1+$/', $digits);

    if (empty($phone) || strlen($digits) < 9 || strlen($digits) > 15 || $is_repeated) {
        wp_send_json_error(array('message' => 'Please provide a valid phone number (e.g. (678) 123-4567 or international format).'));
    }

    // 1. Save to WordPress Database (Order Inquiries CPT)
    $post_title = $name . ' — ' . $vendor . ' (' . current_time('M j, Y H:i') . ')';
    $post_id = wp_insert_post(array(
        'post_title'   => $post_title,
        'post_type'    => 'order_inquiry',
        'post_status'  => 'publish',
        'post_content' => $notes,
    ));

    if ($post_id && !is_wp_error($post_id)) {
        update_post_meta($post_id, '_inquiry_customer_name', $name);
        update_post_meta($post_id, '_inquiry_phone', $phone);
        update_post_meta($post_id, '_inquiry_email', $email);
        update_post_meta($post_id, '_inquiry_vendor', $vendor);
        update_post_meta($post_id, '_inquiry_notes', $notes);
    }

    // 2. Send Email Notification to Admin
    $admin_email = get_option('admin_email');
    $subject = "[PH'EAST] New Order Inquiry for " . $vendor . " from " . $name;
    $body = "New Order Inquiry received on PH'EAST website:\n\n"
          . "Customer: " . $name . "\n"
          . "Phone: " . $phone . "\n"
          . "Email: " . ($email ?: 'N/A') . "\n"
          . "Vendor: " . $vendor . "\n"
          . "Order Notes / Details:\n" . ($notes ?: 'None') . "\n\n"
          . "View in WP Admin: " . admin_url('edit.php?post_type=order_inquiry');

    $headers = array('Content-Type: text/plain; charset=UTF-8');
    if (!empty($email)) {
        $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
    }

    @wp_mail($admin_email, $subject, $body, $headers);

    wp_send_json_success(array(
        'message' => 'Your inquiry has been submitted successfully!',
        'name'    => $name,
        'vendor'  => $vendor,
        'phone'   => $phone
    ));
}
add_action('wp_ajax_submit_pheast_order', 'pheast_handle_order_submission');
add_action('wp_ajax_nopriv_submit_pheast_order', 'pheast_handle_order_submission');

require_once get_template_directory() . '/inc/custom-setup.php';
?>
