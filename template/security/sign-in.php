<div class="form-login">
    <form method="post">
        <div class="form-group">
            <label>Почта</label>
            <input type="email" name="email" value="<?= isset($email) ? $email : '' ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Пароль</label>
            <input type="password" name="plainPassword" class="form-control" required pattern="[a-zA-Z0-9]+">
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Войти</button>
        </div>
        <div class="form-group">
            <?php if (isset($error)): ?>
                <?= $error ?>
            <?php endif; ?>
        </div>
    </form>

</div>
