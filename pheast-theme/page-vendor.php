<?php
/* Template Name: Vendor Page */
get_header();

$hero_bg = get_field('vendor_page_hero_bg') ?: get_template_directory_uri() . '/assets/foodhall-interior.jpg';
$toptext = get_field('vendor_page_toptext') ?: 'EXPLORE THE FOOD HALL';
$title = get_field('vendor_page_hero_title') ?: 'SIX FLAVORS. ONE ROOF.';
$subtitle = get_field('vendor_page_hero_subtitle') ?: 'From craft beer and boba tea to sushi burritos and authentic ramen, discover our curated lineup of Asian street food vendors at The Battery Atlanta.';
$dir_title = get_field('vendor_page_dir_title') ?: 'MEET OUR VENDORS';
$dir_sub = get_field('vendor_page_dir_sub') ?: 'Click any vendor card to learn more & view featured menus.';
?>

  <!-- HERO SECTION -->
  <section class="hero-section">
    <div class="hero-backdrop">
      <img src="<?php echo esc_url($hero_bg); ?>" alt="PH'EAST Food Hall Interior">
      <div class="overlay"></div>
    </div>
    <div class="hero-content">
      <p class="hero-subtitle text-accent reveal-element reveal-fade" style="margin-bottom: 5px;"><?php echo esc_html($toptext); ?></p>
      <h1 class="hero-title reveal-element reveal-slide-up" style="animation-delay: 0.2s;"><?php echo esc_html($title); ?></h1>
      <p class="hero-subtitle reveal-element reveal-fade" style="animation-delay: 0.4s; font-size: 1.15rem; max-width: 650px; line-height: 1.6; margin: 0 auto;"><?php echo esc_html($subtitle); ?></p>
    </div>
    <a href="#next-section" class="hero-scroll-indicator" onclick="scrollToNextSection(event)" aria-label="Scroll to next section">
      <i class="fa-solid fa-chevron-down"></i>
    </a>
  </section>

  <!-- VENDORS DIRECTORY GRID -->
  <section id="next-section" class="section" style="padding: 80px 0; background-color: var(--bg-dark);">
    <div class="container" style="max-width: 1180px;">
      <div class="section-header reveal-element reveal-fade" style="text-align: center; margin-bottom: 50px;">
        <h2 style="font-size: 2.8rem; text-transform: uppercase; margin-bottom: 10px;"><?php echo esc_html($dir_title); ?></h2>
        <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem; max-width: 600px; margin: 0 auto;"><?php echo esc_html($dir_sub); ?></p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">
        <?php
        $vendors_query = new WP_Query(array(
            'post_type' => 'vendor',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        ));
        while( $vendors_query->have_posts() ): $vendors_query->the_post();
            $show_card = get_field('vendor_card_show');
            // If show_card is null or true (default to true)
            if ($show_card === null || $show_card === '' || $show_card === true || $show_card == 1):
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
                $card_title = get_field('vendor_card_title') ?: get_the_title();
                $btn_text = get_field('vendor_btn_text') ?: 'ORDER ONLINE';
                $vendor_permalink = get_permalink();
        ?>
        <div class="vendor-card reveal-element reveal-scale" style="background: #000000; border: 1px solid rgba(243, 15, 61, 0.3); border-radius: 12px; overflow: hidden; transition: transform 0.3s ease; display: flex; flex-direction: column; height: 100%;">
          <a href="<?php echo esc_url($vendor_permalink); ?>" style="display: block; text-decoration: none;">
            <div style="background: #000; padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
              <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="max-width: 220px; width: 100%; height: auto; display: inline-block;">
            </div>
          </a>

          <div style="padding: 25px; display: flex; flex-direction: column; flex-grow: 1;">
            <span style="color: #D41F3C; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px;"><?php echo esc_html($cuisine); ?></span>
            <a href="<?php echo esc_url($vendor_permalink); ?>" style="text-decoration: none; color: inherit;">
              <h3 style="color: #ffffff; font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 1.8rem; margin: 0 0 10px 0;"><?php echo esc_html($card_title); ?></h3>
            </a>
            <p style="color: rgba(255,255,255,0.75); font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px; flex-grow: 1;"><?php echo esc_html($description); ?></p>
            
            <a href="<?php echo esc_url($vendor_permalink); ?>" class="btn btn-outline" style="width: 100%; border: 1px solid #D41F3C; color: #ffffff; display: block; text-align: center; text-decoration: none; margin-top: auto;"><?php echo esc_html($btn_text ?: 'ORDER ONLINE'); ?></a>
          </div>
        </div>
        <?php endif; endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>

<?php get_footer(); ?>
