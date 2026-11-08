// ===== TravelMate results: infinite scroll, like / pass, match popup =====
document.addEventListener('DOMContentLoaded', function () {
  var box = document.getElementById('results');
  var sentinel = document.getElementById('sentinel');
  if (!box || !sentinel) return;

  var premium = box.dataset.premium === '1';
  var offset = 0, loading = false, done = false, seen = 0, gen = 0, empty = 0;
  var sentinelHTML = sentinel.innerHTML;
  var seenEl = document.getElementById('seenCount');
  var modal = document.getElementById('matchModal');
  var fMin = document.getElementById('fMin'), fBudget = document.getElementById('fBudget'), fShared = document.getElementById('fShared');

  function query() {
    var q = 'offset=' + offset;
    if (fMin && !fMin.disabled && fMin.value !== '0') q += '&min=' + fMin.value;
    if (fBudget && !fBudget.disabled && fBudget.checked) q += '&budget=1';
    if (fShared && !fShared.disabled && fShared.checked) q += '&shared=1';
    return q;
  }

  function load() {
    if (loading || done) return;
    loading = true;
    var myGen = gen;
    fetch('api_results.php?' + query(), { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (d) {
        if (myGen !== gen) return;                      // filters changed while loading
        if (d.error) { window.location.href = d.error === 'auth' ? 'login.php' : 'profile.php'; return; }
        if (d.html) {
          box.insertAdjacentHTML('beforeend', d.html);
          seen += d.count;
          if (seenEl) seenEl.textContent = Math.min(seen, 10);
        }
        offset = d.next;
        if (d.count === 0 && !d.locked) { empty++; } else { empty = 0; }
        if (empty >= 12) {                                // strict filters: stop instead of loading forever
          done = true;
          sentinel.innerHTML = '<span>No more travelers match these filters.</span>';
          loading = false;
          return;
        }
        if (d.locked) {
          if (d.lock) box.insertAdjacentHTML('beforeend', d.lock);
          done = true;
          sentinel.style.display = 'none';
        }
        loading = false;
        var rect = sentinel.getBoundingClientRect();
        if (!done && rect.top < window.innerHeight) load();
      })
      .catch(function () {
        if (myGen !== gen) return;
        loading = false;
        sentinel.querySelector('span').textContent = 'Could not load. Scroll to retry.';
      });
  }

  function resetResults() {
    gen++; offset = 0; seen = 0; empty = 0; done = false; loading = false;
    sentinel.innerHTML = sentinelHTML;
    box.innerHTML = '';
    sentinel.style.display = '';
    load();
  }
  [fMin, fBudget, fShared].forEach(function (el) { if (el) el.addEventListener('change', resetResults); });

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      if (entries[0].isIntersecting) load();
    }, { rootMargin: '300px' }).observe(sentinel);
  } else {
    window.addEventListener('scroll', load);
    load();
  }

  // Like / Pass buttons
  box.addEventListener('click', function (e) {
    var btn = e.target.closest('button[data-act]');
    if (!btn) return;
    var card = btn.closest('.t-card');
    var id = card.dataset.id;

    if (btn.dataset.act === 'pass') {
      card.classList.add('gone');
      setTimeout(function () { card.remove(); }, 350);
      return;
    }
    btn.disabled = true;
    var fd = new FormData();
    fd.append('id', id);
    fetch('api_like.php', { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (d) {
        if (!d.ok) { btn.disabled = false; return; }
        btn.textContent = '❤️ Liked';
        btn.classList.add('liked');
        var mc = document.getElementById('matchCount');
        if (mc) mc.textContent = d.matchCount;
        if (d.matched && modal) {
          document.getElementById('matchName').textContent = d.name;
          document.getElementById('matchChat').href = 'chat.php?id=' + d.id;
          modal.classList.add('open');
        }
      });
  });

  var close = document.getElementById('matchClose');
  if (close) close.addEventListener('click', function () { modal.classList.remove('open'); });
  if (modal) modal.addEventListener('click', function (e) { if (e.target === modal) modal.classList.remove('open'); });
});
