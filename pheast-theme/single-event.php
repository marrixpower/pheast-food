<?php
get_header();

$date = get_field('event_date');
$time = get_field('event_time');
$location = get_field('event_location');
$link = get_field('event_link');
$event_img = get_field('event_image');
if (!$event_img && has_post_thumbnail()) {
    $event_img = get_the_post_thumbnail_url(get_the_ID(), 'full');
}
if (!$event_img) {
    $raw_content = get_post_field('post_content', get_the_ID());
    if (preg_match('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $raw_content, $img_matches)) {
        $event_img = $img_matches[1];
    }
}
if (!$event_img) {
    $event_img = get_template_directory_uri() . '/assets/events-live.jpg';
}
?>
  <main style="padding-top: 120px; background-color: var(--bg-dark); min-height: 100vh; padding-bottom: 80px;">
    <div class="container" style="max-width: 900px;">
      
      <div style="background: #000; border: 1px solid #333; border-radius: 12px; overflow: hidden; margin-bottom: 60px;">
        <div style="background-image: url('<?php echo esc_url($event_img); ?>'); background-size: cover; background-position: center; height: 400px; position: relative;">
          <!-- Date Badge -->
          <?php
          $ts = $date ? strtotime($date) : false;
          if (!$ts && $date) {
              $dt = DateTime::createFromFormat('Ymd', $date);
              if ($dt) $ts = $dt->getTimestamp();
          }
          $month_badge = $ts ? date('M', $ts) : 'EVENT';
          $day_badge = $ts ? date('d', $ts) : '--';
          ?>
          <div style="position: absolute; top: 20px; right: 20px; background: var(--accent-primary); color: #fff; padding: 15px 20px; border-radius: 8px; text-align: center; font-family: 'Bebas Kai', sans-serif; box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
            <div style="font-size: 1.2rem;"><?php echo esc_html($month_badge); ?></div>
            <div style="font-size: 2.5rem; line-height: 1;"><?php echo esc_html($day_badge); ?></div>
          </div>
        </div>
        
        <div style="padding: 50px;">
          <h1 style="font-family: 'Bebas Kai', sans-serif; font-size: 3.5rem; color: #fff; margin-bottom: 20px;"><?php the_title(); ?></h1>
          
          <div style="display: flex; flex-wrap: wrap; gap: 30px; margin-bottom: 40px; padding-bottom: 30px; border-bottom: 1px solid #333;">
            <div style="color: #fff; font-size: 1.1rem;">
                <i class="far fa-calendar-alt" style="color: var(--accent-primary); margin-right: 10px;"></i> 
                <?php echo esc_html($date); ?>
            </div>
            <div style="color: #fff; font-size: 1.1rem;">
                <i class="far fa-clock" style="color: var(--accent-primary); margin-right: 10px;"></i> 
                <?php echo esc_html($time); ?>
            </div>
            <div style="color: #fff; font-size: 1.1rem;">
                <i class="fas fa-map-marker-alt" style="color: var(--accent-primary); margin-right: 10px;"></i> 
                <?php echo esc_html($location); ?>
            </div>
          </div>
          
          <div class="event-content" style="color: #ccc; font-size: 1.15rem; line-height: 1.8; margin-bottom: 40px;">
            <?php the_content(); ?>
          </div>
          
          <?php if($link): ?>
          <div style="text-align: center;">
            <a href="<?php echo esc_url($link); ?>" target="_blank" class="btn btn-primary" style="font-size: 1.2rem; padding: 15px 40px; display: inline-block;">Get Tickets / Learn More</a>
          </div>
          <?php endif; ?>
        </div>
      </div>
      
    </div>
  </main>
<?php get_footer(); ?>
