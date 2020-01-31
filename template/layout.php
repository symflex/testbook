<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sticky Footer Template · Bootstrap</title>
    <link href="/bundle/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bundle/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
<div class="container">

    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <h5 class="my-0 mr-md-auto font-weight-normal"><a href="/">book</a></h5>
        <?php if ($user_id): ?>
            <div class="user-email">
                <i class="fa fa-user"></i>
                <?= $user_email ?>
            </div>
            <a href="/sign-out" class="btn btn-primary"> Выйти</a>
        <?php else: ?>
            <a href="/sign-up" class="btn btn-primary">Регистрация</a>&nbsp;
            <a href="/sign-in" class="btn btn-primary">Войти</a>
        <?php endif; ?>
    </div>

    <?php if ($this->isMessage()): ?>
        <section>
            <div class="alert alert-success">
                <?= $this->getMessage() ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </section>
    <?php endif; ?>

    <section>
    <?= $content ?>
    </section>
</div>
<script src="/bundle/jquery.min.js"></script>
<script src="/bundle/bootstrap/js/bootstrap.min.js"></script>
<script src="/bundle/jquery-validate/jquery.validate.min.js"></script>
<script src="/bundle/jquery-validate/additional-methods.min.js"></script>
<script src="/bundle/jquery-validate/localization/messages_ru.min.js"></script>
<script src="/js/book.js"></script>
</body>
</html>
