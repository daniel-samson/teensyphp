<?= template(APP_ROOT . "/templates/layouts/page_beginning.php", ["title" => "500 Internal Server Error",]); ?>
<p>An internal server error has occurred.</p>
<?php if (isset($message)): ?>
    <p><?= $message; ?></p>
<?php endif; ?>
<?= template(APP_ROOT . "/templates/layouts/page_end.php", []); ?>
