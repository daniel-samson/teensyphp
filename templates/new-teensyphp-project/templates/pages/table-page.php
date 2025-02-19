<?= template(APP_ROOT . "/templates/layouts/page_beginning.php", ["title" => "Table Page",]); ?>
<?php if (empty($data)): ?>
    <p>No data found</p>
<?php else: ?>
    <?= template(APP_ROOT . "/templates/components/table.php", ["data" => $data]); ?>
<?php endif; ?>
<?= template(APP_ROOT . "/templates/layouts/page_end.php", []); ?>
