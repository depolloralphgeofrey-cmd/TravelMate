// ===== Pop-up ads for non-subscribed users (simulated Google AdMob interstitial) =====
(function () {
  var cfg = window.TM_ADS;
  var pop = document.getElementById('adPop');
  if (!cfg || !pop) return;

  var body = document.getElementById('adBody');
  var closeBtn = document.getElementById('adClose');
  var countEl = document.getElementById('adCount');
  var KEY = 'tm_ad_last', IDX = 'tm_ad_idx';
  var timer = null, tick = null, lastFocus = null;

  function get(k) { try { return sessionStorage.getItem(k); } catch (e) { return null; } }
  function set(k, v) { try { sessionStorage.setItem(k, String(v)); } catch (e) {} }
  function esc(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

  function schedule() {
    var last = parseInt(get(KEY) || '0', 10);
    var wait = cfg.delay * 1000;
    if (last) wait = Math.max(wait, cfg.gap * 1000 - (Date.now() - last));
    clearTimeout(timer);
    timer = setTimeout(show, wait);
  }

  function show() {
    if (document.hidden) { timer = setTimeout(show, 5000); return; }   // wait until the tab is visible
    var i = parseInt(get(IDX) || '0', 10) % cfg.creatives.length;
    set(IDX, i + 1);
    var c = cfg.creatives[i];
    body.innerHTML =
      '<div class="ad-art">' + esc(c.emoji) + '</div>' +
      '<small class="ad-brand">' + esc(c.brand) + '</small>' +
      '<h3>' + esc(c.head) + '</h3><p>' + esc(c.text) + '</p>' +
      '<a class="btn btn-small" href="#" id="adCta">' + esc(c.cta) + '</a>';
    document.getElementById('adCta').addEventListener('click', function (e) { e.preventDefault(); });

    lastFocus = document.activeElement;
    pop.hidden = false;
    pop.classList.add('open');
    set(KEY, Date.now());

    var left = 5;
    closeBtn.disabled = true;
    closeBtn.innerHTML = 'Close in <b id="adCount">' + left + '</b>';
    clearInterval(tick);
    tick = setInterval(function () {
      left--;
      if (left <= 0) {
        clearInterval(tick);
        closeBtn.disabled = false;
        closeBtn.textContent = 'Close ✕';
        closeBtn.focus();
      } else {
        closeBtn.innerHTML = 'Close in <b>' + left + '</b>';
      }
    }, 1000);
  }

  function close() {
    if (closeBtn.disabled) return;
    pop.classList.remove('open');
    pop.hidden = true;
    set(KEY, Date.now());
    if (lastFocus && lastFocus.focus) { try { lastFocus.focus(); } catch (e) {} }
    schedule();
  }

  closeBtn.addEventListener('click', close);
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
  schedule();
})();
