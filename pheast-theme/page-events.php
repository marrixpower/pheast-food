<?php
/* Template Name: Events Page */
get_header();

$hero_bg = get_field('events_hero_bg') ?: get_template_directory_uri() . '/assets/events-live.jpg';
$hero_title = get_field('events_hero_title') ?: "EVENTS @ PH'EAST";
$hero_subtitle = get_field('events_hero_subtitle') ?: "ALWAYS SOMETHING HAPPENING.";
$hero_desc = get_field('events_hero_desc') ?: "From live music to game nights & special collabs.<br>There's always a reason to gather at PH'EAST.";
?>
  <main>
    <!-- HERO SECTION -->
    <section class="hero-section">
      <div class="hero-backdrop">
        <img src="<?php echo esc_url($hero_bg); ?>" alt="Events Live">
        <div class="overlay"></div>
      </div>
      <div class="hero-content">
        <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
        <p class="hero-subtitle" style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #D41F3C;"><?php echo esc_html($hero_subtitle); ?></p>
        <p class="hero-subtitle hero-desc" style="font-size: 1.08rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; line-height: 1.45; max-width: 680px; margin: 0 auto; color: #ffffff; animation-delay: 0.7s;"><?php echo wp_kses_post($hero_desc); ?></p>
      </div>
      <a href="#next-section" class="hero-scroll-indicator" onclick="scrollToNextSection(event)" aria-label="Scroll to next section">
        <i class="fa-solid fa-chevron-down"></i>
      </a>
    </section>

    <!-- UPCOMING EVENTS SECTION (5-CARD CAROUSEL) -->
    <section id="next-section" class="upcoming-events" style="padding: 20px 0; background: #ffffff; color: #000000;">
      <div class="container" style="max-width: 1180px;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 15px;">
          <h2 style="margin: 0; font-size: 3.2rem; color: #D41F3C; font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; text-transform: uppercase; letter-spacing: 1px;">
            <?php echo esc_html(get_field('events_sec_title') ?: "UPCOMING EVENTS"); ?>
          </h2>
          <a href="<?php echo home_url('/events'); ?>" style="color: #000000; font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 3.2rem; text-decoration: none; display: inline-flex; align-items: center; gap: 12px; text-transform: uppercase; letter-spacing: 1.5px;">
            VIEW ALL EVENTS
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
              <line x1="4" y1="12" x2="20" y2="12"></line>
              <polyline points="13 5 20 12 13 19"></polyline>
            </svg>
          </a>
        </div>

        <!-- 5-Card Carousel Container -->
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

          <!-- Cards Track (5 visible in a row) -->
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
                            $dt = DateTime::createFromFormat('Ymd', $raw_date);
                            if ($dt) $timestamp = $dt->getTimestamp();
                        }
                        if ($timestamp) {
                            $day = strtoupper(date('l', $timestamp));     // e.g. MONDAY
                            $date_num = strtoupper(date('M j', $timestamp)); // e.g. MAY 31
                        } else {
                            $date_num = strtoupper($raw_date);
                        }
                    }
            ?>
            <a href="<?php echo esc_url($event_link); ?>" class="event-mock-card" style="text-decoration: none; color: inherit; display: block;">
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
                echo '<p style="padding: 20px; font-weight: bold; color: #000;">No upcoming events currently scheduled. Check back soon!</p>';
            endif;
            ?>
          </div>
        </div>
      </div>
    </section>

    <!-- PRIVATE EVENTS & GROUP BOOKINGS SECTION -->
    <section class="private-events" style="background: #000000; color: #ffffff; padding: 60px 20px; text-align: center;">
      <div class="container" style="max-width: 1180px;">
        <h2 style="font-size: 2.5rem; margin-bottom: 20px; color: #ffffff; font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; text-transform: uppercase;">
          <?php echo esc_html(get_field('events_private_title') ?: "PRIVATE EVENTS & GROUP BOOKINGS"); ?>
        </h2>
        <p style="font-size: 1.1rem; max-width: 600px; margin: 0 auto 40px; color: rgba(255, 255, 255, 0.85);">
          <?php echo esc_html(get_field('events_private_desc') ?: "Planning a party, team outing, or special celebration? We've got the space and the flavor."); ?>
        </p>
        
        <div class="private-events-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 40px;">
          <div style="background: #000000; color: #ffffff; border: 2px solid #D41F3C; padding: 25px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.4);">
            <div style="margin-bottom: 15px; height: 60px; display: flex; align-items: center; justify-content: center;">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/Ph_east Website Icons-06.png" alt="Group Dining" style="height: 50px; width: auto;" />
            </div>
            <h4 style="color: #ffffff; font-family: 'Bebas Kai', sans-serif; font-size: 1.4rem; margin-bottom: 5px;">GROUP DINING</h4>
            <p style="margin-top: 10px; color: rgba(255,255,255,0.85); font-size: 0.95rem;">Perfect for team outings, birthdays, and more.</p>
          </div>

          <div style="background: #000000; color: #ffffff; border: 2px solid #D41F3C; padding: 25px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.4);">
            <div style="margin-bottom: 15px; height: 60px; display: flex; align-items: center; justify-content: center;">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/Ph_east Website Icons-07.png" alt="Private Spaces" style="height: 50px; width: auto;" />
            </div>
            <h4 style="color: #ffffff; font-family: 'Bebas Kai', sans-serif; font-size: 1.4rem; margin-bottom: 5px;">PRIVATE SPACES</h4>
            <p style="margin-top: 10px; color: rgba(255,255,255,0.85); font-size: 0.95rem;">Semi-private & private areas available for your event.</p>
          </div>

          <div style="background: #000000; color: #ffffff; border: 2px solid #D41F3C; padding: 25px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.4);">
            <div style="margin-bottom: 15px; height: 60px; display: flex; align-items: center; justify-content: center;">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/Ph_east Website Icons-08.png" alt="Custom Packages" style="height: 50px; width: auto;" />
            </div>
            <h4 style="color: #ffffff; font-family: 'Bebas Kai', sans-serif; font-size: 1.4rem; margin-bottom: 5px;">CUSTOM PACKAGES</h4>
            <p style="margin-top: 10px; color: rgba(255,255,255,0.85); font-size: 0.95rem;">Food, drinks, and experiences tailored to your group.</p>
          </div>

          <div style="background: #000000; color: #ffffff; border: 2px solid #D41F3C; padding: 25px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.4);">
            <div style="margin-bottom: 15px; height: 60px; display: flex; align-items: center; justify-content: center;">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/Ph_east Website Icons-09.png" alt="Easy Booking" style="height: 50px; width: auto;" />
            </div>
            <h4 style="color: #ffffff; font-family: 'Bebas Kai', sans-serif; font-size: 1.4rem; margin-bottom: 5px;">EASY BOOKING</h4>
            <p style="margin-top: 10px; color: rgba(255,255,255,0.85); font-size: 0.95rem;">Tell us what you need. We'll handle the rest.</p>
          </div>
        </div>

        <?php $book_link = get_field('events_book_link') ?: home_url('/contact'); ?>
        <a href="<?php echo esc_url($book_link); ?>" class="btn btn-primary" style="font-size: 1.2rem; padding: 15px 30px; background: #D41F3C; border-color: #D41F3C; color: #ffffff; display: inline-block; text-decoration: none;">Book An Event</a>
      </div>
    </section>
  </main>
<?php get_footer(); ?>