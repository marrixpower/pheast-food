<?php get_header(); ?>



  <!-- HERO -->
  <section class="hero-section hero-left">
    <div class="hero-backdrop">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/_71A5775-HDR.jpg" alt="PH'EAST Exterior">
      <div class="overlay"></div>
    </div>
    <div class="hero-content">
      <h1 class="hero-title" style="font-size: 5.2rem; text-transform: uppercase; font-family: 'Balboa Bold', 'Balboa', 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-weight: 700; line-height: 1.05;"><?php echo get_field("home_hero_heading") ?: "ONE FOOD HALL.<br>ENDLESS FLAVOR."; ?></h1>
      <p class="hero-subtitle" style="font-size: 1.08rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; line-height: 1.45; max-width: 680px; margin: 0 auto 30px auto; color: #ffffff;">
        <?php $sub = get_field("home_hero_subtitle") ?: "Asian street food. Local hawkers."; echo "<span style='display: block;'>" . esc_html($sub) . "</span>"; ?>
        <span style="display: block;">Located at The Battery Atlanta.</span>
      </p>
      <div class="hero-buttons">
        <button class="btn btn-primary" onclick="openOrderModal()">Order Online</button>
        <a href="<?php echo home_url('/events'); ?>" class="btn btn-outline" style="border-color: #ffffff; color: #ffffff; background: rgba(0, 0, 0, 0.4);">What's Happening</a>
      </div>
    </div>
    <a href="#next-section" class="hero-scroll-indicator" onclick="scrollToNextSection(event)" aria-label="Scroll to next section">
      <i class="fa-solid fa-chevron-down"></i>
    </a>
  </section>

  <!-- VENDORS -->
  <section class="section bg-secondary-section" style="background-color: #ffffff; color: #000000; padding: 45px 0 50px 0;">
    <div class="container" style="position: relative;">
      <div class="section-header" style="margin-bottom: 20px; text-align: left;">
        <h2 class="section-title" style="text-align: left;"><span style="color: #D41F3C;">FIVE FLAVORS.</span> <span style="color: #000000;">ONE ROOF.</span></h2>
      </div>
      <div class="carousel-track-container">
        <!-- Carousel controls -->
        <div class="carousel-controls">
          <button class="carousel-btn prev" onclick="slideCarousel(-1)" aria-label="Previous Slide">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="50" cy="50" r="45" fill="var(--accent-primary)"/>
              <path d="M68 52 C55 51 45 49 32 49" stroke="white" stroke-width="5" stroke-linecap="round"/>
              <path d="M48 37 C42 41 37 45 30 49 C36 53 41 57 47 62" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <button class="carousel-btn next" onclick="slideCarousel(1)" aria-label="Next Slide">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="50" cy="50" r="45" fill="var(--accent-primary)"/>
              <path d="M32 52 C45 51 55 49 68 49" stroke="white" stroke-width="5" stroke-linecap="round"/>
              <path d="M52 37 C58 41 63 45 70 49 C64 53 59 57 53 62" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>

        
<div class="vendors-grid carousel-track">
    <?php
    $vendors_query = new WP_Query(array(
        'post_type' => 'vendor',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));
    while( $vendors_query->have_posts() ): $vendors_query->the_post();
        $show_card = get_field('vendor_card_show');
        if ($show_card !== false && $show_card !== '0' && $show_card !== 0):
        $logo_url = get_field('vendor_logo');
        if (!$logo_url && has_post_thumbnail()) {
            $logo_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
        }
        if (!$logo_url) {
            $logo_url = get_post_meta(get_the_ID(), 'demo_logo_url', true);
        }
        $cuisine = get_field('vendor_cuisine') ?: 'ASIAN STREET FOOD';
        $description = get_field('vendor_description');
        if (empty($description)) {
            $description = wp_strip_all_tags(strip_shortcodes(get_the_content()));
        }
        $btn_text = get_field('vendor_btn_text') ?: 'ORDER ONLINE';
        $btn_type = get_field('vendor_btn_type') ?: 'modal';
        $btn_link = get_field('vendor_btn_link');
        
        $is_kft = (stripos(get_the_title(), 'Kung Fu') !== false);
        if ($is_kft && empty($btn_link)) {
            $btn_link = home_url('/kungfutea/');
        }
    ?>
    <div class="vendor-card reveal-element reveal-scale" style="background: #000000; border: 1px solid rgba(243, 15, 61, 0.3); border-radius: 12px; overflow: hidden; transition: transform 0.3s ease; display: flex; flex-direction: column; height: 100%;">
      <a href="<?php the_permalink(); ?>" style="display: block; text-decoration: none;">
        <div style="background: #000; padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
          <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="max-width: 220px; width: 100%; height: auto; display: inline-block;">
        </div>
      </a>

      <div style="padding: 25px; display: flex; flex-direction: column; flex-grow: 1;">
        <span style="color: #D41F3C; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px;"><?php echo esc_html($cuisine); ?></span>
        <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;">
          <h3 style="color: #ffffff; font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 1.8rem; margin: 0 0 10px 0;"><?php echo esc_html(get_field('vendor_card_title') ?: get_the_title()); ?></h3>
        </a>
        <p style="color: rgba(255,255,255,0.75); font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px; flex-grow: 1;"><?php echo esc_html($description); ?></p>
        
        <a href="<?php the_permalink(); ?>" class="btn btn-outline" style="width: 100%; border: 1px solid #D41F3C; color: #ffffff; display: block; text-align: center; text-decoration: none; margin-top: auto;"><?php echo esc_html($btn_text ?: 'ORDER ONLINE'); ?></a>
      </div>
    </div>
    <?php endif; endwhile; wp_reset_postdata(); ?>
</div>

      </div>
    </div>
  </section>

  <!-- ABOUT CONCEPT SECTION -->
  <section class="section bg-dark-concept" style="padding: 90px 0; background: #000000;">
    <div class="container" style="max-width: 1180px;">
      <div class="hawker-concept-grid">
        <!-- Left: Manga Illustration -->
        <div class="hawker-image-col">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/manga_illustration.png?v=2" alt="PH'EAST Hawkers Style Street Market Illustration" class="manga-frame-img">
        </div>
        
        <!-- Right: Text & Banner Box -->
        <div class="hawker-text-col">
          <div class="hawker-red-banner">
            <h2>BRINGING A HAWKERS STYLE STREET MARKET TO THE BATTERY ATLANTA.</h2>
          </div>
          <p class="hawker-description">
            <?php echo get_field('home_about_text') ?: "PH'EAST is where you come together for asian street food, live sports, events, and unforgettable vibes. From noodles and boba to cocktails and music, every visit feels like stepping into a modern Asian street market right in the heart of The Battery."; ?>
          </p>
          <a href="<?php echo home_url('/about'); ?>" class="hawker-learn-btn">LEARN MORE</a>
        </div>
      </div>
    </div>
  </section>

  <!-- GALLERY PHOTO CAROUSEL SECTION -->
  <section class="section gallery-carousel-section" style="padding: 0; background: #000; overflow: hidden; position: relative;">
    <div class="gallery-carousel-wrapper">
      <!-- Controls -->
      <div class="gallery-controls">
        <button class="gallery-btn prev" onclick="slideGalleryCarousel(-1)" aria-label="Previous Photo">
          <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="45" fill="#000000"/>
            <path d="M68 52 C55 51 45 49 32 49" stroke="#ffffff" stroke-width="5" stroke-linecap="round"/>
            <path d="M48 37 C42 41 37 45 30 49 C36 53 41 57 47 62" stroke="#ffffff" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <button class="gallery-btn next" onclick="slideGalleryCarousel(1)" aria-label="Next Photo">
          <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="45" fill="#000000"/>
            <path d="M32 52 C45 51 55 49 68 49" stroke="#ffffff" stroke-width="5" stroke-linecap="round"/>
            <path d="M52 37 C58 41 63 45 70 49 C64 53 59 57 53 62" stroke="#ffffff" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>

      <!-- Track -->
      <div class="gallery-track">
        <?php 
        $carousel_photos = get_field('home_carousel_photos');
        if (!empty($carousel_photos)): 
          foreach ($carousel_photos as $index => $photo):
            $img_url = '';
            $img_alt = "PH'EAST Atmosphere " . ($index + 1);
            if (is_array($photo)) {
              $img_url = $photo['url'];
              if (!empty($photo['alt'])) $img_alt = $photo['alt'];
            } elseif (is_numeric($photo)) {
              $img_url = wp_get_attachment_image_url($photo, 'full');
              $alt = get_post_meta($photo, '_wp_attachment_image_alt', true);
              if ($alt) $img_alt = $alt;
            } else {
              $img_url = $photo;
            }
            $delay = ($index % 4) + 1;
        ?>
          <div class="gallery-photo-item reveal-element reveal-scale reveal-delay-<?php echo $delay; ?>">
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" class="gallery-img">
          </div>
        <?php 
          endforeach;
        else: 
          // Default fallback photos
          for ($i = 1; $i <= 8; $i++):
            $delay = (($i - 1) % 4) + 1;
        ?>
          <div class="gallery-photo-item reveal-element reveal-scale reveal-delay-<?php echo $delay; ?>">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/real_gallery_<?php echo $i; ?>.jpg" alt="PH'EAST Food & Drinks <?php echo $i; ?>" class="gallery-img">
          </div>
        <?php 
          endfor;
        endif; 
        ?>
      </div>
    </div>
  </section>

  <!-- EVENTS SECTION -->
  <section class="section bg-light-events" style="padding: 20px 0; background: #ffffff; color: #000000;">
    <div class="container" style="max-width: 1180px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 15px;">
        <h2 style="margin: 0; font-size: 3.2rem; color: #D41F3C; font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; text-transform: uppercase; letter-spacing: 1px;">UPCOMING EVENTS</h2>
        <a href="<?php echo home_url('/events'); ?>" style="color: #000000; font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 3.2rem; text-decoration: none; display: inline-flex; align-items: center; gap: 12px; text-transform: uppercase; letter-spacing: 1.5px;">
          VIEW ALL EVENTS
          <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
            <line x1="4" y1="12" x2="20" y2="12"></line>
            <polyline points="13 5 20 12 13 19"></polyline>
          </svg>
        </a>
      </div>

      <div class="events-carousel-container">
        <!-- Controls -->
        <div class="events-controls">
          <button class="events-btn prev" onclick="slideEventsCarousel(-1)" aria-label="Previous Event">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="50" cy="50" r="45" fill="#000000"/>
              <path d="M68 52 C55 51 45 49 32 49" stroke="white" stroke-width="5" stroke-linecap="round"/>
              <path d="M48 37 C42 41 37 45 30 49 C36 53 41 57 47 62" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <button class="events-btn next" onclick="slideEventsCarousel(1)" aria-label="Next Event">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="50" cy="50" r="45" fill="#000000"/>
              <path d="M32 52 C45 51 55 49 68 49" stroke="white" stroke-width="5" stroke-linecap="round"/>
              <path d="M52 37 C58 41 63 45 70 49 C64 53 59 57 53 62" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>

        <!-- Track -->
        

<div class="events-track">
    <?php
    $events_query = new WP_Query(array('post_type' => 'event', 'posts_per_page' => -1));
    while( $events_query->have_posts() ): $events_query->the_post();
        $raw_date = get_field('event_date'); 
        if($raw_date) {
            $dt = DateTime::createFromFormat('F j, Y', $raw_date);
            if($dt) {
                $day = $dt->format('D');
                $date_num = $dt->format('j');
            } else {
                $day = 'TBD';
                $date_num = '--';
            }
        } else {
            $day = 'TBD';
            $date_num = '--';
        }
    ?>
    <div class="event-mock-card">
        <div class="event-date-box">
            <span class="day"><?php echo strtoupper($day); ?></span>
            <span class="date"><?php echo $date_num; ?></span>
        </div>
        <div class="event-info-box">
            <h3 class="event-name"><?php the_title(); ?></h3>
            <p class="event-time"><?php echo esc_html(get_field('event_time')); ?></p>
        </div>
    </div>
    <?php endwhile; wp_reset_postdata(); ?>
</div>

      </div>
    </div>
  </section>

  <!-- FOOTER -->
  
<?php get_footer(); ?>