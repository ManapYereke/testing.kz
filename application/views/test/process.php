<?php
defined('BASEPATH') or exit('No direct script access allowed');
//Автор-Ербұлан
//Дата-15.03.2023
//Оптимизированная версия
?>
<? $this->load->view("shared/header", $this->_ci_cached_vars);?>

<?
$tb0101_id = $this->uri->segment(3);
?>

<!-- <h1><?= $this->session->userdata("lang") ?></h1> -->
<div class="container">
    <form method="post" action="<?= site_url($this->uri->segment(1) . "/save") ?>">
        <input type="hidden" name="tb0101_id" id="tb0101_id" value="<?= $tb0101_id ?>">
        <?
        $step = 0;
        $questions = array();
        $navlinks = array();
        foreach ($tb0301 as $r1) {
            $tb0302 = $this->db->query("SELECT * FROM tb0302_questions WHERE tb0302_tb0301_id=?", [$r1->tb0301_id])->result();
            shuffle($tb0302);
            $step++;
            $i = 0;
        ?>
            <div id="tb0301-<?= $r1->tb0301_id ?>" class="card mt-2 step<?= $step == 1 ? "" : " d-none" ?>" <?//tb0301_timelimit="<?= $r1->tb0301_timelimit ?>>
                <h5 class="card-header"><?= $this->utils->t([$r1->tb0301_name_ru, $r1->tb0301_name_kz]) ?> <span class="badge badge-secondary">00:00</span></h5>
                <div class="card-body">
                    <div class="intro">
                        <?= $this->utils->t([$r1->tb0301_desc_ru, $r1->tb0301_desc_kz]) ?>
                        <div class="text-center">
                            <button onclick="stepStart(this)" type="button" class="btn btn-success btn-lg"><?= $lang["begin"] ?></button>
                        </div>
                    </div>
                    <div class="questions d-none">
                        <nav>
                            <div class="nav nav-pills" id="nav-tab" role="tablist">
                                <?
                                foreach ($tb0302 as $r2) {
                                    $i++;
                                ?>
                                    <a class="nav-item nav-link <?= $tb0302[0] == $r2 ? "active" : "" ?>" id="tab-<?= $r2->tb0302_id ?>-title" data-toggle="tab" href="#tab-<?= $r2->tb0302_id ?>" name="question link" role="tab" aria-selected="true" onclick="selectQuestion(<?= $step ?>,<?= $i ?>)"><?= $i ?></a>
                                <? }
                                $questions[$step - 1] = $i;
                                $navlinks[$step - 1] = $i;
                                if ($step > 1)
                                    $questions[$step - 1] += $questions[$step - 2];
                                ?>
                            </div>
                        </nav>
                        <div class="tab-content pt-2" id="nav-tabContent">
                            <? foreach ($tb0302 as $r2) { ?>
                                <div class="tab-pane fadeshow <?= $tb0302[0] == $r2 ? "active" : "" ?>" id="tab-<?= $r2->tb0302_id ?>" role="tabpanel">
                                    <div class="alert alert-info">
                                        <strong><?= $lang["question"] ?></strong>
                                        <br><?= $this->utils->t([$r2->tb0302_desc_ru, $r2->tb0302_desc_kz]) ?>
                                    </div>

                                    <table class="table table-striped table-hover">
                                        <?
                                        for ($x = 1; $x <= 5; $x++) {
                                            $f = "tb0302_answer" . $x . "_" . $this->utils->l();
                                            if (!$r2->$f) continue;
                                        ?>
                                            <tr>
                                                <th width="1%"><input type="radio" name="tb0302_id-<?= $r2->tb0302_id ?>" value="<?= $x ?>" onclick="atLeastOneRadio()"></th>
                                                <td>
                                                    <?= $r2->$f ?>
                                                </td>
                                            </tr>
                                        <? } ?>
                                    </table>
                                </div>
                            <? } ?>
                        </div>
                    </div>

                </div>
            </div>
        <? }
        $questionArray = json_encode($questions);
        $navArray = json_encode($navlinks); ?>
        <div class="card-footer text-right d-none" id="card-footer buttons">
            <button id="btGoToNextSubTest" type="button" onclick="stepEnd()" class="btn btn-secondary d-none"><?= $lang["finish"] ?></button>
            <button id="btGoToNextQuestion" type="button" onclick="nextQuestion()" class="btn btn-secondary"><?= $lang["nextQuestion"] ?></button>
            <button id="btGoToFinish" type="button" onclick="finish()" class="btn btn-warning d-none"><?= $lang["finishTest"] ?></button>
        </div>
    </form>
</div>

<script>
    var STEPMAX = <?= $step ?>;


    function finish(force = 0) {
        if (force) {
            $("form").submit();
            return;
        }

        Swal.fire({
            title: '<?= $lang["youSure"] ?>',
            text: '<?= $lang["sureSubmit"] ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?= $lang["sure"] ?>',
            cancelButtonText: '<?= $lang["cancel"] ?>'
        }).then((result) => {
            if (result.value) {
                $("form").submit();
            } else Swal.fire({
                icon: 'error',
                title: '<?= $lang["canceled"] ?>',
                text: '<?= $lang["finishingCanceled"] ?>'
            });
        });
    }

    var countDown;

    function stepStart(o) {
        var card = $(o).parents(".card");
        card.find(".intro").hide();
        card.find(".questions").removeClass("d-none").show();
        $('#btGoToNextQuestion').removeClass('d-none');
        //$('#card-footer buttons').removeClass('d-none');
        document.getElementById('card-footer buttons').classList.remove('d-none');
        var step = parseInt(card.attr("step"));

        // Set the date we're counting down to
        /*var tb0301_timelimit = card.attr("tb0301_timelimit") + ":00";
        card.find(".card-header").find(".badge").text(tb0301_timelimit);

        // Update the count down every 1 second
        countDown = setInterval(function() {
            var timer = card.find(".card-header").find(".badge").text().split(':');

            //by parsing integer, I avoid all extra string processing
            var minutes = parseInt(timer[0], 10);
            var seconds = parseInt(timer[1], 10);
            --seconds;
            minutes = (seconds < 0) ? --minutes : minutes;
            minutes = (minutes < 10) ? '0' + minutes : minutes;

            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            //minutes = (minutes < 10) ?  minutes : minutes;
            $('.countdown').html(minutes + ':' + seconds);

            card.find(".card-header").find(".badge").text(minutes + ':' + seconds);

            // If the count down is finished, write some text 

            if (minutes <= 0 && seconds <= 0) {
                clearInterval(countDown);
                if (step != STEPMAX) {
                    console.log("[GOTO NEXT STEP / FORCED] " + (step + 1) + " / " + STEPMAX);
                    stepEnd();
                } else finish(1);
            }
        }, 1000);*/
    }

    function stepEnd() {
        $('#btGoToNextSubTest').addClass('d-none');
        $('.nav-item.nav-link.active').eq(0).removeClass('active');
        $('.tab-pane.fadeshow.active').eq(0).removeClass('active');

        Swal.fire({
            title: "Вы уверены?",
            text: "Вы уверены, что хотите завершить текущее задание и перейти дальше?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Да, уверен (-а)!",
            cancelButtonText: "Отменить!"
        }).then((result) => {
            if (result.value) {
                var steps = $('.card.mt-2.step');
                for (var i = 0; i < steps.length - 1; i++) {
                    if ($(steps[i]).is('.card.mt-2.step') && !$(steps[i]).is('.card.mt-2.step.d-none')) {
                        $(steps[i]).addClass('d-none');
                        $(steps[i + 1]).removeClass('d-none');
                        break;
                    }
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: "Отменено",
                    text: "Завершение отменено."
                });
            }
        });

        return false;
    }


    function nextQuestion() {
        var array = JSON.parse('<?php echo $questionArray; ?>');
        var questions = $('.nav-item.nav-link');
        var contents = $('.tab-pane.fadeshow');
        var btGoToNextQuestion = $('#btGoToNextQuestion');
        var btGoToNextSubTest = $('#btGoToNextSubTest');
        var btGoToFinish = $('#btGoToFinish');
        for (var i = 0; i < questions.length - 1; i++) {
            if (i === questions.length - 2) {
                btGoToNextQuestion.addClass('d-none');
                btGoToFinish.removeClass('d-none');
            } else if (array.includes(i + 2)) {
                btGoToNextQuestion.addClass('d-none');
                btGoToNextSubTest.removeClass('d-none');
            } else if (array.includes(i + 1)) {
                btGoToNextQuestion.removeClass('d-none');
                btGoToNextSubTest.addClass('d-none');
            } else {
                btGoToNextQuestion.removeClass('d-none');
                btGoToNextSubTest.addClass('d-none');
                btGoToFinish.addClass('d-none');
            }
            if (questions.eq(i).hasClass('active')) {
                questions.eq(i).removeClass('active');
                contents.eq(i).removeClass('active');
                questions.eq(i + 1).addClass('active');
                contents.eq(i + 1).addClass('active');
                break;
            }
        }
    }


    function atLeastOneRadio() {
        var nav_links = $(".nav-item.nav-link.active");
        nav_links.eq(0).attr("name", "question link selected");
    }

    function selectQuestion(s, q) {
        var array = JSON.parse('<?php echo $navArray; ?>');
        var btGoToNextQuestion = $('#btGoToNextQuestion');
        var btGoToNextSubTest = $('#btGoToNextSubTest');
        var btGoToFinish = $('#btGoToFinish');
        if (s === array.length && q === array[array.length - 1]) {
            btGoToNextQuestion.addClass('d-none');
            btGoToFinish.removeClass('d-none');
        } else if (array[s - 1] === q) {
            btGoToNextQuestion.addClass('d-none');
            btGoToNextSubTest.removeClass('d-none');
        } else {
            btGoToNextQuestion.removeClass('d-none');
            btGoToNextSubTest.addClass('d-none');
            btGoToFinish.addClass('d-none');
        }
    }
</script>
<? $this->load->view("shared/footer", $this->_ci_cached_vars); ?>