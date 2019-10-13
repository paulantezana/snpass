<?php if ($message ?? ''): ?>
    <div class="SnAlert <?= $messageType ?? '' ?> SnMb-32"><i class="icon-<?= $messageType ?? '' ?>"></i><?php echo $message ?? '' ?></div>
<?php endif; ?>