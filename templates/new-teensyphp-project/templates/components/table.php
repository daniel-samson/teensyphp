<table>
    <thead>
    <tr>
        <?php $columns = array_keys($data[0]); ?>
        <?php foreach ($columns as $column) { ?>
            <th><?= $column; ?></th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $row) { ?>
        <tr>
            <?php foreach ($row as $column => $value) { ?>
                <td><?= $value; ?></td>
            <?php } ?>
        </tr>
    <?php } ?>
    </tbody>
</table>
