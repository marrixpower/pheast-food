<?php
get_header();

$vendor_id = get_the_ID();
$vendor_title = get_the_title();

// 1. HERO SECTION
$hero_bg = get_field('vendor_hero_bg') ?: (get_the_post_thumbnail_url() ?: get_template_directory_uri() . '/assets/kungfu_hero_bg_real.jpg');
$title_accent = get_field('vendor_hero_title_accent');
$title_white = get_field('vendor_hero_title_white');
$stamp_logo = get_field('vendor_stamp_logo');

// If neither accent nor white part is set, split the post title intelligently
if (empty($title_accent) && empty($title_white)) {
    $raw_title = trim(get_the_title());
    $words = preg_split('/\s+/', $raw_title);
    if (count($words) > 1) {
        $last_word = array_pop($words);
        $title_accent = implode('<br>', $words);
        $title_white = $last_word;
    } else {
        $title_accent = '';
        $title_white = $raw_title;
    }
}

$cuisine = get_field('vendor_cuisine') ?: 'BOBA. TEA. DESSERTS.';
$tagline = get_field('vendor_tagline') ?: 'Taiwanese tea culture in Atlanta.';
$btn1_text = get_field('vendor_hero_btn1_text') ?: 'ORDER ONLINE';
$raw_btn1_link = get_field('vendor_hero_btn1_link');
$btn1_link = (!empty($raw_btn1_link) && filter_var($raw_btn1_link, FILTER_VALIDATE_URL)) ? $raw_btn1_link : '';
$btn2_text = get_field('vendor_hero_btn2_text') ?: 'VIEW MENU';
$btn2_link = get_field('vendor_hero_btn2_link') ?: '#vendor-menu';

// 2. ABOUT SECTION
$about_photo = get_field('vendor_about_photo') ?: (get_field('vendor_logo') ?: get_template_directory_uri() . '/assets/real_kungfu__71A6036.jpg');
$about_heading = get_field('vendor_about_heading') ?: "BREWED WITH TRADITION.<br>SERVED WITH STYLE.";
$about_desc = get_field('vendor_about_desc');

// 3. MENU HIGHLIGHTS SECTION
$menu_label = get_field('vendor_menu_label') ?: "POPULAR DRINKS";
$menu_title = get_field('vendor_menu_title');
if (empty($menu_title)) {
    $menu_title = strtoupper(get_the_title()) . " MENU HIGHLIGHTS";
}

$menu_items = get_field('vendor_menu_items');
if (empty($menu_items)) {
    // Default fallback items
    $menu_items = [
        [
            'item_name'        => 'CLASSIC MILK TEA',
            'item_price'       => '$5.50',
            'item_description' => 'Earl Grey black tea with creamy milk and chewy tapioca boba pearls.'
        ],
        [
            'item_name'        => 'TARO MILK TEA',
            'item_price'       => '$6.00',
            'item_description' => 'Sweet, nutty taro root blended into a velvet-smooth purple milk tea.'
        ],
        [
            'item_name'        => 'PASSION FRUIT GREEN TEA',
            'item_price'       => '$5.75',
            'item_description' => 'Jasmine green tea infused with tangy passion fruit and coconut jelly.'
        ],
        [
            'item_name'        => 'MANGO SLUSH',
            'item_price'       => '$6.25',
            'item_description' => 'Real mango purée ice slushie topped with mango popping boba.'
        ],
    ];
}

// 4. OTHER VENDORS SECTION
$other_heading_accent = get_field('vendor_other_heading_accent') ?: "FIVE FLAVORS.";
$other_heading_main = get_field('vendor_other_heading_main') ?: "ONE ROOF.";
$other_vendors = get_posts([
    'post_type'      => 'vendor',
    'posts_per_page' => -1,
    'post__not_in'   => [$vendor_id],
    'orderby'        => 'menu_order',
    'order'          => 'ASC'
]);
?>

  <main style="background-color: #000000; color: #ffffff;">
    <style>
      .vendor-hero-section {
        position: relative;
        height: 100vh;
        min-height: 100vh;
        display: flex;
        align-items: center;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
      }
      .vendor-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.65) 50%, rgba(0,0,0,0.25) 100%);
        z-index: 1;
      }
      .about-kungfu-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: center;
      }
      .vendor-hero-section h1,
      .vendor-hero-subtitle,
      .about-kungfu-grid h2,
      #vendor-menu h2,
      #vendor-menu .menu-sec-label,
      #vendor-menu h3,
      #vendor-menu .item-price-tag,
      .other-vendors-heading {
        font-family: 'Oswald', sans-serif !important;
        text-transform: uppercase !important;
      }
      @media (max-width: 1024px) {
        .vendor-hero-section h1 {
          font-size: clamp(2.2rem, 7vw, 3.8rem) !important;
          max-width: 100% !important;
          word-break: break-word !important;
          overflow-wrap: break-word !important;
        }
        .vendor-stamp-img {
          height: 110px !important;
          left: calc(100% + 10px) !important;
        }
        .vendor-hero-subtitle {
          margin-top: 40px !important;
        }
        .about-kungfu-grid {
          grid-template-columns: 1fr !important;
          gap: 30px !important;
        }
      }
      @media (max-width: 480px) {
        .vendor-hero-section h1 {
          font-size: clamp(1.8rem, 8vw, 2.7rem) !important;
        }
        .vendor-stamp-img {
          height: 85px !important;
        }
      }
    </style>

    <!-- VENDOR HERO SECTION -->
    <section class="vendor-hero-section" style="background-image: url('<?php echo esc_url($hero_bg); ?>');">
      <div class="vendor-hero-overlay"></div>
      <div class="container" style="max-width: 1180px; width: 100%; position: relative; z-index: 2; padding: 40px 20px;">
        <div style="max-width: 580px;">
          <!-- Title with Stamp Logo -->
          <div style="margin-bottom: 20px;">
            <h1 style="margin: 0; line-height: 0.92; font-family: 'Oswald', sans-serif !important; font-weight: 700 !important; font-size: clamp(2.8rem, 6.2vw, 5.2rem); text-transform: uppercase; word-break: break-word; overflow-wrap: break-word; max-width: 100%;">
              <?php if (!empty($title_accent)): ?>
                <span style="display: block; color: #E30638; letter-spacing: 1px; word-break: break-word;"><?php echo wp_kses_post($title_accent); ?></span>
              <?php endif; ?>
              <?php if (!empty($title_white)): ?>
                <span style="display: block; color: #ffffff; letter-spacing: 1px; position: relative; width: fit-content; max-width: 100%; word-break: break-word;">
                  <?php echo esc_html($title_white); ?>
                  <?php if (!empty($stamp_logo)): ?>
                    <img src="<?php echo esc_url($stamp_logo); ?>" alt="<?php the_title_attribute(); ?> Symbol" class="vendor-stamp-img" style="position: absolute; left: calc(100% + 20px); top: 50%; transform: translateY(-50%); height: 185px; width: auto; max-width: none; display: block; filter: drop-shadow(0 6px 16px rgba(0,0,0,0.6)); pointer-events: none; object-fit: contain;">
                  <?php endif; ?>
                </span>
              <?php endif; ?>
            </h1>
          </div>

          <!-- Subtitle & Tagline -->
          <h2 class="vendor-hero-subtitle" style="color: #ffffff; font-family: 'Oswald', sans-serif !important; font-weight: 700 !important; font-size: 1.55rem; letter-spacing: 1px; margin: 70px 0 8px 0; text-transform: uppercase;"><?php echo esc_html($cuisine); ?></h2>
          <p style="color: #ffffff; font-size: 1.08rem; font-family: 'Inter', sans-serif; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 28px;"><?php echo esc_html($tagline); ?></p>

          <!-- CTA Buttons -->
          <div style="display: flex; gap: 14px; flex-wrap: wrap;">
            <?php if (!empty($btn1_link)): ?>
              <a href="<?php echo esc_url($btn1_link); ?>" target="_blank" class="btn btn-primary" style="min-width: 175px; height: 48px; display: inline-flex; align-items: center; justify-content: center; background: #E30638; border: 2px solid #E30638; color: #ffffff; padding: 0 20px; font-family: 'Oswald', sans-serif !important; font-weight: 700 !important; font-size: 1.05rem; letter-spacing: 1px; border-radius: 4px; text-transform: uppercase; text-decoration: none; cursor: pointer; box-sizing: border-box;"><?php echo esc_html($btn1_text); ?></a>
            <?php else: ?>
              <button type="button" class="btn btn-primary" onclick="openOrderModal('<?php echo esc_js($vendor_title); ?>')" style="min-width: 175px; height: 48px; display: inline-flex; align-items: center; justify-content: center; background: #E30638; border: 2px solid #E30638; color: #ffffff; padding: 0 20px; font-family: 'Oswald', sans-serif !important; font-weight: 700 !important; font-size: 1.05rem; letter-spacing: 1px; border-radius: 4px; text-transform: uppercase; cursor: pointer; box-sizing: border-box;"><?php echo esc_html($btn1_text); ?></button>
            <?php endif; ?>
            <a href="<?php echo esc_attr($btn2_link); ?>" class="btn btn-outline" style="min-width: 175px; height: 48px; display: inline-flex; align-items: center; justify-content: center; border: 2px solid #ffffff; color: #ffffff; background: #000000; padding: 0 20px; font-family: 'Oswald', sans-serif !important; font-weight: 700 !important; font-size: 1.05rem; letter-spacing: 1px; border-radius: 4px; text-transform: uppercase; text-decoration: none; box-sizing: border-box;"><?php echo esc_html($btn2_text); ?></a>
          </div>
        </div>
      </div>
      <a href="#about-vendor" class="hero-scroll-indicator" onclick="scrollToNextSection(event)" aria-label="Scroll to next section">
        <i class="fa-solid fa-chevron-down"></i>
      </a>
    </section>

    <!-- ABOUT VENDOR SECTION -->
    <section id="about-vendor" class="section" style="padding: 70px 0; background: #000000;">
      <div class="container" style="max-width: 1180px; padding: 0 20px;">
        <div class="about-kungfu-grid">
          <!-- Left: Framed Storefront Photo -->
          <div>
            <div style="border: 2.5px solid #D41F3C; border-radius: 14px; padding: 10px; background: rgba(0, 0, 0, 0.6); box-shadow: 0 0 25px rgba(212, 31, 60, 0.15);">
              <img src="<?php echo esc_url($about_photo); ?>" alt="<?php the_title_attribute(); ?> at PH'EAST" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; object-position: center; display: block; border-radius: 6px;">
            </div>
          </div>

          <!-- Right: Description Text -->
          <div style="padding-top: 10px;">
            <h2 style="color: #ffffff; font-family: 'Oswald', sans-serif !important; font-weight: 600 !important; font-size: 2.8rem; line-height: 1.05; margin: 0 0 25px 0; letter-spacing: 1px; text-transform: uppercase;">
              <?php echo wp_kses_post($about_heading); ?>
            </h2>
            <div style="color: rgba(255, 255, 255, 0.85); font-size: 0.95rem; line-height: 1.7; font-family: 'Inter', sans-serif;">
              <?php 
              if ($about_desc) {
                  echo wp_kses_post(wpautop($about_desc));
              } else {
                  the_content();
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- POPULAR DRINKS & MENU HIGHLIGHTS -->
    <section id="vendor-menu" class="section" style="padding: 80px 0; background: #000000; color: #ffffff;">
      <div class="container" style="max-width: 1180px; padding: 0 20px;">
        <div style="text-align: center; margin-bottom: 50px;">
          <span class="menu-sec-label" style="color: #D41F3C; font-family: 'Oswald', sans-serif !important; font-weight: 600 !important; font-size: 1.3rem; letter-spacing: 2px; display: block; margin-bottom: 6px;"><?php echo esc_html($menu_label); ?></span>
          <h2 style="color: #ffffff; font-family: 'Oswald', sans-serif !important; font-weight: 600 !important; font-size: 3rem; margin: 0; text-transform: uppercase; letter-spacing: 1px; line-height: 1.1;"><?php echo esc_html($menu_title); ?></h2>
        </div>

        <?php if (!empty($menu_items)): ?>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 25px;">
            <?php foreach ($menu_items as $item): 
                $item_name = !empty($item['item_name']) ? $item['item_name'] : '';
                $item_price = !empty($item['item_price']) ? $item['item_price'] : '';
                $item_desc = !empty($item['item_description']) ? $item['item_description'] : '';
                if (empty($item_name) && empty($item_price) && empty($item_desc)) continue;
            ?>
              <div style="background: #000000; border: 2px solid #D41F3C; border-radius: 10px; padding: 25px; transition: transform 0.3s ease; display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 10px;">
                  <h3 style="color: #ffffff; font-family: 'Oswald', sans-serif !important; font-weight: 600 !important; font-size: 1.8rem; margin: 0; letter-spacing: 0.5px; text-transform: uppercase;"><?php echo esc_html($item_name); ?></h3>
                  <?php if (!empty($item_price)): ?>
                    <span class="item-price-tag" style="color: #D41F3C; font-family: 'Oswald', sans-serif !important; font-weight: 600 !important; font-size: 1.6rem; margin-left: 10px; flex-shrink: 0;"><?php echo esc_html($item_price); ?></span>
                  <?php endif; ?>
                </div>
                <?php if (!empty($item_desc)): ?>
                  <p style="color: rgba(255,255,255,0.7); font-size: 1.02rem; font-weight: 400; line-height: 1.5; margin: 0; flex-grow: 1;"><?php echo esc_html($item_desc); ?></p>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- OTHER VENDORS CAROUSEL -->
    <?php if (!empty($other_vendors)): ?>
    <section class="section bg-secondary-section" style="padding: 80px 0; background: #ffffff;">
      <div class="container" style="max-width: 1180px; padding: 0 20px;">
        <div class="section-header" style="text-align: left; margin-bottom: 40px; padding-left: 10px;">
          <h2 class="other-vendors-heading" style="color: #000000; font-family: 'Oswald', sans-serif !important; font-weight: 600 !important; font-size: 3.2rem; margin: 0; text-transform: uppercase;"><span style="color: #D41F3C;"><?php echo esc_html($other_heading_accent); ?></span> <?php echo esc_html($other_heading_main); ?></h2>
        </div>

        <div class="carousel-track-container">
          <!-- Carousel Controls -->
          <div class="carousel-controls">
            <button class="carousel-btn prev" onclick="slideCarousel(-1)" aria-label="Previous Slide">
              <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="45" fill="#000000"/>
                <path d="M68 52 C55 51 45 49 32 49" stroke="white" stroke-width="5" stroke-linecap="round"/>
                <path d="M48 37 C42 41 37 45 30 49 C36 53 41 57 47 62" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
            <button class="carousel-btn next" onclick="slideCarousel(1)" aria-label="Next Slide">
              <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="45" fill="#000000"/>
                <path d="M32 52 C45 51 55 49 68 49" stroke="white" stroke-width="5" stroke-linecap="round"/>
                <path d="M52 37 C58 41 63 45 70 49 C64 53 59 57 53 62" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </div>

          <!-- Track with Other Vendors -->
          <div class="vendors-grid carousel-track">
            <?php foreach ($other_vendors as $ov): 
                $ov_logo = get_field('vendor_logo', $ov->ID) ?: get_the_post_thumbnail_url($ov->ID);
                $ov_link = get_permalink($ov->ID);
            ?>
              <div class="vendor-card" style="border-color: #000000;">
                <a href="<?php echo esc_url($ov_link); ?>" style="display: block; text-decoration: none; cursor: pointer;">
                  <img src="<?php echo esc_url($ov_logo); ?>" alt="<?php echo esc_attr($ov->post_title); ?>" style="width: 100%; height: auto; display: block;">
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </section>
    <?php endif; ?>
  </main>

<?php get_footer(); ?>
