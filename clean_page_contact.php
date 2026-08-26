<?php
/* Template Name: Contact Page */
get_header();

$hero_bg = get_field('contact_hero_bg') ?: get_template_directory_uri() . '/assets/venue-exterior.jpg';
$hero_title = get_field('contact_hero_title') ?: "CONTACT PH'EAST";
$hero_sub_red = get_field('contact_hero_sub_red') ?: "WE'D LOVE TO HEAR FROM YOU.";
$hero_subtitle = get_field('contact_hero_subtitle') ?: "Questions, feedback, or partnership inquiries? Send us a message and we'll get back to you soon.";
$form_title = get_field('contact_form_title') ?: "SEND US A MESSAGE";
$get_in_touch_title = get_field('contact_get_in_touch_title') ?: "GET IN TOUCH";
$faq_title = get_field('contact_faq_title') ?: "FREQUENTLY ASKED QUESTIONS";
?>

  <main style="background-color: #000000; color: #ffffff;">
    <!-- HERO SECTION -->
    <section class="hero-section" style="min-height: 420px;">
      <div class="hero-backdrop">
        <img src="<?php echo esc_url($hero_bg); ?>" alt="Venue Exterior">
        <div class="overlay"></div>
      </div>
      <div class="hero-content" style="max-width: 900px; padding: 60px 20px;">
        <h1 class="hero-title" style="font-size: 4.5rem; margin: 0 0 15px 0;"><?php echo esc_html($hero_title); ?></h1>
        <p class="hero-subtitle text-accent" style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #D41F3C !important; margin-bottom: 8px; animation-delay: 0.4s;"><?php echo esc_html($hero_sub_red); ?></p>
        <p class="hero-subtitle" style="font-size: 1.08rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; line-height: 1.45; max-width: 680px; margin: 0 auto; color: #ffffff; animation-delay: 0.7s;"><?php echo esc_html($hero_subtitle); ?></p>
      </div>
      <a href="#next-section" class="hero-scroll-indicator" onclick="scrollToNextSection(event)" aria-label="Scroll to next section">
        <i class="fa-solid fa-chevron-down"></i>
      </a>
    </section>

    <style>
      .contact-right-col {
        flex: 1; min-width: 300px;
        border-left: 1px solid #333;
        padding-left: 40px;
      }
      @media (max-width: 768px) {
        .contact-right-col {
          border-left: none;
          padding-left: 0;
          border-top: 1px solid #333;
          padding-top: 40px;
        }
      }
      
      /* FAQ ACCORDION STYLES */
      .faq-item {
        background: #000000;
        border-bottom: 1px solid #333333;
      }
      .faq-item:last-child {
        border-bottom: none;
      }
      .faq-btn {
        width: 100%;
        text-align: left;
        padding: 20px 24px;
        background: transparent;
        border: none;
        font-size: 1.15rem;
        font-family: inherit;
        font-weight: 500;
        color: #ffffff;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: color 0.25s ease, background-color 0.2s ease;
        box-sizing: border-box;
      }
      .faq-btn:hover {
        color: #D41F3C;
        background: rgba(255, 255, 255, 0.02);
      }
      .faq-btn i, .faq-btn svg {
        display: none !important;
      }
      .faq-btn::after {
        content: '+' !important;
        font-size: 1.8rem !important;
        font-weight: 300 !important;
        line-height: 1 !important;
        color: #D41F3C !important;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), color 0.25s ease !important;
        display: inline-block !important;
        margin-left: auto !important;
      }
      .faq-btn.active::after {
        transform: rotate(45deg) !important;
        color: #ffffff !important;
      }
      .faq-btn.active {
        color: #D41F3C;
      }
      .faq-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        background: transparent;
      }
    </style>

    <!-- CONTACT SECTION (FORM & PHONES) -->
    <section id="next-section" class="contact-section container" style="padding: 60px 20px; background-color: #000000; max-width: 1180px;">
      <div style="display: flex; flex-wrap: wrap; gap: 40px;">
        
        <!-- Left: Form -->
        <div style="flex: 1.5; min-width: 300px;">
          <h2 style="font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 2.5rem; text-transform: uppercase; margin-bottom: 25px; letter-spacing: 1px;"><?php echo esc_html($form_title); ?></h2>
          <form onsubmit="event.preventDefault(); showToast('Message sent successfully! We will get back to you soon.'); this.reset();">
            <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
              <input type="text" placeholder="First Name" class="form-control" style="flex: 1; min-width: 140px; padding: 12px; background: #000000; border: 1px solid #333; color: white; border-radius: 4px;" required>
              <input type="text" placeholder="Last Name" class="form-control" style="flex: 1; min-width: 140px; padding: 12px; background: #000000; border: 1px solid #333; color: white; border-radius: 4px;" required>
            </div>
            <div style="margin-bottom: 15px;">
              <input type="email" placeholder="Email Address" class="form-control" style="width: 100%; padding: 12px; background: #000000; border: 1px solid #333; color: white; border-radius: 4px;" required>
            </div>
            <div style="margin-bottom: 15px;">
              <input type="text" placeholder="Subject" class="form-control" style="width: 100%; padding: 12px; background: #000000; border: 1px solid #333; color: white; border-radius: 4px;" required>
            </div>
            <div style="margin-bottom: 25px;">
              <textarea placeholder="Your Message" class="form-control" rows="5" style="width: 100%; padding: 12px; background: #000000; border: 1px solid #333; color: white; border-radius: 4px; resize: vertical;" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 1rem; border-radius: 4px; background-color: #D41F3C; color: white; border: none; font-weight: bold; cursor: pointer; text-transform: uppercase;">SEND MESSAGE</button>
          </form>
        </div>

        <!-- Right: Vendor Phone List -->
        <div class="contact-right-col">
          <h2 style="font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 2.5rem; text-transform: uppercase; margin-bottom: 25px; letter-spacing: 1px;"><?php echo esc_html($get_in_touch_title); ?></h2>
          <ul style="list-style: none; padding: 0; margin: 0;">
            <?php 
            $phones = get_field('contact_phones');
            if (empty($phones)) {
                $phones = [
                    ['name' => "TAPS@PH'EAST", 'phone' => '(678) 247-8137'],
                    ['name' => 'POKE BURRI', 'phone' => '(470) 506-8453'],
                    ['name' => '26 THAI', 'phone' => '(678) 401-6415'],
                    ['name' => 'LIFTING NOODLES', 'phone' => '(404) 565-9539'],
                    ['name' => 'FAN T\'ASIA', 'phone' => '(770) 485-9968'],
                    ['name' => 'KUNG FU TEA', 'phone' => '(404) 913-3079'],
                ];
            }
            $total_phones = count($phones);
            foreach ($phones as $idx => $item):
                $is_last = ($idx === $total_phones - 1);
                $border_style = $is_last ? '' : 'padding-bottom: 15px; border-bottom: 1px solid #333;';
                $clean_phone = preg_replace('/[^0-9]/', '', $item['phone']);
            ?>
              <li style="margin-bottom: 15px; font-size: 1.1rem; <?php echo $border_style; ?>">
                <span style="color: #D41F3C; font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 1.4rem; letter-spacing: 1px; display: inline-block; width: 150px;"><?php echo esc_html($item['name']); ?></span> | <a href="tel:<?php echo esc_attr($clean_phone); ?>" style="color: inherit; text-decoration: none;"><?php echo esc_html($item['phone']); ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </section>

    <!-- FAQ ACCORDION SECTION -->
    <section class="faq-section" style="padding: 40px 20px 80px 20px; background-color: #000000;">
      <div class="container" style="max-width: 800px;">
        <hr style="border: 0; border-top: 1px solid #333; margin-bottom: 40px;">
        <h2 style="font-family: 'Bebas Kai', 'Bebas Kai Regular', 'Bebas Neue', sans-serif; font-size: 2.5rem; text-align: center; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 1px;"><?php echo esc_html($faq_title); ?></h2>
        
        <div style="border: 1px solid #333; background: #000000; border-radius: 8px; overflow: hidden;">
          <?php
          $faqs = get_field('contact_faqs');
          if (empty($faqs)) {
              $faqs = [
                  [
                      'question' => "What is PH'EAST?",
                      'answer'   => "PH'EAST is an Asian street food hall located at The Battery Atlanta, featuring six unique culinary concepts under one roof."
                  ],
                  [
                      'question' => "Where is PH'EAST located?",
                      'answer'   => "We're at 925 Battery Ave SE Ste. 1100, Atlanta, GA 30339, right next to Truist Park at The Battery."
                  ],
                  [
                      'question' => "Do you have Parking?",
                      'answer'   => "Yes! Free parking is available in the Red Deck at The Battery. Valet parking is also available on event days."
                  ],
                  [
                      'question' => "Can I book a private event?",
                      'answer'   => "Absolutely! We offer group dining, semi-private spaces, and fully customized event packages. Visit our Events page or email taps@pheast.com."
                  ],
                  [
                      'question' => "How do I make online orders?",
                      'answer'   => "Click the 'Order Online' button in our navigation to select your vendor and place your order for pickup."
                  ],
              ];
          }
          foreach ($faqs as $f_idx => $faq):
          ?>
            <div class="faq-item">
              <button class="faq-btn" onclick="toggleFaq(this)" type="button">
                <span><?php echo esc_html($faq['question']); ?></span>
              </button>
              <div class="faq-content">
                <div style="padding: 0 24px 20px 24px;">
                  <p style="color: rgba(255,255,255,0.75); line-height: 1.6; margin: 0; font-size: 1rem;"><?php echo nl2br(esc_html($faq['answer'])); ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </main>

<?php get_footer(); ?>
