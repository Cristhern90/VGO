//alert('http://' + window.location.hostname + '/VGO2/includes/ajax/');

$.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '< Ant',
    nextText: 'Sig >',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    weekHeader: 'Sm',
    dateFormat: 'yy-mm-dd',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['es']);
$(function () {
    $("#datepicker").datepicker();
    $(".datepicker").datepicker();
    $('.datetimepicker').datetimepicker();
});
function conenct_post(page, fun, id) {
    var myArray = new FormData();
    myArray.append("funcion", fun);
    myArray.append("id", id);
    var ret = "";
    $.ajax({
        url: 'http://' + window.location.hostname + '/VGO2/includes/ajax/' + page + ".php", type: 'POST', data: myArray, cache: false, contentType: false, processData: false, async: false, beforeSend: function () {},
        complete: function () {
//            $("#loading").hide();
        },
        success: function (data) {
            console.log(data);
            try {
                var resp = $.parseJSON(data);
                ret = resp;
                if (resp.alert) {
                    alert(resp.msg);
                }
                if (resp.location) {
                    location.href = resp.location;
                }
                if (resp.reload) {
                    location.reload();
                }
            } catch (e) {
                alert("Error inesperado: contacte con Venfil");
            }
        },
        error: function (e) {
            alert("error: ".e);
        }
    });
    return ret;
}

function conenct_post_arr(page, fun, arr) {
    var myArray = new FormData();
    myArray.append("funcion", fun);
    var count = 1;
    for (var i = 0; i < arr.length; i++) {
        myArray.append("id" + count, arr[i]);
        count++;
    }
    var ret = conenction_post(page, myArray);
    return ret;
}

function conenction_post(page, myArray) {
    var ret = "";
    $.ajax({
        url: 'http://' + window.location.hostname + '/VGO2/includes/ajax/' + page + ".php", type: 'POST', data: myArray, cache: false, contentType: false, processData: false, async: false, beforeSend: function () {},
        complete: function () {
//            $("#loading").hide();
        },
        success: function (data) {
            console.log(data);
            try {
                var resp = $.parseJSON(data);
                ret = resp;
                if (resp.alert) {
                    alert(resp.msg);
                }
                if (resp.location) {
                    location.href = resp.location;
                }
                if (resp.reload) {
                    location.reload();
                }
            } catch (e) {
                alert("Error inesperado: contacte con Venfil");
            }
        },
        error: function (e) {
            alert("error: ".e);
        }
    });
    return ret;
}

function conenct_post_obj(page, fun, obj) {
    $("#loading").show();
    var myArray = new FormData();
    myArray.append("funcion", fun);
    myArray.append("dates", obj);
    var ret = "";
    $.ajax({
        url: 'http://' + window.location.hostname + '/VGO2/includes/ajax/' + page + ".php", type: 'POST', data: myArray, cache: false, contentType: false, processData: false, async: false, beforeSend: function () {},
        complete: function () {
//            $("#loading").hide();
        },
        success: function (data) {
            console.log(data);
            try {
                var resp = $.parseJSON(data);
                ret = resp;
                if (resp.alert) {
                    alert(resp.msg);
                }
                if (resp.location) {
                    location.href = resp.location;
                }
                if (resp.reload) {
                    location.reload();
                }
            } catch (e) {
                alert("Error inesperado: contacte con Venfil");
            }
        },
        error: function (e) {
            alert("error: ".e);
        }
    });
    return ret;
}
