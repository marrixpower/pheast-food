/* ==========================================================================
   PH'EAST FOOD HALL - INTERACTIVE SCRIPT
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  // Load saved theme or default to bold
  const savedTheme = localStorage.getItem('pheast_theme') || 'bold';
  switchTheme(savedTheme, false);

  // Initialize premium scroll animations
  initScrollAnimations();

  // Initialize auto-scrolling vendor carousel
  initAutoCarousel();

  // Initialize hero scroll down indicator
  initScrollIndicator();

  // Initialize mobile gallery filter carousel
  initGalleryFilterCarousel();
});

/**
 * Switch Theme Concept
 * @param {string} themeName - 'bold' | 'minimal' | 'app'
 * @param {boolean} showNotification - whether to show toast
 */
function switchTheme(themeName, showNotification = true) {
  // Update HTML data-theme attribute
  document.documentElement.setAttribute('data-theme', themeName);
  
  // Save to localStorage
  localStorage.setItem('pheast_theme', themeName);

  // Update theme button active states
  const buttons = document.querySelectorAll('.theme-btn');
  buttons.forEach(btn => {
    if (btn.getAttribute('data-target-theme') === themeName) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });

  if (showNotification) {
    let themeTitle = "1. Bold & Dynamic (Street Market)";
    if (themeName === 'minimal') themeTitle = "2. Zen Garden (Elevated)";
    if (themeName === 'app') themeTitle = "3. Neon Pop (User-Centric)";
    showToast(`Switched to: ${themeTitle}`);
  }
}

/**
 * Filter Vendors by Category
 */
function filterVendors(category) {
  const pills = document.querySelectorAll('.filter-pills .pill');
  pills.forEach(p => p.classList.remove('active'));
  if (event && event.target) {
    event.target.classList.add('active');
  }

  const cards = document.querySelectorAll('.vendor-card');
  cards.forEach(card => {
    if (category === 'all' || card.getAttribute('data-category') === category) {
      card.style.display = 'block';
      card.style.animation = 'fadeIn 0.4s ease';
    } else {
      card.style.display = 'none';
    }
  });
}

function filterGallery(category) {
  const pills = document.querySelectorAll('.gallery-filters .filter-pill');
  pills.forEach(p => p.classList.remove('active'));
  if (event && event.target) {
    event.target.classList.add('active');
  }

  const items = document.querySelectorAll('.gallery-item');
  items.forEach(item => {
    if (category === 'all' || item.getAttribute('data-category') === category) {
      item.style.display = 'block';
      item.style.animation = 'fadeIn 0.4s ease';
    } else {
      item.style.display = 'none';
    }
  });
}

/**
 * Open/Close Order Modal & Contact Form
 */
function openOrderModal(vendorName) {
  const modal = document.getElementById('order-modal');
  const formWrap = document.getElementById('order-modal-form-wrap');
  const successWrap = document.getElementById('order-modal-success');
  const vendorInput = document.getElementById('order-cust-vendor');
  const vendorLabel = document.getElementById('order-modal-vendor-label');

  if (formWrap) formWrap.style.display = 'block';
  if (successWrap) successWrap.style.display = 'none';

  // Auto-detect vendor if not passed explicitly
  let targetVendor = vendorName;
  if (!targetVendor || targetVendor === '') {
    const singleVendorTitle = document.querySelector('.vendor-hero-section h1');
    if (singleVendorTitle) {
      targetVendor = singleVendorTitle.innerText.replace(/\s+/g, ' ').trim();
    } else {
      targetVendor = "PH'EAST Food Hall";
    }
  }

  if (vendorInput) vendorInput.value = targetVendor;
  if (vendorLabel) vendorLabel.textContent = targetVendor;

  if (modal) modal.classList.add('active');
}

function closeOrderModal() {
  const modal = document.getElementById('order-modal');
  if (modal) modal.classList.remove('active');
}

function handleOrderModalSubmit(event) {
  event.preventDefault();
  const form = event.target;
  const nameInput = document.getElementById('order-cust-name');
  const name = nameInput ? nameInput.value.trim() : '';
  const phoneInput = document.getElementById('order-cust-phone');
  const phoneError = document.getElementById('order-phone-error');
  const phone = phoneInput ? phoneInput.value.trim() : '';
  const email = document.getElementById('order-cust-email') ? document.getElementById('order-cust-email').value.trim() : '';
  const vendor = document.getElementById('order-cust-vendor') ? document.getElementById('order-cust-vendor').value.trim() : "PH'EAST Food Hall";
  const notes = document.getElementById('order-cust-notes') ? document.getElementById('order-cust-notes').value.trim() : '';
  
  // 1. Validate Name (min 2 chars, cannot be just digits)
  if (!name || name.length < 2 || /^[\d\W]+$/.test(name)) {
    if (nameInput) {
      nameInput.style.borderColor = '#ff4757';
      nameInput.focus();
    }
    showToast("Будь ласка, введіть дійсне ім'я (мінімум 2 літери).");
    return;
  } else if (nameInput) {
    nameInput.style.borderColor = '#333';
  }

  // 2. Strict Phone Validation (9-15 digits, no repeated fake numbers like 999999999)
  const digitsOnly = phone.replace(/[^0-9]/g, '');
  const isRepeated = /^(\d)\1+$/.test(digitsOnly);

  if (digitsOnly.length < 9 || digitsOnly.length > 15 || isRepeated) {
    if (phoneError) {
      phoneError.textContent = 'Введіть дійсний номер (від 9 до 15 цифр, напр. (678) 123-4567)';
      phoneError.style.display = 'block';
    }
    if (phoneInput) {
      phoneInput.style.borderColor = '#ff4757';
      phoneInput.focus();
    }
    showToast('Будь ласка, введіть дійсний номер телефону.');
    return;
  } else {
    if (phoneError) phoneError.style.display = 'none';
    if (phoneInput) phoneInput.style.borderColor = '#333';
  }

  const submitBtn = form.querySelector('button[type="submit"]');
  if (submitBtn) {
    submitBtn.disabled = true;
    submitBtn.textContent = 'SENDING / НАДСИЛАННЯ...';
  }

  const formData = new FormData();
  formData.append('action', 'submit_pheast_order');
  formData.append('name', name);
  formData.append('phone', phone);
  formData.append('email', email);
  formData.append('vendor', vendor);
  formData.append('notes', notes);

  const ajaxUrl = (typeof pheast_ajax !== 'undefined' && pheast_ajax.ajax_url) ? pheast_ajax.ajax_url : '/wp-admin/admin-ajax.php';

  fetch(ajaxUrl, {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (!data.success) {
      const errorMsg = (data.data && data.data.message) ? data.data.message : 'Помилка валідації даних.';
      if (phoneError) {
        phoneError.textContent = errorMsg;
        phoneError.style.display = 'block';
      }
      if (phoneInput) {
        phoneInput.style.borderColor = '#ff4757';
        phoneInput.focus();
      }
      showToast(errorMsg);
      return;
    }

    const formWrap = document.getElementById('order-modal-form-wrap');
    const successWrap = document.getElementById('order-modal-success');
    const successMsg = document.getElementById('order-modal-success-msg');

    if (formWrap && successWrap) {
      formWrap.style.display = 'none';
      successWrap.style.display = 'block';
      if (successMsg) {
        successMsg.innerHTML = `Дякуємо, <strong>${name}</strong>!<br>Ваш запит для <strong>${vendor}</strong> успішно збережено в системі.<br>Ми зв'яжемося з вами за телефоном <strong>${phone}</strong> найближчим часом!`;
      }
    }
    showToast(`Запит для ${vendor} збережено!`);
    form.reset();
  })
  .catch(err => {
    console.error('Submission error:', err);
    showToast('Помилка з\'єднання. Спробуйте ще раз.');
  })
  .finally(() => {
    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.textContent = 'SUBMIT / НАДІСЛАТИ';
    }
  });
}

/**
 * Event RSVP Simulation
 */
function rsvpEvent(eventName) {
  showToast(`Successfully RSVP'd for ${eventName}! Added to calendar.`);
}

/**
 * Newsletter Form Simulation
 */
function submitNewsletter() {
  const emailInput = document.getElementById('newsletter-email');
  if (emailInput && emailInput.value.trim() !== '') {
    showToast(`Thanks! ${emailInput.value} joined the PH'EAST list.`);
    emailInput.value = '';
  } else {
    showToast('Please enter a valid email address.');
  }
}

/**
 * FAQ Accordion
 */
function toggleFaq(element) {
  const content = element.nextElementSibling;
  element.classList.toggle('active');
  if (content.style.maxHeight && content.style.maxHeight !== '0px') {
    content.style.maxHeight = '0px';
  } else {
    content.style.maxHeight = content.scrollHeight + "px";
  }
}

/**
 * Toast Notification System
 */
function showToast(message) {
  const toast = document.getElementById('toast-notification');
  const toastText = document.getElementById('toast-text');
  
  if (toast && toastText) {
    toastText.textContent = message;
    toast.classList.remove('hidden');

    setTimeout(() => {
      toast.classList.add('hidden');
    }, 3500);
  }
}

/**
 * Toggle Mobile Menu
 */
function toggleMobileMenu() {
  const menu = document.querySelector('.nav-menu');
  if (menu) {
    const isActive = menu.classList.toggle('active');
    if (isActive) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = '';
    }
  }
}

/**
 * Slide Carousel Left or Right
 */
function slideCarousel(direction) {
  const track = document.querySelector('.carousel-track');
  if (!track) return;

  const card = track.querySelector('.vendor-card');
  if (!card) return;

  const style = window.getComputedStyle(track);
  const gap = parseFloat(style.gap) || 14;
  const step = card.offsetWidth + gap;

  const maxScroll = track.scrollWidth - track.clientWidth;
  const currentScroll = track.scrollLeft;

  // Cyclic Loop: If at end and clicking Next -> Loop to start (left: 0)
  if (direction > 0 && currentScroll >= maxScroll - 20) {
    track.scrollTo({
      left: 0,
      behavior: 'smooth'
    });
    return;
  }

  // Cyclic Loop: If at start and clicking Prev -> Loop to end (left: maxScroll)
  if (direction < 0 && currentScroll <= 20) {
    track.scrollTo({
      left: maxScroll,
      behavior: 'smooth'
    });
    return;
  }

  // Otherwise calculate target position
  const nextScroll = currentScroll + (direction * step);
  const targetLeft = Math.min(maxScroll, Math.max(0, nextScroll));

  track.scrollTo({
    left: targetLeft,
    behavior: 'smooth'
  });
}

/**
 * Auto-Scroll Carousel Manager for Vendor Track
 */
let autoCarouselTimer = null;

function initAutoCarousel() {
  const container = document.querySelector('.carousel-track-container');
  const track = document.querySelector('.carousel-track');
  if (!container || !track) return;

  function startAutoScroll() {
    stopAutoScroll();
    autoCarouselTimer = setInterval(() => {
      slideCarousel(1);
    }, 2500); // Smooth auto-scroll every 2.5 seconds
  }

  function stopAutoScroll() {
    if (autoCarouselTimer) {
      clearInterval(autoCarouselTimer);
      autoCarouselTimer = null;
    }
  }

  startAutoScroll();

  // Pause on hover or user touch interaction for smooth UX
  container.addEventListener('mouseenter', stopAutoScroll);
  container.addEventListener('mouseleave', startAutoScroll);
  container.addEventListener('touchstart', stopAutoScroll, { passive: true });
  container.addEventListener('touchend', () => {
    setTimeout(startAutoScroll, 3000);
  }, { passive: true });
}

/**
 * Slide Gallery Photo Carousel Left or Right (Cyclic Loop)
 */
function slideGalleryCarousel(direction) {
  const track = document.querySelector('.gallery-track');
  if (!track) return;

  const item = track.querySelector('.gallery-photo-item');
  if (!item) return;

  const step = item.offsetWidth;
  const maxScroll = track.scrollWidth - track.clientWidth;
  const currentScroll = track.scrollLeft;

  // Cyclic Loop: If at end and clicking Next -> Loop to start (left: 0)
  if (direction > 0 && currentScroll >= maxScroll - 10) {
    track.scrollTo({
      left: 0,
      behavior: 'smooth'
    });
    return;
  }

  // Cyclic Loop: If at start and clicking Prev -> Loop to end (left: maxScroll)
  if (direction < 0 && currentScroll <= 10) {
    track.scrollTo({
      left: maxScroll,
      behavior: 'smooth'
    });
    return;
  }

  // Otherwise calculate strict integer step
  const targetIndex = Math.round((currentScroll + direction * step) / step);
  const targetLeft = Math.min(maxScroll, Math.max(0, targetIndex * step));

  track.scrollTo({
    left: targetLeft,
    behavior: 'smooth'
  });
}

/**
 * Slide Events Carousel Left or Right by 1 Fixed Card Step (Cyclic Loop)
 */
function slideEventsCarousel(direction) {
  const track = document.querySelector('.events-track');
  if (!track) return;

  const card = track.querySelector('.event-mock-card');
  if (!card) return;

  const style = window.getComputedStyle(track);
  const gap = parseFloat(style.gap) || 20;
  const step = card.offsetWidth + gap;

  const maxScroll = track.scrollWidth - track.clientWidth;
  const currentScroll = track.scrollLeft;

  // Cyclic Loop: If at end and clicking Next -> Loop to start (left: 0)
  if (direction > 0 && currentScroll >= maxScroll - 10) {
    track.scrollTo({
      left: 0,
      behavior: 'smooth'
    });
    return;
  }

  // Cyclic Loop: If at start and clicking Prev -> Loop to end (left: maxScroll)
  if (direction < 0 && currentScroll <= 10) {
    track.scrollTo({
      left: maxScroll,
      behavior: 'smooth'
    });
    return;
  }

  // Calculate precise fixed target step (1 card width + gap)
  const currentCardIndex = Math.round(currentScroll / step);
  const targetIndex = currentCardIndex + direction;
  const targetLeft = Math.min(maxScroll, Math.max(0, targetIndex * step));

  track.scrollTo({
    left: targetLeft,
    behavior: 'smooth'
  });
}

window.onclick = function(event) {
  const modal = document.getElementById('order-modal');
  if (event.target === modal) {
    closeOrderModal();
  }
};

/**
 * Premium Scroll Reveal Animations (Intersection Observer)
 */
function initScrollAnimations() {
  const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.15
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('reveal-active');
        observer.unobserve(entry.target); // Run once
      }
    });
  }, observerOptions);

  // Auto-apply specific reveal classes for variety
  const animMap = [
    { selector: 'section h2:not(.hero-title)', type: 'reveal-left' },
    { selector: 'section h3', type: 'reveal-right' },
    { selector: 'section p:not(.footer-subtext):not(.hero-subtitle)', type: 'reveal-right' },
    { selector: '.vendor-card, .gallery-photo-item, .gallery-item, .filter-pills .pill', type: 'reveal-scale' },
    { selector: '.about-grid-section img', type: '' },
    { selector: '.hawker-image-col img', type: 'reveal-left' },
    { selector: '.hawker-text-col', type: 'reveal-right' },
    { selector: '.contact-info', type: 'reveal-left' },
    { selector: '.contact-form', type: 'reveal-right' },
    { selector: '.btn:not(.nav-menu .btn, .theme-btn)', type: 'reveal-scale' },
    { selector: 'hr, .footer-heading, .faq-item', type: '' },
    { selector: '.footer-brand-logo', type: 'reveal-scale' }
  ];

  animMap.forEach(config => {
    document.querySelectorAll(config.selector).forEach((el, index) => {
      if (!el.classList.contains('reveal-element')) {
        el.classList.add('reveal-element');
        if (config.type) {
          el.classList.add(config.type);
        }
        
        // Add staggering based on index for grids
        if (el.classList.contains('vendor-card') || el.classList.contains('gallery-photo-item') || el.classList.contains('gallery-item') || el.classList.contains('pill') || el.classList.contains('faq-item')) {
          const delay = (index % 4) + 1;
          el.classList.add(`reveal-delay-${delay}`);
        }
      }
      
      // Delay observing to ensure the initial 'hidden' state is painted by the browser
      // This forces the animation to play even for elements already in the viewport on load
      setTimeout(() => {
        observer.observe(el);
      }, 150);
    });
  });

  // Ensure any elements manually given the reveal-element class in HTML are also observed
  // This fixes manually hidden elements staying invisible if they don't match the animMap
  document.querySelectorAll('.reveal-element').forEach(el => {
    setTimeout(() => {
      observer.observe(el);
    }, 150);
  });
}

/**
 * Smooth Scroll to Next Section & Fade Hero Scroll Indicator
 */
function scrollToNextSection(e) {
  if (e) e.preventDefault();
  const hero = document.querySelector('.hero-section, .vendor-hero-section');
  if (hero) {
    let nextSection = hero.nextElementSibling;
    while (nextSection && (nextSection.tagName === 'SCRIPT' || nextSection.tagName === 'STYLE' || nextSection.offsetHeight === 0)) {
      nextSection = nextSection.nextElementSibling;
    }
    if (!nextSection || nextSection.tagName === 'SCRIPT') {
      nextSection = document.querySelector('main > section:nth-of-type(2), main > div:not(.hero-section), body > section:nth-of-type(2)');
    }
    if (nextSection) {
      nextSection.scrollIntoView({ behavior: 'smooth' });
    } else {
      window.scrollTo({ top: window.innerHeight, behavior: 'smooth' });
    }
  } else {
    window.scrollTo({ top: window.innerHeight, behavior: 'smooth' });
  }
}

function initScrollIndicator() {
  const indicator = document.querySelector('.hero-scroll-indicator');
  if (!indicator) return;

  const handleScroll = () => {
    const scrollY = window.scrollY || window.pageYOffset || document.documentElement.scrollTop || 0;
    const maxScroll = 160;
    
    if (scrollY <= 0) {
      indicator.style.opacity = '1';
      indicator.style.pointerEvents = 'auto';
    } else if (scrollY >= maxScroll) {
      indicator.style.opacity = '0';
      indicator.style.pointerEvents = 'none';
    } else {
      const opacity = 1 - (scrollY / maxScroll);
      indicator.style.opacity = opacity.toFixed(2);
      indicator.style.pointerEvents = opacity > 0.1 ? 'auto' : 'none';
    }
  };

  window.addEventListener('scroll', handleScroll, { passive: true });
  handleScroll();
}

/**
 * Gallery Filter Mobile Carousel (Fixed 1-item snap, manual scroll only)
 */
function initGalleryFilterCarousel() {
  const container = document.querySelector('.gallery-filters');
  if (!container) return;

  const pills = Array.from(container.querySelectorAll('.filter-pill'));
  if (pills.length === 0) return;

  // When a filter pill is clicked, center that exact pill smoothly inside the bar
  pills.forEach((pill) => {
    pill.addEventListener('click', () => {
      const pillOffsetLeft = pill.offsetLeft;
      const pillWidth = pill.offsetWidth;
      const containerWidth = container.clientWidth;
      const targetScroll = pillOffsetLeft - (containerWidth / 2) + (pillWidth / 2);
      container.scrollTo({ left: targetScroll, behavior: 'smooth' });
    });
  });
}

/**
 * Handle Newsletter Form Submission
 */
function handleNewsletterSubmit(e) {
  if (e) e.preventDefault();
  const input = document.getElementById('newsletter-email');
  if (input && input.value.trim()) {
    showToast(`Thank you for subscribing!`);
    input.value = '';
  }
}
