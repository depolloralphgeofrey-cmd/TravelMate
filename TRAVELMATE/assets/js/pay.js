// ===== Mode-of-payment behaviour (subscribe.php, travel.php) =====
(function () {
  var form = document.querySelector('form[data-pay]');
  if (!form) return;
  var detail = document.getElementById('payDetail');
  var note = document.getElementById('payNote');
  var localBox = document.getElementById('localBox');
  var bankBox = document.getElementById('bankBox');

  function updateMethod() {
    var m = form.querySelector('input[name=method]:checked');
    if (!detail) return;
    detail.hidden = !m;
    if (m) {
      note.textContent = m.dataset.note || '';
      localBox.hidden = m.value !== 'local';
      bankBox.hidden = m.value !== 'bank';
    }
  }
  function refresh() {
    updateMethod();
    if (typeof window.payUpdate === 'function') window.payUpdate(form);
  }
  form.addEventListener('change', refresh);
  form.addEventListener('input', function () { if (typeof window.payUpdate === 'function') window.payUpdate(form); });
  form.addEventListener('submit', function () {
    var b = form.querySelector('.pay-submit');
    if (b) setTimeout(function () { b.disabled = true; b.textContent = 'Processing…'; }, 0);
  });
  refresh();
})();
