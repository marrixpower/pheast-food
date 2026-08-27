<!DOCTYPE html>
<html lang="en" data-theme="bold">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
  <meta name="description" content="Experience authentic Asian Street Food at The Battery Atlanta.">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Outfit:wght@800;900&family=Fredoka:wght@600;700&family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">

<?php wp_head(); ?>
</head>
<body>
  <!-- THEME SWITCHER -->

  <!-- NAVIGATION -->
  <header class="main-header">
    <div class="container header-inner">
      <a href="<?php echo home_url('/'); ?>" class="brand-logo">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/PHEAST-logo-transparent.png" alt="PH'EAST Food Hall" style="height: 52px; width: auto; object-fit: contain;" class="logo-main">
      </a>

      <!-- FULLSCREEN MOBILE NAV & DESKTOP MENU -->
      <nav class="nav-menu">
        <button class="mobile-menu-close" onclick="toggleMobileMenu()" aria-label="Close Menu">&times;</button>
        
        <div class="mobile-menu-top-btn-wrap">
          <button class="btn btn-primary mobile-menu-top-btn" onclick="openOrderModal(); toggleMobileMenu();">ORDER ONLINE</button>
        </div>

        <div class="mobile-menu-links">
          <?php
          if ( has_nav_menu( 'primary' ) ) {
              $menu_html = wp_nav_menu( array(
                  'theme_location' => 'primary',
                  'echo'           => false,
                  'container'      => false,
                  'items_wrap'     => '%3$s'
              ) );
              echo strip_tags( $menu_html, '<a>' );
          } else {
              $is_home = is_front_page() ? ' active' : '';
              $is_about = is_page('about') ? ' active' : '';
              $is_vendor = (is_page('vendor') || is_page('vendors') || is_singular('vendor')) ? ' active' : '';
              $is_events = (is_page('events') || is_singular('event') || is_post_type_archive('event')) ? ' active' : '';
              $is_gallery = is_page('gallery') ? ' active' : '';
              $is_contact = is_page('contact') ? ' active' : '';
              
              echo '<a href="' . esc_url(home_url('/')) . '" class="nav-link' . $is_home . '">Home</a>';
              echo '<a href="' . esc_url(home_url('/about/')) . '" class="nav-link' . $is_about . '">About</a>';
              echo '<a href="' . esc_url(home_url('/vendor/')) . '" class="nav-link' . $is_vendor . '">Vendors</a>';
              echo '<a href="' . esc_url(home_url('/events/')) . '" class="nav-link' . $is_events . '">Events</a>';
              echo '<a href="' . esc_url(home_url('/gallery/')) . '" class="nav-link' . $is_gallery . '">Gallery</a>';
              echo '<a href="' . esc_url(home_url('/contact/')) . '" class="nav-link' . $is_contact . '">Contact</a>';
          }
          ?>
        </div>

        <div class="mobile-menu-footer">
          <div class="mobile-menu-card">
            <span class="mobile-menu-card-label">CONTACT INFO</span>
            <p class="mobile-menu-card-address"><?php echo nl2br(esc_html(get_field('global_address', 'option') ?: "The Battery Atlanta — 925 Battery Ave SE\nSuite 1100, Atlanta, GA 30339")); ?></p>
            <?php 
              $phone = get_field('contact_main_phone', 7) ?: '+1 (404) 343-0409';
              $phone_clean = preg_replace('/[^0-9+]/', '', $phone);
            ?>
            <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="mobile-menu-card-phone"><?php echo esc_html($phone); ?></a>
            <div class="mobile-menu-socials">
              <a href="<?php echo esc_url(get_field('global_ig', 'option') ?: 'https://www.instagram.com/pheastfoodhall/'); ?>" target="_blank" class="mobile-social-link" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
              <a href="<?php echo esc_url(get_field('global_fb', 'option') ?: 'https://www.facebook.com/pheastfoodhall/'); ?>" target="_blank" class="mobile-social-link" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
              <a href="<?php echo esc_url(get_field('global_yelp', 'option') ?: 'https://www.yelp.com/biz/pheast-food-hall-atlanta'); ?>" target="_blank" class="mobile-social-link" aria-label="Yelp"><i class="fa-brands fa-yelp"></i></a>
            </div>
            <button class="btn btn-primary mobile-menu-card-btn" onclick="openOrderModal(); toggleMobileMenu();">ORDER ONLINE</button>
          </div>
        </div>
      </nav>

      <!-- RIGHT HEADER CONTROLS (DESKTOP CTA + MOBILE HAMBURGER) -->
      <div style="display: flex; gap: 15px; align-items: center;">
        <button class="btn btn-primary header-desktop-order-btn" onclick="openOrderModal()">ORDER ONLINE</button>
        <button class="mobile-nav-toggle" onclick="toggleMobileMenu()" aria-label="Toggle Navigation">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>
    </div>
  </header>