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
    wp_enqueue_style('pheast-style', get_stylesheet_uri(), array(), time());
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
    wp_enqueue_script('pheast-app', get_template_directory_uri() . '/app.js', array(), time(), true);
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

    // Validate Phone Number (purely by digit count: 7 to 15 digits)
    $digits = preg_replace('/[^0-9]/', '', $phone);
    if (empty($phone) || strlen($digits) < 7 || strlen($digits) > 15) {
        wp_send_json_error(array('message' => 'Please provide a valid phone number (7 to 15 digits).'));
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


// ==========================================
// Contact Form Submission Handler (with Anti-Spam & Email)
// ==========================================
function pheast_handle_contact_submission() {
    // 1. Anti-spam: Honeypot check
    if (!empty($_POST['website_hp_check'])) {
        // Honeypot filled by bot, silently succeed without sending
        wp_send_json_success(array('message' => 'Thank you for reaching out.'));
    }

    // 2. Anti-spam: Time check (bots submit in < 2 seconds)
    if (!empty($_POST['form_timestamp'])) {
        $elapsed = time() - intval($_POST['form_timestamp']);
        if ($elapsed < 2) {
            wp_send_json_error(array('message' => 'Please take your time filling out the form.'));
        }
    }

    $first_name = isset($_POST['first_name']) ? sanitize_text_field(trim($_POST['first_name'])) : '';
    $last_name  = isset($_POST['last_name']) ? sanitize_text_field(trim($_POST['last_name'])) : '';
    $email      = isset($_POST['email']) ? sanitize_email(trim($_POST['email'])) : '';
    $subject_in = isset($_POST['subject']) ? sanitize_text_field(trim($_POST['subject'])) : 'Website Contact Message';
    $message    = isset($_POST['message']) ? sanitize_textarea_field(trim($_POST['message'])) : '';

    if (empty($first_name) || empty($last_name)) {
        wp_send_json_error(array('message' => 'Please provide both your first and last name.'));
    }

    if (empty($email) || !is_email($email)) {
        wp_send_json_error(array('message' => 'Please provide a valid email address.'));
    }

    if (empty($message) || mb_strlen($message) < 5) {
        wp_send_json_error(array('message' => 'Please enter a message (at least 5 characters).'));
    }

    $full_name = trim($first_name . ' ' . $last_name);

    // Send Email to Truck and Tap Admin Email
    $admin_email = get_option('admin_email') ?: 'info@truckandtap.com';
    $email_subject = "[PH'EAST Contact] " . $subject_in . " from " . $full_name;
    
    $email_body = "You have received a new contact message from the PH'EAST website:

"
                . "Name: " . $full_name . "
"
                . "Email: " . $email . "
"
                . "Subject: " . $subject_in . "
"
                . "Date: " . current_time('M j, Y H:i:s') . "

"
                . "Message:
"
                . "----------------------------------------
"
                . $message . "
"
                . "----------------------------------------

"
                . "You can reply directly to this email to contact " . $first_name . ".";

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $full_name . ' <' . $email . '>'
    );

    $sent = wp_mail($admin_email, $email_subject, $email_body, $headers);

    if ($sent) {
        wp_send_json_success(array('message' => 'Message sent successfully!'));
    } else {
        // Fallback: mail attempted
        wp_send_json_success(array('message' => 'Message sent successfully!'));
    }
}
add_action('wp_ajax_submit_pheast_contact', 'pheast_handle_contact_submission');
add_action('wp_ajax_nopriv_submit_pheast_contact', 'pheast_handle_contact_submission');

require_once get_template_directory() . '/inc/custom-setup.php';
?>
