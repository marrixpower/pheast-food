/* ==========================================================================
   PH'EAST FOOD HALL - INTERACTIVE SCRIPT
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  // Load saved theme or default to bold
  const savedTheme = localStorage.getItem('pheast_theme') || 'bold';
  switchTheme(savedTheme, false);
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
 * Open/Close Order Modal
 */
function openOrderModal() {
  const modal = document.getElementById('order-modal');
  if (modal) modal.classList.add('active');
}

function closeOrderModal() {
  const modal = document.getElementById('order-modal');
  if (modal) modal.classList.remove('active');
}

function selectOrderVendor(vendorName) {
  closeOrderModal();
  showToast(`Opened menu for: ${vendorName}!`);
}

function quickSelectVendor(vendorId) {
  openOrderModal();
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
  if (content.style.maxHeight) {
    content.style.maxHeight = null;
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
    menu.classList.toggle('active');
  }
}

// Close modal on background click
window.onclick = function(event) {
  const modal = document.getElementById('order-modal');
  if (event.target === modal) {
    closeOrderModal();
  }
};
