<div class="col col-sm-12">
    <button class="btn btn-primary">Добавить</button>
</div>
<div class="col col-sm-12">
<table class="table table-bordered table-striped">
    <thead>
        <th>№</th>
        <th>Имя</th>
        <th>Фамилия</th>
        <th>Телефон</th>
        <th>Почта</th>
    </thead>
    <tbody>
        <?php foreach ($items as $row => $item): ?>
        <tr id="item-<?= $item->id ?>">
            <td><?= ++$row ?></td>
            <td><?= $item->name ?></td>
            <td><?= $item->surname ?></td>
            <td><?= $item->phone ?></td>
            <td><?= $item->email ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
