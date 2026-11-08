// ===== TRAVELMATE main.js =====
document.addEventListener('DOMContentLoaded', function () {
  // Mobile nav
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('mainNav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open);
    });
  }

  // Header shadow on scroll
  var header = document.getElementById('siteHeader');
  window.addEventListener('scroll', function () {
    if (header) header.classList.toggle('scrolled', window.scrollY > 10);
  });

  // Reveal on scroll
  var reveals = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('show'); io.unobserve(e.target); }
      });
    }, { threshold: 0.12 });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('show'); });
  }

  // Accordion
  document.querySelectorAll('.acc-item').forEach(function (item) {
    var btn = item.querySelector('.acc-btn');
    var panel = item.querySelector('.acc-panel');
    btn.addEventListener('click', function () {
      var isOpen = item.classList.toggle('open');
      panel.style.maxHeight = isOpen ? panel.scrollHeight + 'px' : 0;
    });
  });

  // Animated bar chart
  var bars = document.querySelectorAll('.bar i');
  if (bars.length) {
    var chartIO = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          bars.forEach(function (b) { b.style.height = b.dataset.h + '%'; });
          chartIO.disconnect();
        }
      });
    }, { threshold: 0.3 });
    chartIO.observe(bars[0].closest('.bars'));
  }

  // Counter animation
  document.querySelectorAll('[data-count]').forEach(function (el) {
    var target = parseInt(el.dataset.count, 10);
    var suffix = el.dataset.suffix || '';
    var cio = new IntersectionObserver(function (entries) {
      if (entries[0].isIntersecting) {
        var cur = 0, step = Math.max(1, Math.ceil(target / 40));
        var t = setInterval(function () {
          cur += step;
          if (cur >= target) { cur = target; clearInterval(t); }
          el.textContent = cur + suffix;
        }, 30);
        cio.disconnect();
      }
    });
    cio.observe(el);
  });

  // Trip cost splitter (App page)
  var calc = document.getElementById('splitCalc');
  if (calc) {
    var fields = calc.querySelectorAll('input');
    var out = document.getElementById('splitResult');
    var run = function () {
      var total = 0;
      calc.querySelectorAll('input[data-cost]').forEach(function (i) { total += parseFloat(i.value) || 0; });
      var people = Math.max(1, parseInt(document.getElementById('people').value, 10) || 1);
      out.textContent = '₱' + Math.round(total / people).toLocaleString() + ' / person';
    };
    fields.forEach(function (i) { i.addEventListener('input', run); });
    run();
  }

  // Match score demo (App page)
  var matchForm = document.getElementById('matchDemo');
  if (matchForm) {
    matchForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var checks = matchForm.querySelectorAll('input[type=checkbox]:checked').length;
      var total = matchForm.querySelectorAll('input[type=checkbox]').length;
      var score = Math.round(50 + (checks / total) * 50);
      document.getElementById('matchScore').textContent = score + '% Match';
    });
  }
});
