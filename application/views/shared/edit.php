<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<? $this->load->view("/shared/header", $this->_ci_cached_vars) ?>
<div class="container-fluid">
    <div class="page-header animated slideInDown">
        <h1 class="text-muted discount-card-text">
            <span class="glyphicon glyphicon-pencil"></span> <?= $title ?>
        </h1>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="container">
                <div class="">
                    <form id="dataform" onkeypress="return event.keyCode!=13;" enctype="multipart/form-data">
                        <? $this->load->view($this->uri->segment(1) . "/form", $this->_ci_cached_vars) ?>
                        <hr>
                        <div class="form-group">
                            <a class="btn btn-secondary" href="<?= site_url($this->uri->segment(1)) ?>">
                                <i class="fas fa-undo-alt"></i> Отменить и вернуться
                            </a>
                            <button class="btn btn-success" type="button" onclick="save(this,2)">
                                <i class="far fa-save"></i> Сохранить и перейти к списку
                            </button>
                            <? if ($this->uri->segment(3)) { ?>
                                <button class="btn btn-danger pull-right" type="button" onclick="del()">
                                    <span class="glyphicon glyphicon-trash"> <?= "Удалить" ?>
                                </button>
                            <? } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var dosave = 0;

    function save(btn, type) {
        dosave = type;
        $(btn).attr("disabled", "");
        $("#dataform").submit();
        setTimeout(function() {
            $(btn).removeAttr("disabled")
        }, 2000);
    }

    function del() {
        Swal.fire({
            title: "Вы уверены?",
            text: "Вы уверены, что хотите удалить запись?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Да, уверен (-а)!",
            cancelButtonText: "Отменить!",
        }).then((result) => {
            if (result.value) {
                sendAsPost({
                    url: "<?= site_url($this->uri->segment(1) . "/" . "del/" . $this->uri->segment(3)) ?>",
                    title: "Удаление",
                    data: {
                        "<?= $id ?>": "<?= $this->uri->segment(3) ?>"
                    },
                    callbackOk: function() {
                        location.href = "<?= site_url($this->uri->segment(1)) ?>"
                    }
                });
            } else Swal.fire({
                icon: 'error',
                title: "Отменено",
                text: "Удаление отменено."
            });
        });
        return false;
    }

    ONLOAD.push(function() {
        $("#dataform").submit(function() {
            // tinyMCE.triggerSave();
            // if(!$("#dataform").valid()) return false;

            sendAsPost({
                url: "<?= site_url($this->uri->segment(1) . "/save") ?>",
                formData: new FormData(this),
                title: "Сохранение",
                callbackOk: function(data) {
                    if (dosave == 1) location.href = "/<?= $this->uri->segment(1) ?>/view/" + data.id + "?draft=1";
                    if (dosave == 2) location.href = "<?= site_url($this->uri->segment(1)) ?>";
                }
            });
            return false;
        });

    });
</script>
<? $this->load->view("/shared/footer", $this->_ci_cached_vars) ?>