function reload_more(obj, obj2 = false, new_tab = false, sustitute = false) {
    if (sustitute) {
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    console.log(urlParams);
    for (var [key, value] of Object.entries(obj)) {
        if (urlParams.get(key)) {
            if (value == "") {
                urlParams.delete(key);
            } else {
                urlParams.set(key, value);
            }
        } else {
            urlParams.append(key, value);
        }
    }
    if (obj2) {
        for (var [key2, value2] of Object.entries(obj2)) {
            if (urlParams.get(key2)) {
                urlParams.delete(key2);
            }
        }
    }

    var href = "index.php?" + urlParams.toString();

    if (new_tab) {
        window.open(href);
    } else {
        location.href = href;
}
}

function conect(page, myArray) {
    var str = false;
    $.ajax({
        url: page,
        type: 'POST',
        data: myArray,
        cache: false,
        contentType: false,
        processData: false,
        async: false,
        beforeSend: function () {},
        complete: function () {
        },
        success: function (data) {
            try {
                console.log(data);
                var obj = JSON.parse(data);
                if (obj.errorCode) {
                    if (obj.errorCode == 1) {
                        alert("Se ha producido un error en la conección con la BBDD, contacta con el programador");
                    } else if (obj.errorCode == 2) {
                        alert("Se ha producido un error en la ejecución de un script, contacta con el programador");
                    } else {
                        alert("Se ha producido un error en la función php, contacta con el programador");
                    }
                    if (obj.alert) {
                        alert(obj.response);
                    }
                } else {
                    if (obj.alert) {
                        alert(obj.response);
                        if (obj.reload) {
                            location.reload();
                        }
                        if (obj.newLocation) {
                            location.href = obj.newLocation;
                        }
                    } else {
                        if (obj.reload) {
                            location.reload();
                        }
                        if (obj.newLocation) {
                            location.href = obj.newLocation;
                        }
                    }
                }
                str = obj;
            } catch (e) {
                console.log(data);
                console.log(e);
                alert("Los datos retornados no son correctos");
            }
        },
        error: function (e) {
            console.log(e);
            alert("Se ha producido un error de conección con: " + page);
        }
    });
    return str;
}