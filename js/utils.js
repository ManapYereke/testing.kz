var DT_LANG = 
	{
		"processing": "Подождите...",
		"search": "Поиск:",
		"lengthMenu": "Показать _MENU_ записей",
		"info": "Записи с _START_ до _END_ из _TOTAL_ записей",
		"infoEmpty": "Записи с 0 до 0 из 0 записей",
		"infoFiltered": "(отфильтровано из _MAX_ записей)",
		"infoPostFix": "",
		"loadingRecords": "Загрузка записей...",
		"zeroRecords": "Записи отсутствуют.",
		"emptyTable": "В таблице отсутствуют данные",
		"paginate": {
			"first": "Первая",
			"previous": "Предыдущая",
			"next": "Следующая",
			"last": "Последняя"
		}
	}

var $summernote;
// function SummernoteImageUpload(file, editor, welEditable) {
function SummernoteImageUpload(file, editor) {
	$summernote=$($summernote);
	console.log($summernote);

	var getUrl = window.location;
	var baseUrl = getUrl .protocol + "//" + getUrl.host; // + "/" + getUrl.pathname.split('/')[1];

	data = new FormData();
	data.append("file", file);
	$.ajax({
		data: data,
		type: "POST",
		url: baseUrl+"/SummernoteImageUpload.php",
		cache: false,
		contentType: false,
		processData: false,
		success: function(url) {
			var imgNode = $('<img>').attr('src', url);
            // console.log("[SummernoteImageUpload] "+url);
            $summernote.summernote('insertImage', url)
            // $summernote.summernote('insertNode', imgNode)
		},
        error: function(data) {
            console.log(data);
        }
	});
}

function load(args) {
	var args = $.extend({
		id: ""
		, url: ""
		, method: "GET"
		, data: {}
		, callback: null
	}, args);

	var o = $(args.id)

	console.log(JSON.stringify(args.data, null, "\t"));

	if (!o.length) {
		console.log("LOAD: Object [" + args.id + "] not found.");
		return false
	}

	if (o.parent().find(".progress-title").length) return;

	var title = o.attr("title") ? o.attr("title") : o.attr("placeholder");
	//if (o.prop("tagName") == "SELECT") o.selectpicker('destroy');
	o.hide();
	if (title) o.parent().append('<div class="div text-muted progress-title">' + title + '</div>');
	o.parent().append('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div>');

	args.url += (args.url.indexOf("?") > 1 ? "&" : "?") + "__rnd=" + Math.random();

	if (args.method == "GET") {
		return $.get(args.url, args.data, function (raw) {
			var data = jsonRaw2Obj(raw);
			o.parent().find(".progress-title, .progress").remove();

			if (data.error) {
				Swal.fire({
					icon: 'error',
					title: title,
					text: data.error
				});
				return;
			}

			args.callback(args, data);
		}).fail(function (jqXHR, textStatus, errorThrown) {
			o.parent().find(".progress-title, .progress").remove();
			Swal.fire({
				icon: 'error',
				title: title,
				text: errorThrown
			});
		});
	}

	return $.post(args.url, args.data, function (raw) {
		var data = jsonRaw2Obj(raw);

		$(args.id).parent().find(".progress-title, .progress").remove();

		if (data.error) {
			Swal.fire({
				icon: 'error',
				title: title,
				text: data.error
			});
			return;
		}

		args.callback(args, data);
	}).fail(function (jqXHR, textStatus, errorThrown) {
		console.log("qweqweqwe");
		$(args.id).parent().find(".progress-title, .progress").remove();
		Swal.fire({
			icon: 'error',
			title: title,
			text: errorThrown
		});
	});
}

function jsonRaw2Obj(raw) {
	raw = raw ? raw : "";
	var data;
	try {
		if (typeof raw === "object") {
			data = raw;
		} else {
			data = $.parseJSON(raw);
		}
	} catch (ex) {
		data = { error: ex };
	}
	return data;
}


function div(a, b) { // деление нацело
	var x = a / b;
	x = Math.round(x);

	var y = x * b;
	x -= y > a ? 1 : 0;

	return x;
}

function arraysEqual(a, b) {
	if (a === b) return true;
	if (a === null || b === null) return false;
	if (a.length !== b.length) return false;

	// If you don't care about the order of the elements inside
	// the array, you should sort both arrays here.

	for (var i = 0; i < a.length; ++i) {
		if (a[i] !== b[i]) return false;
	}
	return true;
}

function encodeHTML(s) {
	if(s){
		return s.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&apos;');
	}else{
		return '';
	}
}

function decodeHTML (s) {
	if(s){
        return s.replace(/&apos;/g, "'")
                   .replace(/&quot;/g, '"')
				   .replace(/&gt;/g, '>')
				   .replace(/&lt;/g, '<')
				   .replace(/&amp;/g, '&');
   }else{
		return '';
	}
}

String.prototype.replaceAll = function (search, replacement) {
	var target = this;
	return target.replace(new RegExp(search, 'g'), replacement);
};

function sendAsGet(args) {
	var args = $.extend({
		url: ""
		, data: ""
		, title: ""
		, callbackOk: null
		, callbackError: null
		, showOkMsg: true
		, showErrMsg: true
		, showWaitMsg: true
	}, args);

	if (args.showWaitMsg) $("#pleasewait").modal("show");
	$.get(args.url, args.data, function (raw) {
		if (args.showWaitMsg) $("#pleasewait").modal("hide");
		var data = jsonRaw2Obj(raw);
		$('.modal-backdrop').remove();

		if (data.error) {

			if (args.showErrMsg) Swal.fire({icon:'error',title:args.title,text:data.error});
			if (args.callbackError) args.callbackError(data);
			return;
		}

		if (data.redirect) {
			location.href = data.redirect
			return;
		}

        if (args.showOkMsg) Swal.fire({icon:'success',title:args.title,text:data.result});
		if (args.callbackOk) args.callbackOk(data);
	}).fail(function (request, status, error) {
		$("#pleasewait").modal("hide");
        if (args.showErrMsg) Swal.fire({icon:'error',title:args.title,text:error});
		if (args.callbackError) args.callbackError(data)
	});
	return false;
}

function sendAsPost(args) {
	var args = $.extend({
		url: ""
		, data: ""
        , formData: ""
		, title: ""
		, callbackOk: null
		, callbackError: null
		, showOkMsg: true
		, showErrMsg: true
		, showWaitMsg: true
	}, args);

	if (args.showWaitMsg) $("#pleasewait").modal("show");

    if (args.formData) {
        $.ajax({
            url: args.url
            , type: 'POST'
            , cache: false
            , data: args.formData
            , processData: false
            , contentType: false
            , success: function (raw) {
                if (args.showWaitMsg) $("#pleasewait").modal("hide");
                var data = jsonRaw2Obj(raw);
                $('.modal-backdrop').remove();

                if (data.error) {
                    if (args.showErrMsg) Swal.fire({icon:'error',title:args.title,text:data.error});
                    if (args.callbackError) args.callbackError(data);
                    return;
                }

                if (data.redirect) {
                    location.href = data.redirect
                    return;
                }

                if (args.showOkMsg) Swal.fire({icon:'success',title:args.title,text:data.result});
                if (args.callbackOk) args.callbackOk(data);
                return false
            }
            , error: function (request, status, error) {
                $("#pleasewait").modal("hide");
                if (args.showErrMsg) Swal.fire({icon:'error',title:args.title,text:error});
                if (args.callbackError) args.callbackError(data)
                return false
            }
        })
        return false;
    } else if (args.data) {
        $.post(args.url, args.data, function (raw) {
            if (args.showWaitMsg) $("#pleasewait").modal("hide");
            var data = jsonRaw2Obj(raw);
            $('.modal-backdrop').remove();

            if (data.error) {
                if (args.showErrMsg) Swal.fire({icon:'error',title:args.title,text:data.error});
                if (args.callbackError) args.callbackError(data);
                return;
            }

            if (data.redirect) {
                location.href = data.redirect
                return;
            }

            if (args.showOkMsg) Swal.fire({icon:'success',title:args.title,text:data.result});
            if (args.callbackOk) args.callbackOk(data);
        }).fail(function (request, status, error) {
            $("#pleasewait").modal("hide");
            if (args.showErrMsg) Swal.fire({icon:'error',title:args.title,text:error});
            if (args.callbackError) args.callbackError(data)
        });
        return false;
    }
}