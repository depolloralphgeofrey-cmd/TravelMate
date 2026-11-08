<?php
// Shared "Mode of payment" picker used by subscribe.php and travel.php
require_once __DIR__ . '/matching.php';

function render_pay_methods($selMethod = '', $selLocal = '') {
    $methods = payment_methods();
    $locals = local_payment_options();
    ?>
    <div class="pay-grid">
      <?php foreach ($methods as $key => $m): ?>
        <label class="pay-opt">
          <input type="radio" name="method" value="<?= e($key) ?>" data-note="<?= e($m[2]) ?>" <?= $selMethod === $key ? 'checked' : '' ?>>
          <span><i><?= $m[1] ?></i><?= e($m[0]) ?></span>
        </label>
      <?php endforeach; ?>
    </div>
    <div class="pay-detail" id="payDetail" hidden>
      <p id="payNote"></p>
      <div id="localBox" hidden>
        <label for="local">Choose your local instant payment</label>
        <select id="local" name="local">
          <option value="">Select…</option>
          <?php foreach ($locals as $l): ?><option <?= $selLocal === $l ? 'selected' : '' ?>><?= e($l) ?></option><?php endforeach; ?>
        </select>
      </div>
      <pre class="bank" id="bankBox" hidden><?= e(BANK_DETAILS) ?></pre>
    </div>
    <?php
}

// Validates the chosen method; returns [label, key, error]
function read_pay_choice() {
    $methods = payment_methods();
    $method = $_POST['method'] ?? '';
    $local = $_POST['local'] ?? '';
    if (!isset($methods[$method])) return [null, null, 'Please choose a mode of payment.'];
    if ($method === 'local' && !in_array($local, local_payment_options(), true)) {
        return [null, null, 'Please choose which local instant payment you want to use.'];
    }
    $label = $methods[$method][0] . ($method === 'local' ? ' – ' . $local : '');
    return [$label, $method, ''];
}
