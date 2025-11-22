<?php if (!$isProduction): ?>
<style>
.debug-bar {
  position: fixed;
  bottom: 0; left: 0; right: 0;
  background: #1a1a1a;
  color: #0f0;
  font-family: monospace;
  font-size: 12px;
  padding: 6px 10px;
  border-top: 2px solid #333;
  z-index: 9999;
  display: flex;
  justify-content: space-between;
}
.debug-bar span { margin-right: 10px; }
</style>

<div class="debug-bar">
  <span><b>Env:</b> <?= strtoupper($appEnv) ?></span>
  <span><b>DB:</b> <?= $db_status ?></span>
  <span><b>Load Time:</b> <?= $loadTime ?> ms</span>
  <span><b>PHP:</b> <?= phpversion() ?></span>
</div>
<?php endif; ?>
