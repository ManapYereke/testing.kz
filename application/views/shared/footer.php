</div>
 <!-- // container-fluid -->
<!-- <footer class="bg-dark w-100 fixed-bottom" style="height:100px">
	<div class="row">
		<div class="col">123
		</div>
	</div>
</footer> -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
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

<?if($this->uri->segment(2)=="add"||$this->uri->segment(2)=="edit"){?>
<!-- <script src="https://cdn.tiny.cloud/1/sm3cx4xklpdu7lr5u5owaikez2lxzs475ocd6uopl1p1h0ay/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->
<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<?}?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js" integrity="sha256-AdQN98MVZs44Eq2yTwtoKufhnU+uZ7v2kXnD5vqzZVo=" crossorigin="anonymous"></script>

<script src="https://kit.fontawesome.com/9f3223bcef.js" crossorigin="anonymous"></script>

<script type="text/javascript" src="/js/jquery.table.marge.js"></script>
<script type="text/javascript" src="/js/utils.js"></script>

<div class="modal fade" id="pleasewait" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header"><h4 class="modal-title">Пожалуйста, ожидайте</h4></div>
            <div class="modal-body">
                <i class="fa fa-cog fa-spin fa-2x"></i>&nbsp;
               Пожалуйста ожидайте, идет отправка/запрос данных...
            </div>
        </div>
    </div>
</div>

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
</body>
</html>