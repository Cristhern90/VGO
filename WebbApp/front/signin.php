<h2>Registro de usuario</h2>
<br>
<form id="sign">

    <input type="hidden" name="funcion" value="signin">
    <div class="row text-center">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    Nombre de usuario<span style="color: red">*</span>
                </div>
                <div class="col-md-6">
                    <input type="text" name="nombreUsuario" required="">
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="col-md-6 col-sm-12">
            <div class="row">
                <div class="col-md-6">
                    Nombre Completo<span style="color: red">*</span>
                </div>
                <div class="col-md-6">
                    <input type="text" name="nombreCompleto" required="">
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="col-md-6 col-sm-12">
            <div class="row">
                <div class="col-md-6">
                    Contraseña<span style="color: red">*</span>
                </div>
                <div class="col-md-6">
                    <input type="password" name="pass1"required="">
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="col-md-6 col-sm-12">
            <div class="row">
                <div class="col-md-6">
                    Repite contreseña<span style="color: red">*</span>
                </div>
                <div class="col-md-6">
                    <input type="password" name="pass2" required="">
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="col-md-6 col-sm-12">
            <div class="row">
                <div class="col-md-6">
                    Correo electronico<span style="color: red">*</span>
                </div>
                <div class="col-md-6">
                    <input type="text" name="mail" required=""> 
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="col-md-6 col-sm-12">
        </div>
        <br>
        <br>
        <div class="col-sm-12">
            <input type="submit" value="Registrarse">
        </div>
        <br>
        <br>
    </div>
</form>
<script>

    $("#sign").submit(function (e) {
        var myArray = new FormData($(this)[0]);
        if (myArray.get("pass1") == myArray.get("pass2")) {
            conenction_post("user", myArray);
        }else{
            alert("Las contraseñas no coinciden");
        }
//        alert("aa");
        e.preventDefault();
    });
</script>