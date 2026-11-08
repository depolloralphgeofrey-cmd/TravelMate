// ===== Rewarded ad flow (rewards.php) =====
(function () {
  var cfg = window.TM_RW;
  var btn = document.getElementById('watchBtn');
  var ov = document.getElementById('rwOverlay');
  if (!cfg || !btn || !ov) return;

  var claim = document.getElementById('rwClaim');
  var bar = document.getElementById('rwBar');
  var msg = document.getElementById('adMsg');
  var token = null, iv = null;

  function post(action, extra) {
    var fd = new FormData();
    fd.append('action', action);
    if (extra) Object.keys(extra).forEach(function (k) { fd.append(k, extra[k]); });
    return fetch('api_ad.php', { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) { return r.json(); });
  }
  function setMsg(t) { if (msg) msg.textContent = t || ''; }

  btn.addEventListener('click', function () {
    btn.disabled = true;
    setMsg('');
    post('start').then(function (d) {
      if (!d.ok) {
        setMsg(d.message || 'Could not start the ad.');
        btn.disabled = (d.error === 'limit' || d.error === 'premium');
        if (d.error === 'premium') window.location.reload();
        return;
      }
      token = d.token;
      var total = d.seconds, left = total;
      ov.hidden = false; ov.classList.add('open');
      claim.disabled = true; bar.style.width = '0';
      claim.innerHTML = 'Reward in <b id="rwCount">' + left + '</b>s';
      clearInterval(iv);
      iv = setInterval(function () {
        left--;
        bar.style.width = Math.min(100, Math.round((total - left) / total * 100)) + '%';
        if (left <= 0) {
          clearInterval(iv);
          claim.disabled = false;
          claim.textContent = '🎁 Claim ' + cfg.points + ' points';
        } else {
          claim.innerHTML = 'Reward in <b>' + left + '</b>s';
        }
      }, 1000);
    }).catch(function () { setMsg('Network error. Please try again.'); btn.disabled = false; });
  });

  claim.addEventListener('click', function () {
    claim.disabled = true;
    post('complete', { token: token }).then(function (d) {
      ov.classList.remove('open'); ov.hidden = true;
      if (d.ok) {
        document.getElementById('ptsNow').textContent = d.points;
        document.getElementById('ptsPeso').textContent = '₱' + Number(d.peso).toFixed(2);
        document.getElementById('adsToday').textContent = d.today;
        document.getElementById('ptsBar').style.width = Math.min(100, Math.round(d.points / cfg.cost * 100)) + '%';
        setMsg('+' + d.earned + ' points added! ' + (d.remaining > 0 ? d.remaining + ' ads left today.' : 'That was your last ad for today.'));
        btn.disabled = d.remaining <= 0;
        if (d.remaining <= 0) btn.textContent = 'Come back tomorrow';
        // enable redeem buttons when enough points
        if (d.points >= cfg.cost) document.querySelectorAll('.feat-actions .btn-dark').forEach(function (b) { b.disabled = false; b.removeAttribute('title'); });
      } else {
        setMsg(d.message || 'No points were added.');
        btn.disabled = d.error === 'limit';
      }
    });
  });
})();
