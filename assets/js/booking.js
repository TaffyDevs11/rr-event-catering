// booking.js — Wizard navigation + validation + price calc
// Path: C:\xampp\htdocs\RRCatering\assets\js\booking.js

document.addEventListener('DOMContentLoaded', function () {
  const steps = Array.from(document.querySelectorAll('.step'));
  const form = document.getElementById('wizardForm');
  const progress = document.getElementById('wizardProgress');
  let current = 0;

  // Show/Hide helpers
  function showStep(index) {
    steps.forEach((s, i) => {
      s.classList.toggle('active', i === index);
    });
    const pct = Math.round(((index) / (steps.length - 1)) * 100);
    progress.style.width = pct + '%';
    current = index;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // Next / Prev buttons
  document.querySelectorAll('.next-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      if (validateStep(current)) showStep(Math.min(current + 1, steps.length - 1));
    });
  });
  document.querySelectorAll('.prev-btn').forEach(btn => {
    btn.addEventListener('click', () => showStep(Math.max(current - 1, 0)));
  });

  // Validate step
  function validateStep(index) {
    // Step 1: basic fields
    if (index === 0) {
      const required = ['full_name','email','phone','event_date','guests','event_address'];
      let ok = true;
      required.forEach(id => {
        const el = document.getElementById(id);
        if (!el || !el.value.trim()) { el && el.classList.add('input-error'); ok = false; }
        else el && el.classList.remove('input-error');
      });

      // check 7-day rule
      const dateEl = document.getElementById('event_date');
      if (dateEl && dateEl.value) {
        const sel = new Date(dateEl.value);
        const today = new Date();
        today.setHours(0,0,0,0);
        const min = new Date();
        min.setDate(today.getDate() + 7);
        if (sel < min) {
          alert('Please select a date at least 7 days from today.');
          return false;
        }
      }

      return ok;
    }

    // Step 2: menu selection limits
    if (index === 1) {
      // For each menu-section, confirm selected count equals max
      const sections = document.querySelectorAll('.menu-section');
      for (let sec of sections) {
        const max = parseInt(sec.getAttribute('data-category-max')) || null;
        // we store max on DOM? fallback: read hint text
        const hintNode = sec.querySelector('.hint');
        let maxAllowed = null;
        if (hintNode) {
          const match = hintNode.textContent.match(/Choose\s+(\d+)/);
          if (match) maxAllowed = parseInt(match[1]);
        }
        if (maxAllowed) {
          const checkboxes = sec.querySelectorAll('input.menu-checkbox:checked');
          if (checkboxes.length !== maxAllowed) {
            alert('Please select exactly ' + maxAllowed + ' items from "' + sec.querySelector('h3').textContent + '".');
            return false;
          }
        }
      }
      return true;
    }

    return true;
  }

  // Handle checkbox visuals and selection limits
  document.querySelectorAll('.menu-checkbox').forEach(cb => {
    cb.addEventListener('change', (e) => {
      const cat = cb.dataset.category;
      const section = document.querySelector('.menu-section[data-category="'+cat+'"]');
      const max = parseInt(section.querySelector('.hint').innerText.match(/\d+/)[0]);
      const selected = section.querySelectorAll('input.menu-checkbox:checked');

      // Toggle visual on parent
      if (cb.checked) cb.closest('.menu-item').classList.add('checked');
      else cb.closest('.menu-item').classList.remove('checked');

      if (selected.length > max) {
        cb.checked = false;
        cb.closest('.menu-item').classList.remove('checked');
        alert('You can only choose ' + max + ' items in this category.');
      }
      calculateTotals();
    });
  });

  // Calculate totals
  function calculateTotals() {
    let foodTotal = 0;
    const checked = document.querySelectorAll('input.menu-checkbox:checked');
    checked.forEach(c => {
      const p = parseFloat(c.dataset.price) || 0;
      foodTotal += p;
    });
    const transport = 120;
    const grand = (foodTotal + transport);
    // Set review values
    document.getElementById('rvFoodTotal').innerText = foodTotal.toFixed(2);
    document.getElementById('rvGrandTotal').innerText = grand.toFixed(2);
    document.getElementById('rvName').innerText = document.getElementById('full_name').value || '';
    document.getElementById('rvEmail').innerText = document.getElementById('email').value || '';
    document.getElementById('rvPhone').innerText = document.getElementById('phone').value || '';
    document.getElementById('rvDate').innerText = document.getElementById('event_date').value || '';
    document.getElementById('rvGuests').innerText = document.getElementById('guests').value || '';
    document.getElementById('rvAddress').innerText = document.getElementById('event_address').value || '';
    // list selected items
    const rvMenu = document.getElementById('rvMenu');
    rvMenu.innerHTML = '';
    const groups = {};
    checked.forEach(c => {
      const cat = c.dataset.category;
      const label = c.closest('.menu-item').querySelector('.mi-title').innerText;
      if (!groups[cat]) groups[cat] = [];
      groups[cat].push(label);
    });
    for (let cat in groups) {
      const div = document.createElement('div');
      div.className = 'rv-cat';
      div.innerHTML = '<strong>' + (document.querySelector('.menu-section[data-category="'+cat+'"] h3').innerText) + ':</strong> ' + groups[cat].join(', ');
      rvMenu.appendChild(div);
    }
    // hidden input
    document.getElementById('total_price').value = grand.toFixed(2);
  }

  // Update totals on input changes
  ['full_name','email','phone','event_date','guests','event_address'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', calculateTotals);
  });

  // Initial calc
  calculateTotals();

  // On form submit: final validation for step 2 rule also run
  form.addEventListener('submit', function(e){
    if (!validateStep(1)) { // ensure menu selection correct
      e.preventDefault();
      showStep(1);
      return false;
    }
    // Also run 7-day rule once more
    const dateEl = document.getElementById('event_date');
    if (dateEl && dateEl.value) {
      const sel = new Date(dateEl.value);
      const today = new Date(); today.setHours(0,0,0,0);
      const min = new Date(); min.setDate(today.getDate()+7);
      if (sel < min) {
        alert('Please select a date at least 7 days from today.');
        e.preventDefault();
        showStep(0);
        return false;
      }
    }

    // All ok — allow submit
    return true;
  });

  // Initialize - set data-category-max attributes for quick lookup
  document.querySelectorAll('.menu-section').forEach(sec => {
    const max = sec.querySelector('.hint') ? sec.querySelector('.hint').innerText.match(/\d+/)[0] : '0';
    sec.setAttribute('data-category-max', max);
  });

  showStep(0);
});
