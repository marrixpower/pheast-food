<?php
// If front page or home, load front-page.php
if (is_front_page() || is_home()) {
    include get_template_directory() . '/front-page.php';
    exit;
}
get_header();
if (have_posts()) { while(have_posts()) { the_post(); the_content(); } }
get_footer();
?>