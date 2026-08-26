<?php
/* Template Name: Gallery */
get_header(); ?>

  <main>
    <!-- HERO SECTION -->
    <section class="hero-section">
      <div class="hero-backdrop">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/food-flatlay.jpg" alt="Food Flatlay">
        <div class="overlay"></div>
      </div>
      <div class="hero-content">
        <h1 class="hero-title">GALLERY</h1>
        <p class="hero-subtitle" style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #ffffff;">MOMENTS @ PH'EAST</p>
        <p class="hero-subtitle" style="font-size: 1.08rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; line-height: 1.45; max-width: 680px; margin: 0 auto; color: #ffffff; animation-delay: 0.7s;">A look at the food, the people, and the good times.</p>
      </div>
      <a href="#next-section" class="hero-scroll-indicator" onclick="scrollToNextSection(event)" aria-label="Scroll to next section">
        <i class="fa-solid fa-chevron-down"></i>
      </a>
    </section>

    <!-- GALLERY SECTION -->
    <section id="next-section" class="gallery-section container" style="padding: 60px 20px;">
      <div class="gallery-filters">
        <button class="filter-pill active" onclick="filterGallery('all')">All</button>
        <?php 
        $cats = get_terms([
            'taxonomy'   => 'gallery_category',
            'hide_empty' => false
        ]);
        if (!empty($cats) && !is_wp_error($cats)) {
            foreach ($cats as $cat) {
                echo '<button class="filter-pill" onclick="filterGallery(\'' . esc_attr($cat->slug) . '\')">' . esc_html($cat->name) . '</button>';
            }
        } else {
            // Default filter pills fallback
            echo '<button class="filter-pill" onclick="filterGallery(\'food\')">Food</button>';
            echo '<button class="filter-pill" onclick="filterGallery(\'events\')">Events</button>';
            echo '<button class="filter-pill" onclick="filterGallery(\'people\')">People</button>';
            echo '<button class="filter-pill" onclick="filterGallery(\'drinks\')">Drinks</button>';
        }
        ?>
      </div>

      <div style="width: 100%; height: 1px; background: linear-gradient(90deg, transparent, rgba(212, 31, 60, 0.6), transparent); margin: 35px 0 40px 0;"></div>

      <div class="gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px; grid-auto-rows: 200px; grid-auto-flow: dense;">
        <?php 
        $gallery_query = new WP_Query([
            'post_type'      => 'gallery_item',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC'
        ]);
        
        if ($gallery_query->have_posts()): 
            while ($gallery_query->have_posts()): $gallery_query->the_post(); 
                
                $terms = get_the_terms(get_the_ID(), 'gallery_category');
                $cat_slug = 'all';
                if ($terms && !is_wp_error($terms)) {
                    $cat_slug = $terms[0]->slug;
                }
                
                $img_id = get_post_meta(get_the_ID(), 'gallery_photo', true);
                if (!$img_id) {
                    $img_id = get_post_thumbnail_id(get_the_ID());
                }
                
                $img_url = 'https://via.placeholder.com/400x400';
                $style = '';
                $manual_tall = get_field('is_tall');
                
                if ($manual_tall) {
                    $style = ' style="grid-row-end: span 2;"';
                }
                
                if ($img_id) {
                    $img_data = wp_get_attachment_image_src($img_id, 'full');
                    if ($img_data) {
                        $img_url = $img_data[0];
                        $w = $img_data[1];
                        $h = $img_data[2];
                        
                        if (!$manual_tall && $h >= $w * 1.15) {
                            $style = ' style="grid-row-end: span 2;"';
                        } elseif (!$manual_tall && $w >= $h * 1.35) {
                            $style = ' style="grid-column-end: span 2;"';
                        }
                    }
                } else {
                    $img_url = get_field('gallery_photo');
                    if (!$img_url) {
                        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                    }
                    if (!$img_url) {
                        $img_url = get_post_meta(get_the_ID(), 'demo_image_url', true);
                    }
                }
        ?>
        <div class="gallery-item reveal-element reveal-scale" data-category="<?php echo esc_attr($cat_slug); ?>"<?php echo $style; ?>>
          <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
        </div>
        <?php endwhile; wp_reset_postdata(); endif; ?>
      </div>

      <div style="text-align: center; margin-top: 40px;">
        <button class="btn btn-outline" style="padding: 10px 30px; color: #ffffff; border: 2px solid #D41F3C; background: transparent;">Load More</button>
      </div>
    </section>
  </main>
  
<?php get_footer(); ?>
