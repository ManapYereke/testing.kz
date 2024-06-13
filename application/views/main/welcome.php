<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jadi</title>
    <link rel="stylesheet" href="/css/homestyle.css">
    <script   src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/fc-3.3.0/fh-3.1.6/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.12/js/bootstrap-select.min.js" integrity="sha256-+o/X+QCcfTkES5MroTdNL5zrLNGb3i4dYdWPWuq6whY=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.12/js/i18n/defaults-ru_RU.min.js" integrity="sha256-IylVxjD4l0kmppHSTNv4H5aZdeG05YQNN8Mz/Z2jJFM=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<!-- Optional: include a polyfill for ES6 Promises for IE11 -->
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>

<script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ru.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js" integrity="sha256-AdQN98MVZs44Eq2yTwtoKufhnU+uZ7v2kXnD5vqzZVo=" crossorigin="anonymous"></script>

<script src="https://kit.fontawesome.com/9f3223bcef.js" crossorigin="anonymous"></script>

<script type="text/javascript" src="/js/jquery.table.marge.js"></script>
<script type="text/javascript" src="/js/utils.js"></script>
<script>
	$(function(){
		$.each(ONLOAD,function(x,y){ y(); })

        $('.date').flatpickr({
            enableTime: false,
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d.m.Y",
            locale: { "firstDayOfWeek": 1 },
            locale: "ru"
        });
        $('.time').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            locale: { "firstDayOfWeek": 1 },    
            locale: "ru"
        });
        $('.datetime').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "d.m.Y H:i",
            locale: { "firstDayOfWeek": 1 },
            locale: "ru"
        });
        $('.time').flatpickr({locale: "ru", format: 'LT'});
        
        $("input[type=tel]").mask("+7 (000) 000-0000");
		$("form").submit(function(){$(this).find('input').unmask();});
	});
</script>
</head>
<body>
    <!-- Header start -->
    <header class="header">
        <div class="wrapper">
            <div class="header__wrapper">
                <div class="header__logo">
                    <a href="https://ksacollege.edu.kz/rus" class="header__logo-link">
                        <img src="/img/logo_ksa.svg" alt="" class="header__logo-pic">
                    </a>
                </div>
                <div class="language-switcher">
                    <button onclick="switchLanguage('ru')">RU</button>
                    <button onclick="switchLanguage('kz')">KZ</button>
                </div>
            </div>
        </div>
    </header>
    <!-- Header end -->

    <main class="main">
        <!-- Intro start -->
        <section class="intro" id="sign-in">
            <div class="wrapper">
                <h1 class="intro__title">
                    Оценка уровня знаний абитуриента для поступления в колледж!
                </h1>
                <p class="intro__subtitle">
                    Платформа тестирования, которая позволяет абитуриентам проверять свои способности по специальности для поступления в колледж
                </p>
                <form class="search-form">
                    <fieldset class="search-form__wrap">
                        <p class="info">
                            <input type="text" class="field" placeholder="Программное обеспечение" onclick="openModal('modal1')">
                            <input type="text" class="field" placeholder="Переводческое дело" onclick="openModal('modal2')">
                            <button type="button" class="submit" onclick="startTest()">Начать тест</button>
                        </p>
                    </fieldset>
                </form>
            </div>
        </section>
        <!-- Intro end -->

        <!-- Modal 1 -->
        <div id="modal1" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modal1')">&times;</span>
                <div id="h2title" class="h2">
                    <div class="h2title">
                <h2>Программное обеспечение</h2>
                <ul id="ultitle" class="ul">
                    <ul class="ultitle">
                    <li>• Информатика</li>
                    <li>• Физика</li>
                    <li>• Математика</li>
                </ul>
                </ul>
            </div>
                </div>
            </div>
        </div>

        <!-- Modal 2 -->
        <div id="modal2" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modal2')">&times;</span>
                <div id="h2title" class="h2">
                    <div class="h2title">
                <h2>Переводческое дело</h2>
                <ul id="ultitle" class="ul">
                    <ul class="ultitle">
                    <li>• Казахский язык</li>
                    <li>• Русский язык</li>
                    <li>• Английский язык</li>
                </ul>
                </ul>
            </div>
        </div>
            </div>
        </div>
    </main>

    <script src="/js/main-es5.min.js"></script>
</body>
</html>