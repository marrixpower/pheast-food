<!DOCTYPE html>
<html lang="en" data-theme="bold">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PH'EAST | One Food Hall. Endless Flavor.</title>
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
      <a href="<?php echo home_url(); ?>" class="brand-logo">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/PHEAST-logo-transparent.png" alt="PH'EAST Food Hall" style="height: 52px; width: auto; object-fit: contain;" class="logo-main">
      </a>
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
              echo '<a href="' . admin_url('nav-menus.php') . '" class="nav-link">Setup Menu</a>';
          }
          ?>
        </div>
      </nav>
      <div class="nav-actions">
        <button class="btn btn-primary header-cta" onclick="openOrderModal()">ORDER ONLINE</button>
        <button class="hamburger-btn" onclick="toggleMobileMenu()" aria-label="Open Menu">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </div>
  </header>