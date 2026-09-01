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

  <!-- MODAL: ORDER ONLINE (VENDOR LIST FOR HEADER + CONTACT FORM FOR SINGLE VENDOR) -->
  <div id="order-modal" class="modal-overlay" onclick="if(event.target===this) closeOrderModal()">
    <div class="modal-card" style="max-width: 520px; width: 100%; border: 2.5px solid #E30638; box-shadow: 0 0 40px rgba(227, 6, 56, 0.4); background: #0c0c0c; padding: 35px 28px; position: relative; border-radius: 12px; box-sizing: border-box;">
      <button class="modal-close" onclick="closeOrderModal()" aria-label="Close Modal" style="position: absolute; top: 15px; right: 18px; font-size: 1.8rem; color: #fff; background: none; border: none; cursor: pointer; line-height: 1; transition: color 0.2s ease;">&times;</button>
      
      <!-- 1. VENDOR BUTTONS LIST (SHOWN FOR HEADER ORDER ONLINE BUTTON) -->
      <div id="order-modal-vendor-list">
        <h2 style="font-family: 'Oswald', sans-serif !important; font-size: 2.2rem; margin: 0 0 6px 0; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; line-height: 1.1;">ORDER ONLINE</h2>
        <p style="color: rgba(255,255,255,0.75); font-size: 0.95rem; margin-bottom: 22px; line-height: 1.4;">Choose your vendor to place an order or view options:</p>
        
        <div style="display: flex; flex-direction: column; gap: 10px;">
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
                  if ($show === null || $show === '' || $show === true || $show == 1):
                      $rendered_any = true;
                      $btn_label = get_field('vendor_order_modal_text') ?: get_the_title();
                      $order_link = get_field('vendor_order_modal_link');
                      $vendor_title = get_the_title();
                      
                      if (!empty($order_link) && filter_var($order_link, FILTER_VALIDATE_URL)):
          ?>
            <a href="<?php echo esc_url($order_link); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline vendor-modal-btn" style="width: 100%; height: 48px; display: inline-flex; align-items: center; justify-content: center; border: 2px solid #ffffff; color: #ffffff; font-family: 'Oswald', sans-serif !important; font-size: 1.05rem; font-weight: 700 !important; letter-spacing: 1px; text-transform: uppercase; text-decoration: none; border-radius: 4px; box-sizing: border-box; transition: all 0.3s ease;"><?php echo esc_html($btn_label); ?></a>
          <?php       else: ?>
            <button type="button" class="btn btn-outline vendor-modal-btn" onclick="openVendorInquiryFromModal('<?php echo esc_js($vendor_title); ?>')" style="width: 100%; height: 48px; display: inline-flex; align-items: center; justify-content: center; border: 2px solid #ffffff; color: #ffffff; font-family: 'Oswald', sans-serif !important; font-size: 1.05rem; font-weight: 700 !important; letter-spacing: 1px; text-transform: uppercase; text-decoration: none; border-radius: 4px; box-sizing: border-box; cursor: pointer; transition: all 0.3s ease;"><?php echo esc_html($btn_label); ?></button>
          <?php
                      endif;
                  endif;
              endwhile;
              wp_reset_postdata();
          endif;

          if (!$rendered_any):
          ?>
            <button type="button" class="btn btn-outline vendor-modal-btn" onclick="openVendorInquiryFromModal('Kung Fu Tea')" style="width: 100%; height: 48px; border: 2px solid #fff; color: #fff; font-family: 'Oswald', sans-serif; font-size: 1.05rem; font-weight: 700; text-transform: uppercase; border-radius: 4px;">🧋 KUNG FU TEA</button>
            <button type="button" class="btn btn-outline vendor-modal-btn" onclick="openVendorInquiryFromModal('Poke Burri')" style="width: 100%; height: 48px; border: 2px solid #fff; color: #fff; font-family: 'Oswald', sans-serif; font-size: 1.05rem; font-weight: 700; text-transform: uppercase; border-radius: 4px;">🍣 POKE BURRI</button>
            <button type="button" class="btn btn-outline vendor-modal-btn" onclick="openVendorInquiryFromModal('Lifting Noodles')" style="width: 100%; height: 48px; border: 2px solid #fff; color: #fff; font-family: 'Oswald', sans-serif; font-size: 1.05rem; font-weight: 700; text-transform: uppercase; border-radius: 4px;">🍜 LIFTING NOODLES</button>
            <button type="button" class="btn btn-outline vendor-modal-btn" onclick="openVendorInquiryFromModal('26 Thai Kitchen')" style="width: 100%; height: 48px; border: 2px solid #fff; color: #fff; font-family: 'Oswald', sans-serif; font-size: 1.05rem; font-weight: 700; text-transform: uppercase; border-radius: 4px;">🍲 26 THAI KITCHEN</button>
            <button type="button" class="btn btn-outline vendor-modal-btn" onclick="openVendorInquiryFromModal('Fan T\'Asia')" style="width: 100%; height: 48px; border: 2px solid #fff; color: #fff; font-family: 'Oswald', sans-serif; font-size: 1.05rem; font-weight: 700; text-transform: uppercase; border-radius: 4px;">🥟 FAN T'ASIA</button>
          <?php endif; ?>
          
          <button type="button" class="btn btn-ghost" onclick="closeOrderModal()" style="margin-top: 6px; width: 100%; height: 44px; color: rgba(255,255,255,0.7); background: transparent; border: 1px solid #333; font-family: 'Oswald', sans-serif; font-weight: 700; text-transform: uppercase; border-radius: 4px; cursor: pointer;">CANCEL</button>
        </div>
      </div>

      <!-- 2. CONTACT INQUIRY FORM (SHOWN WHEN OPENED FROM VENDOR PAGE OR SPECIFIC VENDOR) -->
      <div id="order-modal-form-wrap" style="display: none;">
        <h2 style="font-family: 'Oswald', sans-serif !important; font-size: 2.2rem; margin: 0 0 6px 0; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; line-height: 1.1;">ORDER ONLINE</h2>
        <p style="color: rgba(255,255,255,0.75); font-size: 0.92rem; margin-bottom: 20px; line-height: 1.4;">Leave your contact details and order inquiry below — our team will get in touch with you shortly!</p>
        
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
          <div id="order-modal-vendor-tag" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(227,6,56,0.15); border: 1.5px solid #E30638; color: #ffffff; padding: 5px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
            <i class="fa-solid fa-store" style="color: #E30638;"></i>
            <span id="order-modal-vendor-label">PH'EAST FOOD HALL</span>
          </div>
          <button type="button" onclick="showOrderModalVendorList()" style="background: none; border: none; color: rgba(255,255,255,0.6); font-size: 0.8rem; font-weight: 600; cursor: pointer; text-decoration: underline;">&larr; All Vendors</button>
        </div>
        
        <form id="order-inquiry-form" onsubmit="handleOrderModalSubmit(event)" style="display: flex; flex-direction: column; gap: 14px;">
          <input type="hidden" id="order-cust-vendor" name="vendor" value="PH'EAST Food Hall">
          
          <div>
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #E30638; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Your Name *</label>
            <input type="text" id="order-cust-name" required placeholder="John Doe" class="footer-input-box" style="width: 100%; height: 44px; background: #181818; border: 1px solid #333; color: #fff; padding: 0 14px; border-radius: 4px; box-sizing: border-box; font-size: 0.95rem;">
          </div>
          
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
              <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #E30638; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Phone Number *</label>
              <input type="tel" id="order-cust-phone" required placeholder="(678) 000-0000" inputmode="tel" minlength="7" maxlength="20" class="footer-input-box" style="width: 100%; height: 44px; background: #181818; border: 1px solid #333; color: #fff; padding: 0 14px; border-radius: 4px; box-sizing: border-box; font-size: 0.95rem; transition: border-color 0.2s ease;">
              <span id="order-phone-error" style="display: none; color: #ff4757; font-size: 0.75rem; font-weight: 600; margin-top: 3px;">Please enter a valid phone number (7 to 15 digits)</span>
            </div>
            <div>
              <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #E30638; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Email Address (Optional)</label>
              <input type="email" id="order-cust-email" placeholder="name@email.com" class="footer-input-box" style="width: 100%; height: 44px; background: #181818; border: 1px solid #333; color: #fff; padding: 0 14px; border-radius: 4px; box-sizing: border-box; font-size: 0.95rem;">
            </div>
          </div>
          
          <div>
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #E30638; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Order Details / Questions</label>
            <textarea id="order-cust-notes" rows="3" placeholder="Describe what you would like to order or ask..." class="footer-input-box" style="width: 100%; background: #181818; border: 1px solid #333; color: #fff; padding: 10px 14px; border-radius: 4px; box-sizing: border-box; font-size: 0.95rem; resize: vertical;"></textarea>
          </div>
          
          <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px; background: #E30638; border: 2px solid #E30638; color: #ffffff; font-family: 'Oswald', sans-serif !important; font-size: 1.1rem; font-weight: 700 !important; letter-spacing: 1px; text-transform: uppercase; border-radius: 4px; cursor: pointer; margin-top: 6px; transition: all 0.3s ease;">SUBMIT ORDER</button>
        </form>
      </div>
      
      <!-- 3. SUCCESS MESSAGE STATE (HIDDEN INITIALLY) -->
      <div id="order-modal-success" style="display: none; text-align: center; padding: 25px 10px;">
        <div style="width: 60px; height: 60px; background: rgba(227,6,56,0.15); border: 2px solid #E30638; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px auto;">
          <i class="fa-solid fa-check" style="color: #E30638; font-size: 1.8rem;"></i>
        </div>
        <h3 style="font-family: 'Oswald', sans-serif !important; font-size: 2rem; color: #fff; text-transform: uppercase; margin: 0 0 10px 0;">THANK YOU!</h3>
        <p id="order-modal-success-msg" style="color: rgba(255,255,255,0.85); font-size: 1rem; line-height: 1.6; margin-bottom: 22px;">Your inquiry has been received. Our team will contact you shortly!</p>
        <button type="button" class="btn btn-primary" onclick="closeOrderModal()" style="padding: 10px 32px; background: #E30638; border: 2px solid #E30638; color: #fff; font-family: 'Oswald', sans-serif !important; font-weight: 700; text-transform: uppercase; border-radius: 4px; cursor: pointer;">CLOSE</button>
      </div>
    </div>
  </div>

  <div id="toast-notification" class="toast hidden">
    <span id="toast-text"></span>
  </div>

<?php wp_footer(); ?>
</body>
</html>
