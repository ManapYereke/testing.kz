<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<? $this->load->view("shared/header", $this->_ci_cached_vars); ?>
<div class="row login">
    <div class="col-4"></div>
    <div class="col-4">
        <h1 class="display-5 text-center">АВТОРИЗАЦИЯ</h1>
        <form class="form-lg" id="authForm">
            <!-- <a class="pr-2" href="<?= site_url("user/registration") ?>">Регистрация</a>
		<a class="pr-2" href="<?= site_url("user/recovery") ?>">Забыли пароль?</a> -->
            <br><br>
            <input type="text" class="form-control mr-sm-2" data-mask="000 000 000 000" id="tb0101_idn" name="tb0101_idn" placeholder=<?=$lang["idn"]?>>
            <br>
            <input type="password" class="form-control mr-sm-2" id="tb0101_passwd" name="tb0101_passwd" placeholder="Пароль">
            <br>
            <button class="btn btn-success form-control"><?=$lang["to_come_in"]?></button>
        </form>
    </div>
    <div class="col-4"></div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#authForm').submit(function(e) {
            e.preventDefault(); // Предотвращаем отправку формы по умолчанию
            var formData = $(this).serialize(); // Сериализуем данные формы
            $.ajax({
                url: '<?php echo site_url("user/login"); ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        window.location.href = '<?php echo site_url("user/lst"); ?>'; // Перенаправление на страницу панели управления
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>
<? $this->load->view("shared/footer", $this->_ci_cached_vars); ?>