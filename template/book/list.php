<div class="col col-sm-12">
    <div class="form-group">
        <button class="btn btn-primary" id="open-form">Добавить</button>
    </div>
</div>
<div class="col col-sm-12">
<table class="table table-bordered table-striped">
    <thead>
        <th>№</th>
        <th>Имя</th>
        <th>Фамилия</th>
        <th>Телефон</th>
        <th>Почта</th>
        <th>Изображение</th>
        <th>#</th>
    </thead>
    <tbody id="item-list">
    </tbody>
</table>
</div>

<div class="modal" tabindex="-1" role="dialog" id="itemForm">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="form-item">
                    <div class="form-group">
                        <label>Имя</label>
                        <input id="name" type="text" name="Item[name]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Фамилия</label>
                        <input id="surname" value="" type="text" name="Item[surname]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Телефон</label>
                        <input id="phone" type="text" name="Item[phone]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Почта</label>
                        <input id="email" type="email" name="Item[email]" class="form-control">
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image">
                            <label class="custom-file-label" for="image">Выбрать файл</label>
                        </div>
                        <div class="preview">
                            <img id="image" src="" class="preview-image">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" id="save-item">Сохранить</button>
            </div>
        </div>
    </div>
</div>
