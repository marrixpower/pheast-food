<?php
/* Template Name: About Page */
get_header();

$about_page = get_page_by_path('about');
$page_id = $about_page ? $about_page->ID : (get_the_ID() ?: 6);

// Hero / First Screen Section
$hero_subtitle = get_field('about_hero_subtitle', $page_id) ?: (get_field('about_top_subtitle', $page_id) ?: "ABOUT PH'EAST");
$hero_title = get_field('about_hero_title', $page_id) ?: (get_field('about_top_heading', $page_id) ?: "ASIAN STREET FOOD.<br>LOCAL SOUL.");
$hero_desc = get_field('about_hero_desc', $page_id) ?: (get_field('about_top_text', $page_id) ?: "PH'EAST is Atlanta's destination for bold Asian street food, unforgettable vibes, and a community that comes together over great food and good times.");
$hero_image = get_field('about_hero_image', $page_id) ?: (get_field('about_top_image', $page_id) ?: get_template_directory_uri() . '/assets/foodhall-interior.jpg');
if (is_array($hero_image)) { $hero_image = $hero_image['url']; }

// Optional Full-Width Hero Banner
$hero_banner_bg = get_field('about_hero_banner_bg', $page_id);
if (is_array($hero_banner_bg)) { $hero_banner_bg = $hero_banner_bg['url']; }
$hero_banner_title = get_field('about_hero_banner_title', $page_id) ?: "ABOUT PH'EAST";
$hero_banner_subtitle = get_field('about_hero_banner_subtitle', $page_id) ?: "ASIAN STREET FOOD. LOCAL SOUL.";

// Story Section (Second Row)
$story_image = get_field('about_image', $page_id) ?: (get_field('about_story_image', $page_id) ?: get_template_directory_uri() . '/assets/venue-exterior.jpg');
if (is_array($story_image)) { $story_image = $story_image['url']; }
$story_subtitle = get_field('about_subtitle', $page_id) ?: "OUR STORY";
$story_heading = get_field('about_heading', $page_id) ?: "FROM THE STREETS<br>TO THE BATTERY.";
$story_text1 = get_field('about_text1', $page_id) ?: "PH'EAST was born from a love of Asian street food and the energy of night markets. We set out to create a space in Atlanta where people could experience that same energy—bold flavors, late nights, and a whole lot of heart.";
$story_text2 = get_field('about_text2', $page_id) ?: "Today, we're proud to support local vendors, showcase amazing talent, and welcome thousands of guests who make PH'EAST what it is.";
?>

  <main>
    <?php if (!empty($hero_banner_bg)): ?>
      <!-- OPTIONAL FULL-WIDTH HERO BANNER -->
      <section class="hero-section">
        <div class="hero-backdrop">
          <img src="<?php echo esc_url($hero_banner_bg); ?>" alt="<?php echo esc_attr($hero_banner_title); ?>">
          <div class="overlay"></div>
        </div>
        <div class="hero-content">
          <h1 class="hero-title"><?php echo esc_html($hero_banner_title); ?></h1>
          <p class="hero-subtitle" style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #D41F3C;"><?php echo esc_html($hero_banner_subtitle); ?></p>
        </div>
        <a href="#next-section" class="hero-scroll-indicator" onclick="scrollToNextSection(event)" aria-label="Scroll to next section">
          <i class="fa-solid fa-chevron-down"></i>
        </a>
      </section>
    <?php endif; ?>

    <section id="next-section" class="about-grid-section container" style="padding: 80px 20px; display: flex; flex-direction: column; gap: 60px;">
      
      <!-- Row 1: Hero / Intro -->
      <div style="display: flex; gap: 40px; align-items: center; flex-wrap: wrap;">
        <!-- Left: Text -->
        <div style="flex: 1; min-width: 300px;">
          <h3 class="reveal-element reveal-right" style="color: var(--accent-primary); font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 1.5rem; letter-spacing: 2px; margin-bottom: 5px; transition-delay: 0.1s;"><?php echo esc_html($hero_subtitle); ?></h3>
          <h2 class="reveal-element reveal-left" style="font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 3.5rem; line-height: 1.1; margin-bottom: 20px; text-transform: uppercase; transition-delay: 0.3s;"><?php echo wp_kses_post($hero_title); ?></h2>
          <p class="reveal-element reveal-right" style="font-size: 1.1rem; line-height: 1.8; color: var(--text-primary); transition-delay: 0.5s;">
            <?php echo wp_kses_post($hero_desc); ?>
          </p>
        </div>
        <!-- Right: Image -->
        <div style="flex: 1; min-width: 300px;">
          <div class="reveal-element" style="border: 2px solid var(--accent-primary); padding: 10px; border-radius: 8px; box-shadow: 0 0 15px var(--accent-primary); transition-delay: 0.7s;">
            <img src="<?php echo esc_url($hero_image); ?>" alt="PH'EAST Interior" style="width: 100%; border-radius: 4px; display: block;">
          </div>
        </div>
      </div>

      <!-- Row 2: Our Story -->
      <div style="display: flex; gap: 40px; align-items: center; flex-wrap: wrap-reverse;">
        <!-- Left: Image -->
        <div style="flex: 1; min-width: 300px;">
          <div class="reveal-element" style="border: 2px solid var(--accent-primary); padding: 10px; border-radius: 8px; box-shadow: 0 0 15px var(--accent-primary);">
            <img src="<?php echo esc_url($story_image); ?>" alt="PH'EAST Exterior" style="width: 100%; border-radius: 4px; display: block;">
          </div>
        </div>
        <!-- Right: Text -->
        <div style="flex: 1; min-width: 300px;">
          <h3 class="reveal-element reveal-right" style="color: var(--accent-primary); font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 1.5rem; letter-spacing: 2px; margin-bottom: 5px;"><?php echo esc_html($story_subtitle); ?></h3>
          <h2 class="reveal-element reveal-left" style="font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 3.5rem; line-height: 1.1; margin-bottom: 20px; text-transform: uppercase;"><?php echo wp_kses_post($story_heading); ?></h2>
          <p class="reveal-element reveal-right" style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 15px; color: var(--text-primary);">
            <?php echo wp_kses_post($story_text1); ?>
          </p>
          <p class="reveal-element reveal-right" style="font-size: 1.1rem; line-height: 1.8; color: var(--text-primary);">
            <?php echo wp_kses_post($story_text2); ?>
          </p>
        </div>
      </div>

    </section>

    <section class="stats-row" style="background: var(--accent-primary); color: white; padding: 40px 20px;">
      <div class="container" style="display: flex; justify-content: space-around; flex-wrap: wrap; text-align: center; gap: 20px;">
        <div>
          <h3 style="font-size: 2.5rem; margin-bottom: 10px;">6</h3>
          <p>Unique Vendors</p>
        </div>
        <div>
          <h3 style="font-size: 2.5rem; margin-bottom: 10px;">200+</h3>
          <p>Menu Items</p>
        </div>
        <div>
          <h3 style="font-size: 2.5rem; margin-bottom: 10px;">500K+</h3>
          <p>Guests Served</p>
        </div>
        <div>
          <h3 style="font-size: 2.5rem; margin-bottom: 10px;">4.8★</h3>
          <p>Average Rating</p>
        </div>
      </div>
    </section>

    <section class="our-values container" style="padding: 60px 20px; text-align: center; background-color: #000000;">
      <p style="color: var(--primary-color); text-transform: uppercase; letter-spacing: 2px; font-weight: bold; font-size: 0.9rem; margin-bottom: 5px;">What We Stand For</p>
      <h2 style="font-size: 3rem; margin-bottom: 50px; font-family: var(--font-display); letter-spacing: 1px;" class="reveal-element reveal-left reveal-active">Our Values</h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 30px;">
        <div class="value-card" style="padding: 20px; border: 2.5px solid #D41F3C; border-radius: 12px;">
          <div style="margin-bottom: 20px; height: 60px; display: flex; align-items: center; justify-content: center;">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/Ph_east Website Icons-02.png" alt="Authenticity" style="height: 50px; width: auto;" />
          </div>
          <h3 style="margin-bottom: 15px; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;" class="reveal-element reveal-right reveal-active">Authenticity</h3>
          <p class="reveal-element reveal-right reveal-active" style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6;">Honoring the roots of Asian cuisine through authentic ingredients and recipes.</p>
        </div>
        <div class="value-card" style="padding: 20px; border: 2.5px solid #D41F3C; border-radius: 12px;">
          <div style="margin-bottom: 20px; height: 60px; display: flex; align-items: center; justify-content: center;">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/Ph_east Website Icons-03.png" alt="Community" style="height: 50px; width: auto;" />
          </div>
          <h3 style="margin-bottom: 15px; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;" class="reveal-element reveal-right reveal-active">Community</h3>
          <p class="reveal-element reveal-right reveal-active" style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6;">Building connections and creating a space where everyone feels welcome.</p>
        </div>
        <div class="value-card" style="padding: 20px; border: 2.5px solid #D41F3C; border-radius: 12px;">
          <div style="margin-bottom: 20px; height: 60px; display: flex; align-items: center; justify-content: center;">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/Ph_east Website Icons-04.png" alt="Quality" style="height: 50px; width: auto;" />
          </div>
          <h3 style="margin-bottom: 15px; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;" class="reveal-element reveal-right reveal-active">Quality</h3>
          <p class="reveal-element reveal-right reveal-active" style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6;">We partner with passionate vendors who care about flavor and craftsmanship.</p>
        </div>
        <div class="value-card" style="padding: 20px; border: 2.5px solid #D41F3C; border-radius: 12px;">
          <div style="margin-bottom: 20px; height: 60px; display: flex; align-items: center; justify-content: center;">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/Ph_east Website Icons-05.png" alt="Experience" style="height: 50px; width: auto;" />
          </div>
          <h3 style="margin-bottom: 15px; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;" class="reveal-element reveal-right reveal-active">Experience</h3>
          <p class="reveal-element reveal-right reveal-active" style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6;">It's more than a meal, it's music, sports, and memories that last.</p>
        </div>
      </div>
    </section>

    <section class="testimonials" style="background: #000000; padding: 60px 20px;">
      <div class="container">
        <h2 style="font-size: 2.5rem; text-align: center; margin-bottom: 40px; color: #ffffff;">What People Say</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
          <div class="testimonial-card" style="background: #000000; border: 2.5px solid #D41F3C; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.4);">
            <p style="font-style: italic; margin-bottom: 20px; color: #ffffff;">"Best ramen I've had outside of Japan. The broth is INSANE."</p>
            <p style="font-weight: bold; color: #ffffff;">— Sarah M. ⭐⭐⭐⭐⭐</p>
          </div>
          <div class="testimonial-card" style="background: #000000; border: 2.5px solid #D41F3C; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.4);">
            <p style="font-style: italic; margin-bottom: 20px; color: #ffffff;">"We come here every Braves game day. The energy is incredible."</p>
            <p style="font-weight: bold; color: #ffffff;">— Marcus T. ⭐⭐⭐⭐⭐</p>
          </div>
          <div class="testimonial-card" style="background: #000000; border: 2.5px solid #D41F3C; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.4);">
            <p style="font-style: italic; margin-bottom: 20px; color: #ffffff;">"The boba from Kung Fu Tea is addictive. My kids love this place!"</p>
            <p style="font-weight: bold; color: #ffffff;">— Lisa K. ⭐⭐⭐⭐⭐</p>
          </div>
        </div>
      </div>
    </section>
  </main>

  
<?php
get_footer();
?>