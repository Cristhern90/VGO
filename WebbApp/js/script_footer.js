//function conenct_post(page, fun, id) {
//    var myArray = new FormData();
//    myArray.append("funcion", fun);
//    myArray.append("id", id);
//    var ret = "";
//    $.ajax({
//        url: 'http://localhost/VGO2/includes/ajax/' + page + ".php", type: 'POST', data: myArray, cache: false, contentType: false, processData: false, async: false, beforeSend: function () {},
//        complete: function () {
////            $("#loading").hide();
//        },
//        success: function (data) {
//            console.log(data);
//            try {
//                var resp = $.parseJSON(data);
//                ret = resp;
//                if (resp.alert) {
//                    alert(resp.msg);
//                }
//                if (resp.location) {
//                    location.href = resp.location;
//                }
//                if (resp.reload) {
//                    location.reload();
//                }
//            } catch (e) {
//                alert("Error inesperado: contacte con Venfil");
//            }
//        },
//        error: function (e) {
//            alert("error: ".e);
//        }
//    });
//    return ret;
//}
//
//function conenct_post_arr(page, fun, arr) {
//    var myArray = new FormData();
//    myArray.append("funcion", fun);
//    var count = 1;
//    for (var i = 0; i < arr.length; i++) {
//        myArray.append("id" + count, arr[i]);
//        count++;
//    }
//    var ret = conenction_post(page, myArray);
//    return ret;
//}
//
//function conenction_post(page, myArray) {
//    var ret = "";
//    $.ajax({
//        url: 'http://localhost/VGO2/includes/ajax/' + page + ".php", type: 'POST', data: myArray, cache: false, contentType: false, processData: false, async: false, beforeSend: function () {},
//        complete: function () {
////            $("#loading").hide();
//        },
//        success: function (data) {
//            console.log(data);
//            try {
//                var resp = $.parseJSON(data);
//                ret = resp;
//                if (resp.alert) {
//                    alert(resp.msg);
//                }
//                if (resp.location) {
//                    location.href = resp.location;
//                }
//                if (resp.reload) {
//                    location.reload();
//                }
//            } catch (e) {
//                alert("Error inesperado: contacte con Venfil");
//            }
//        },
//        error: function (e) {
//            alert("error: ".e);
//        }
//    });
//    return ret;
//}
//
//function conenct_post_obj(page, fun, obj) {
//    $("#loading").show();
//    var myArray = new FormData();
//    myArray.append("funcion", fun);
//    myArray.append("dates", obj);
//    var ret = "";
//    $.ajax({
//        url: 'http://localhost/VGO2/includes/ajax/' + page + ".php", type: 'POST', data: myArray, cache: false, contentType: false, processData: false, async: false, beforeSend: function () {},
//        complete: function () {
////            $("#loading").hide();
//        },
//        success: function (data) {
//            console.log(data);
//            try {
//                var resp = $.parseJSON(data);
//                ret = resp;
//                if (resp.alert) {
//                    alert(resp.msg);
//                }
//                if (resp.location) {
//                    location.href = resp.location;
//                }
//                if (resp.reload) {
//                    location.reload();
//                }
//            } catch (e) {
//                alert("Error inesperado: contacte con Venfil");
//            }
//        },
//        error: function (e) {
//            alert("error: ".e);
//        }
//    });
//    return ret;
//}
