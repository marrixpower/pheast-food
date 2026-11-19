<?php
get_header();

$hero_video = get_field('home_hero_video_url') ?: get_field('home_hero_video');
$hero_bg = get_field('home_hero_bg') ?: get_template_directory_uri() . '/assets/_71A5775-HDR.jpg';
$hero_title = get_field('home_hero_title') ?: "ONE FOOD HALL.<br>ENDLESS FLAVOR.";
$hero_subtitle = get_field('home_hero_subtitle') ?: "Asian street food. Local hawkers.<br>Located at The Battery Atlanta.";
$vendors_title = get_field('home_vendors_title') ?: '<span style="color: #D41F3C;">FIVE FLAVORS.</span> <span style="color: #000000;">ONE ROOF.</span>';
$concept_img = get_field('home_concept_image') ?: get_template_directory_uri() . '/assets/manga_illustration.png?v=2';
$concept_banner = get_field('home_concept_banner') ?: 'BRINGING A HAWKERS STYLE STREET MARKET TO THE BATTERY ATLANTA.';
$concept_text = get_field('home_concept_text') ?: "PH'EAST is where you come together for asian street food, live sports, events, and unforgettable vibes. From noodles and boba to cocktails and music, every visit feels like stepping into a modern Asian street market right in the heart of The Battery.";
?>

  <main>
    <!-- HERO -->
    <section class="hero-section hero-left">
      <div class="hero-backdrop">
        <?php if (!empty($hero_video)): ?>
          <video class="hero-bg-video" autoplay loop muted playsinline webkit-playsinline="true" preload="auto" disablepictureinpicture disableremoteplayback poster="<?php echo esc_url($hero_bg); ?>">
            <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
            <img src="<?php echo esc_url($hero_bg); ?>" alt="PH'EAST Exterior">
          </video>
        <?php else: ?>
          <img src="<?php echo esc_url($hero_bg); ?>" alt="PH'EAST Exterior">
        <?php endif; ?>
        <div class="overlay"></div>
      </div>
      <div class="hero-content">
        <h1 class="hero-title" style="font-size: 5.2rem; text-transform: uppercase; font-family: 'Balboa Bold', 'Balboa', 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-weight: 700; line-height: 1.05;">
          <?php echo $hero_title; ?>
        </h1>
        <p class="hero-subtitle" style="font-size: 1.08rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; line-height: 1.45; max-width: 680px; margin: 0 0 30px 0; text-align: left; color: #ffffff;">
          <span style="display: block;">Asian street food. Local hawkers.</span>
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
    <section id="next-section" class="section bg-secondary-section" style="background-color: #ffffff; color: #000000; padding: 45px 0 50px 0;">
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
            $home_vendor_cards = get_field('home_vendor_cards');
            
            $resolve_vendor_link = function($att_id, $img_url, $title_or_alt, $default_index) {
                if ($att_id) {
                    $att_post = get_post($att_id);
                    if ($att_post) {
                        $caption = trim($att_post->post_excerpt);
                        $desc = trim($att_post->post_content);
                        if (!empty($caption) && (filter_var($caption, FILTER_VALIDATE_URL) || strpos($caption, '/') === 0)) {
                            return $caption;
                        }
                        if (!empty($desc) && (filter_var($desc, FILTER_VALIDATE_URL) || strpos($desc, '/') === 0)) {
                            return $desc;
                        }
                    }
                }
                $str = strtolower($img_url . ' ' . $title_or_alt);
                if (strpos($str, '05') !== false || strpos($str, 'taps') !== false) {
                    return home_url('/vendors/taps-pheast/');
                }
                if (strpos($str, '06') !== false || strpos($str, 'kungfu') !== false || strpos($str, 'kung fu') !== false || strpos($str, 'boba') !== false) {
                    return home_url('/kungfutea/');
                }
                if (strpos($str, '07') !== false || strpos($str, 'poke') !== false || strpos($str, 'burri') !== false) {
                    return home_url('/vendors/poke-burri/');
                }
                if (strpos($str, '08') !== false || strpos($str, 'fuzion') !== false || strpos($str, 'noodle') !== false || strpos($str, 'ramen') !== false) {
                    return home_url('/vendors/lifting-noodles-ramen/');
                }
                if (strpos($str, '09') !== false || strpos($str, 'thai') !== false || strpos($str, '26') !== false) {
                    return home_url('/vendors/26-thai-kitchen-bar/');
                }
                if (strpos($str, '10') !== false || strpos($str, 'fan') !== false || strpos($str, 'tasia') !== false) {
                    return home_url('/vendors/fan-tasia/');
                }
                static $cached_vendors = null;
                if ($cached_vendors === null) {
                    $cached_vendors = get_posts([
                        'post_type' => 'vendor',
                        'posts_per_page' => -1,
                        'orderby' => 'menu_order',
                        'order' => 'ASC'
                    ]);
                }
                if (!empty($cached_vendors) && isset($cached_vendors[$default_index])) {
                    $v = $cached_vendors[$default_index];
                    $is_kft = (stripos($v->post_title, 'Kung Fu') !== false);
                    return $is_kft ? home_url('/kungfutea/') : get_permalink($v->ID);
                }
                return home_url('/vendor/');
            };

            if (!empty($home_vendor_cards)):
                foreach ($home_vendor_cards as $idx => $card):
                    $card_url = '';
                    $card_alt = "PH'EAST Vendor";
                    $card_id = 0;
                    if (is_array($card)) {
                        $card_url = $card['url'];
                        $card_alt = !empty($card['alt']) ? $card['alt'] : (!empty($card['title']) ? $card['title'] : $card_alt);
                        $card_id = !empty($card['ID']) ? $card['ID'] : 0;
                    } elseif (is_numeric($card)) {
                        $card_id = (int)$card;
                        $card_url = wp_get_attachment_image_url($card, 'full');
                        $alt = get_post_meta($card, '_wp_attachment_image_alt', true);
                        if ($alt) $card_alt = $alt;
                    } else {
                        $card_url = $card;
                    }
                    if (!$card_url) continue;
                    $vendor_link = $resolve_vendor_link($card_id, $card_url, $card_alt, $idx);
            ?>
            <div class="vendor-card">
              <a href="<?php echo esc_url($vendor_link); ?>" style="display: block; width: 100%; height: 100%; text-decoration: none; cursor: pointer;">
                <img src="<?php echo esc_url($card_url); ?>" alt="<?php echo esc_attr($card_alt); ?>" class="vendor-card-img" style="width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 20px;">
              </a>
            </div>
            <?php
                endforeach;
            else:
                $vendors_query = new WP_Query(array(
                    'post_type' => 'vendor',
                    'posts_per_page' => -1,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));
                $fallback_idx = 0;
                while ($vendors_query->have_posts()): $vendors_query->the_post();
                    $card_url = get_field('vendor_card_rectangle') ?: get_post_meta(get_the_ID(), 'vendor_card_rectangle', true);
                    if (!$card_url) {
                        $square = get_field('vendor_logo') ?: get_the_post_thumbnail_url();
                        if ($square && preg_match('/Vendor-Cards-(1[1-6])\.png/i', $square, $m)) {
                            $num = intval($m[1]) - 6;
                            $card_url = str_replace($m[0], sprintf('Vendor-Cards-%02d.png', $num), $square);
                        } else {
                            $card_url = sprintf('https://snow-quetzal-139443.hostingersite.com/wp-content/uploads/2026/09/Vendor-Cards-%02d.png', $fallback_idx + 5);
                        }
                    }
                    $is_kft = (stripos(get_the_title(), 'Kung Fu') !== false);
                    $vendor_link = $is_kft ? home_url('/kungfutea/') : get_permalink();
                    $fallback_idx++;
            ?>
            <div class="vendor-card">
              <a href="<?php echo esc_url($vendor_link); ?>" style="display: block; width: 100%; height: 100%; text-decoration: none; cursor: pointer;">
                <img src="<?php echo esc_url($card_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="vendor-card-img" style="width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 20px;">
              </a>
            </div>
            <?php endwhile; wp_reset_postdata(); endif; ?>
          </div>
          <div class="carousel-mobile-swipe-hint vendors-mobile-swipe-hint">
            <i class="fa-solid fa-arrows-left-right"></i>
            <span>Swipe for more vendors</span>
            <i class="fa-solid fa-chevron-right pulse-arrow"></i>
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
            <img src="<?php echo esc_url($concept_img); ?>" alt="PH'EAST Hawkers Style Street Market Illustration" class="manga-frame-img">
          </div>
          
          <!-- Right: Text & Banner Box -->
          <div class="hawker-text-col">
            <div class="hawker-red-banner">
              <h2><?php echo esc_html($concept_banner); ?></h2>
            </div>
            <p class="hawker-description">
              <?php echo esc_html($concept_text); ?>
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
          <div class="gallery-photo-item">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/real_gallery_1.jpg" alt="PH'EAST Food & Drinks 1" class="gallery-img">
          </div>
          <div class="gallery-photo-item">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/real_gallery_2.jpg" alt="PH'EAST Food & Drinks 2" class="gallery-img">
          </div>
          <div class="gallery-photo-item">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/real_gallery_3.jpg" alt="PH'EAST Food & Drinks 3" class="gallery-img">
          </div>
          <div class="gallery-photo-item">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/real_gallery_4.jpg" alt="PH'EAST Food & Drinks 4" class="gallery-img">
          </div>
          <div class="gallery-photo-item">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/real_gallery_5.jpg" alt="PH'EAST Crowd Atmosphere 1" class="gallery-img">
          </div>
          <div class="gallery-photo-item">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/real_gallery_6.jpg" alt="PH'EAST Crowd Atmosphere 2" class="gallery-img">
          </div>
          <div class="gallery-photo-item">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/real_gallery_7.jpg" alt="PH'EAST Crowd Atmosphere 3" class="gallery-img">
          </div>
          <div class="gallery-photo-item">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/real_gallery_8.jpg" alt="PH'EAST Crowd Atmosphere 4" class="gallery-img">
          </div>
        </div>
      </div>
    </section>

    <!-- EVENTS SECTION -->
    <section class="section bg-light-events upcoming-events" style="padding: 20px 0; background: #ffffff; color: #000000;">
      <div class="container" style="max-width: 1180px;">
        <div class="events-header-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 15px;">
          <h2 class="events-section-title" style="margin: 0; font-size: 3.2rem; color: #D41F3C; font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; text-transform: uppercase; letter-spacing: 1px;">UPCOMING EVENTS</h2>
          <a href="<?php echo home_url('/events'); ?>" class="events-view-all-link" style="color: #000000; font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 3.2rem; text-decoration: none; display: inline-flex; align-items: center; gap: 12px; text-transform: uppercase; letter-spacing: 1.5px;">
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
            $events_query = new WP_Query(array(
                'post_type'      => 'event',
                'posts_per_page' => -1,
                'orderby'        => 'meta_value',
                'meta_key'       => 'event_date',
                'order'          => 'ASC'
            ));
            
            if ($events_query->have_posts()):
                while ($events_query->have_posts()): $events_query->the_post();
                    $raw_date = get_field('event_date');
                    $time = get_field('event_time');
                    $event_link = get_field('event_link') ?: get_permalink();

                    $day = 'EVENT';
                    $date_num = 'UPCOMING';
                    if (!empty($raw_date)) {
                        $timestamp = strtotime($raw_date);
                        if (!$timestamp) {
                            $dt = DateTime::createFromFormat('F j, Y', $raw_date);
                            if (!$dt) $dt = DateTime::createFromFormat('Ymd', $raw_date);
                            if (!$dt) $dt = DateTime::createFromFormat('m/d/Y', $raw_date);
                            if ($dt) $timestamp = $dt->getTimestamp();
                        }
                        if ($timestamp) {
                            $day = strtoupper(date('l', $timestamp));
                            $date_num = strtoupper(date('F j', $timestamp));
                        } else {
                            $date_num = strtoupper($raw_date);
                        }
                    }
            ?>
            <a href="<?php echo esc_url($event_link); ?>" class="event-mock-card">
              <div class="event-date-box">
                <span class="day"><?php echo esc_html($day); ?></span>
                <span class="date"><?php echo esc_html($date_num); ?></span>
              </div>
              <div class="event-info-box">
                <h3 class="event-name"><?php the_title(); ?></h3>
                <?php if ($time): ?>
                  <p class="event-time"><?php echo esc_html($time); ?></p>
                <?php endif; ?>
              </div>
            </a>
            <?php
                endwhile;
                wp_reset_postdata();
            else:
            ?>
            <div class="event-mock-card">
              <div class="event-date-box">
                <span class="day">MONDAY</span>
                <span class="date">MAY 31</span>
              </div>
              <div class="event-info-box">
                <h3 class="event-name">TRIVIA NIGHT</h3>
                <p class="event-time">7-10 PM</p>
              </div>
            </div>
            <div class="event-mock-card">
              <div class="event-date-box">
                <span class="day">WEDNESDAY</span>
                <span class="date">JUNE 2</span>
              </div>
              <div class="event-info-box">
                <h3 class="event-name">BRAVES<br>HOME GAME</h3>
                <p class="event-time">6:00 PM</p>
              </div>
            </div>
            <div class="event-mock-card">
              <div class="event-date-box">
                <span class="day">THURSDAY</span>
                <span class="date">JUNE 3</span>
              </div>
              <div class="event-info-box">
                <h3 class="event-name">KARAOKE<br>NIGHT</h3>
                <p class="event-time">7-11 PM</p>
              </div>
            </div>
            <div class="event-mock-card">
              <div class="event-date-box">
                <span class="day">SATURDAY</span>
                <span class="date">JUNE 5</span>
              </div>
              <div class="event-info-box">
                <h3 class="event-name">LIVE MUSIC</h3>
                <p class="event-time">5-9 PM</p>
              </div>
            </div>
            <div class="event-mock-card">
              <div class="event-date-box">
                <span class="day">SUNDAY</span>
                <span class="date">JUNE 6</span>
              </div>
              <div class="event-info-box">
                <h3 class="event-name">SUNDAY<br>DJ SET</h3>
                <p class="event-time">4-8 PM</p>
              </div>
            </div>
            <div class="event-mock-card">
              <div class="event-date-box">
                <span class="day">TUE</span>
                <span class="date">8</span>
              </div>
              <div class="event-info-box">
                <h3 class="event-name">TACO & SAKÉ<br>TUESDAY</h3>
                <p class="event-time">5-10 PM</p>
              </div>
            </div>
            <div class="event-mock-card">
              <div class="event-date-box">
                <span class="day">FRI</span>
                <span class="date">11</span>
              </div>
              <div class="event-info-box">
                <h3 class="event-name">ASIAN STREET<br>FEAST</h3>
                <p class="event-time">6-11 PM</p>
              </div>
            </div>
            <?php endif; ?>
          </div>
          <div class="carousel-mobile-swipe-hint events-mobile-swipe-hint">
            <i class="fa-solid fa-arrows-left-right"></i>
            <span>Swipe for more events</span>
            <i class="fa-solid fa-chevron-right pulse-arrow"></i>
          </div>
        </div>
      </div>
    </section>

  </main>

<?php get_footer(); ?>