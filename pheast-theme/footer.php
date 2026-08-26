<footer class="main-footer">
    <div class="container" style="max-width: 1180px;">
      <div class="footer-grid-layout">
        <!-- Col 1: Logo & Newsletter -->
        <div class="footer-col">
          <?php 
          $footer_logo = get_field('footer_logo', 'option') ?: get_template_directory_uri() . '/assets/PHEAST-logo-white-removebg-preview.png';
          $news_heading = get_field('footer_news_heading', 'option') ?: 'STAY UPDATED';
          $news_subtext = get_field('footer_news_subtext', 'option') ?: 'Join our mailing list for the latest news!';
          $news_placeholder = get_field('footer_news_placeholder', 'option') ?: 'Enter your email';
          ?>
          <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php bloginfo('name'); ?>" class="footer-brand-logo">
          <h4 class="footer-heading"><?php echo esc_html($news_heading); ?></h4>
          <p class="footer-subtext"><?php echo esc_html($news_subtext); ?></p>
          <form class="footer-form" onsubmit="handleNewsletterSubmit(event)">
            <div class="footer-input-group">
              <input type="email" id="newsletter-email" placeholder="<?php echo esc_attr($news_placeholder); ?>" class="footer-input-box" required>
              <button type="submit" class="footer-submit-btn" aria-label="Subscribe">
                <i class="fa-solid fa-arrow-right"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Col 2: The Battery Atlanta Address -->
        <div class="footer-col">
          <div class="footer-header-with-icon">
            <svg class="footer-red-icon" width="24" height="28" viewBox="0 0 24 28" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 0C5.37 0 0 5.37 0 12C0 21 12 28 12 28C12 28 24 21 24 12C24 5.37 18.63 0 12 0ZM12 16C9.79 16 8 14.21 8 12C8 9.79 9.79 8 12 8C14.21 8 16 9.79 16 12C16 14.21 14.21 16 12 16Z" fill="#D41F3C"/>
            </svg>
            <div>
              <h4 class="footer-heading"><?php echo esc_html(get_field('footer_addr_heading', 'option') ?: 'THE BATTERY ATLANTA'); ?></h4>
              <p class="footer-info-text">
                <?php 
                $addr_text = nl2br(esc_html(get_field('global_address', 'option') ?: "925 Battery Ave SE Ste. 1100,\nAtlanta, GA 30339"));
                $maps_url = get_field('global_maps_link', 'option');
                if (!empty($maps_url)): ?>
                  <a href="<?php echo esc_url($maps_url); ?>" target="_blank" style="color: inherit; text-decoration: none;"><?php echo $addr_text; ?></a>
                <?php else: ?>
                  <?php echo $addr_text; ?>
                <?php endif; ?>
              </p>
            </div>
          </div>
        </div>

        <!-- Col 3: Hours -->
        <div class="footer-col">
          <div class="footer-header-with-icon">
            <svg class="footer-red-icon" width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="13" cy="13" r="11.5" stroke="#D41F3C" stroke-width="2.5"/>
              <path d="M13 6V13L18 16" stroke="#D41F3C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div>
              <h4 class="footer-heading"><?php echo esc_html(get_field('footer_hours_heading', 'option') ?: 'HOURS'); ?></h4>
              <p class="footer-info-text">
                <?php echo nl2br(esc_html(get_field('global_hours', 'option') ?: "Sun - Thurs: 11 AM - Midnight\nFri & Sat: 11 AM - 1 AM")); ?>
              </p>
            </div>
          </div>
        </div>

        <!-- Col 4: Follow PH'EAST -->
        <div class="footer-col">
          <?php 
          $social_heading = get_field('footer_social_heading', 'option') ?: 'FOLLOW <br class="footer-br">PH\'EAST';
          $ig = get_field('global_ig', 'option') ?: 'https://www.instagram.com/pheastfoodhall/';
          $fb = get_field('global_fb', 'option') ?: 'https://www.facebook.com/pheastfoodhall/';
          $yelp = get_field('global_yelp', 'option') ?: 'https://www.yelp.com/biz/pheast-food-hall-atlanta';
          $tiktok = get_field('global_tiktok', 'option') ?: '#';
          ?>
          <h4 class="footer-heading"><?php echo wp_kses_post($social_heading); ?></h4>
          <div class="footer-social-row">
            <?php if(!empty($ig)): ?><a href="<?php echo esc_url($ig); ?>" target="_blank" class="social-circle-btn" aria-label="Instagram"><i class="fab fa-instagram"></i></a><?php endif; ?>
            <?php if(!empty($fb)): ?><a href="<?php echo esc_url($fb); ?>" target="_blank" class="social-circle-btn" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
            <?php if(!empty($yelp)): ?><a href="<?php echo esc_url($yelp); ?>" target="_blank" class="social-circle-btn" aria-label="Yelp"><i class="fab fa-yelp"></i></a><?php endif; ?>
            <?php if(!empty($tiktok)): ?><a href="<?php echo esc_url($tiktok); ?>" target="_blank" class="social-circle-btn" aria-label="TikTok"><i class="fab fa-tiktok"></i></a><?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- MODAL: ORDER ONLINE (DYNAMIC VENDORS) -->
  <div id="order-modal" class="modal-overlay">
    <div class="modal-card">
      <h2>Order Online</h2>
      <p style="margin-top: 10px;">Choose your vendor:</p>
      <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 20px;">
        <?php
        $modal_vendors = new WP_Query(array(
            'post_type'      => 'vendor',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC'
        ));
        
        $rendered_any = false;
        
        if ($modal_vendors->have_posts()):
            while ($modal_vendors->have_posts()): $modal_vendors->the_post();
                $show = get_field('vendor_order_modal_show');
                // If show is null or true (default to true)
                if ($show === null || $show === '' || $show === true || $show == 1):
                    $rendered_any = true;
                    $btn_label = get_field('vendor_order_modal_text');
                    if (empty($btn_label)) {
                        $btn_label = get_the_title();
                    }
                    $order_link = get_field('vendor_order_modal_link') ?: get_permalink();
                    $vendor_name = esc_js(get_the_title());
                    $link_attr = "'" . esc_js(esc_url($order_link)) . "'";
        ?>
          <button class="btn btn-outline reveal-element reveal-scale" onclick="selectOrderVendor('<?php echo $vendor_name; ?>', <?php echo $link_attr; ?>)"><?php echo esc_html($btn_label); ?></button>
        <?php
                endif;
            endwhile;
            wp_reset_postdata();
        endif;

        if (!$rendered_any):
        ?>
          <button class="btn btn-outline" onclick="selectOrderVendor('Kung Fu Tea')">🧋 Kung Fu Tea</button>
          <button class="btn btn-outline" onclick="selectOrderVendor('Lifting Noodles')">🍜 Lifting Noodles</button>
          <button class="btn btn-outline" onclick="selectOrderVendor('Poke Burri')">🍣 Poke Burri</button>
          <button class="btn btn-outline" onclick="selectOrderVendor('26 Thai')">🍲 26 Thai Kitchen</button>
          <button class="btn btn-outline" onclick="selectOrderVendor('Fan T Asia')">🥟 Fan T'Asia</button>
        <?php endif; ?>
        
        <button class="btn btn-ghost reveal-element reveal-scale" onclick="closeOrderModal()" style="margin-top: 10px;">Cancel</button>
      </div>
    </div>
  </div>

  <div id="toast-notification" class="toast hidden">
    <span id="toast-text"></span>
  </div>

<?php wp_footer(); ?>
</body>
</html>
