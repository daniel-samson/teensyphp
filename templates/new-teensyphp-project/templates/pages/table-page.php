<?= template(app_root() . "/templates/layouts/page_beginning.php", ["title" => "Table Page",]); ?>
<?php if (empty($data)): ?>
    <p>No data found</p>
<?php else: ?>
    <?= template(app_root() . "/templates/components/table.php", ["data" => $data]); ?>
<?php endif; ?>
<?= template(app_root() . "/templates/layouts/page_end.php", []); ?>
