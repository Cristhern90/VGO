<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of game
 *
 * @author crist
 */
include './ajax.php';

class game extends ajax {

    public function search_game_by_name() {
        $name = $_POST["id"];
        $query = 'fields name, first_release_date, genres.name,  
                involved_companies.developer,involved_companies.publisher,involved_companies.porting,involved_companies.supporting,involved_companies.company.name, involved_companies.company.logo.url,
                release_dates, release_dates.region, release_dates.date, release_dates.platform.name, release_dates.platform.platform_logo.url,
                cover.url,
                screenshots.url, 
                artworks.url,
                first_release_date,
                videos.name,videos.video_id,
                websites.url 
                ; search "' . $name . '";
                where category = (0,3,8,9,11);';
        $response = json_decode($this->API_conection("games", $query));

        $arr = array();
        $str = "";
        $count = 0;
        foreach ($response as $key => $game) {
            $game_array = json_decode(json_encode($game), true);
            $obj = json_encode($game_array);
            if (isset($game->first_release_date)) {
                $frd = "(" . date("Y", $game->first_release_date) . ")";
            } else {
                $frd = "";
            }
            $arr = array();

            if (!$this->existing_id("juego", "idJuego", $game->id)) {

                $str .= '<div class="item_slider col-md-3 col-sm-6 col-xs-8">';
                $str .= '<button onclick="conenct_post_obj(\'game\',\'save_game_by_id_api\',\'' . $game->id . '\')" class=\'button_api\'>';

                if (isset($game->cover->url)) {
//                    $str .= "<img src='" . $game->cover->url . "'><br>";
                    $str .= '<img src="' . str_replace("t_thumb", "t_cover_big", $game->cover->url) . '" class="w-100 game_img" alt="..."><br>';
                }
                $str .= $game->name . "<br>" . $frd;
                $str .= "</button>";

                $str .= '</div>';
                $count++;
            }
        }

        $this->result["str"] = $count;
        $this->result["result"] = $str;
    }

    public function search_game_by_name_exact() {
        $name = $_POST["id"];
        $query = 'fields name, first_release_date, genres.name,  
                involved_companies.developer,involved_companies.publisher,involved_companies.porting,involved_companies.supporting,involved_companies.company.name, involved_companies.company.logo.url,
                release_dates, release_dates.region, release_dates.date, release_dates.platform.name, release_dates.platform.platform_logo.url,
                cover.url,
                screenshots.url, 
                artworks.url,
                first_release_date,
                videos.name,videos.video_id,
                websites.url
                ; search "' . $name . '";
                where name = "' . $name . '";';
        $response = json_decode($this->API_conection("games", $query));

        $arr = array();
        $str = "";
        $count = 0;
        foreach ($response as $key => $game) {
            $game_array = json_decode(json_encode($game), true);
            $obj = json_encode($game_array);
            if (isset($game->first_release_date)) {
                $frd = "(" . date("d-m-Y", $game->first_release_date) . ")";
            } else {
                $frd = "";
            }
            $arr = array();

            if (!$this->existing_id("juego", "idJuego", $game->id)) {

                $str .= '<div class="item_slider col-md-3 col-sm-6 col-xs-8">';
                $str .= '<button onclick="conenct_post_obj(\'game\',\'save_game_by_id_api\',\'' . $game->id . '\')" class=\'button_api\'>';

                if (isset($game->cover->url)) {
//                    $str .= "<img src='" . $game->cover->url . "'><br>";
                    $str .= '<img src="' . str_replace("t_thumb", "t_cover_big", $game->cover->url) . '" class="w-100 game_img" alt="..."><br>';
                }
                $str .= $game->name . "<br>" . $frd;
                $str .= "</button>";

                $str .= '</div>';
                $count++;
            }
        }

        $this->result["str"] = $count;
        $this->result["result"] = $str;
    }

    public function save_game_by_id($id) {
        $query = "fields name, first_release_date, genres.name,  
                involved_companies.developer,involved_companies.publisher,involved_companies.porting,involved_companies.supporting,involved_companies.company.name, involved_companies.company.logo.url,
                release_dates, release_dates.region, release_dates.date, release_dates.platform.name, release_dates.platform.platform_logo.url,
                cover.url,
                screenshots.url, 
                artworks.url,
                videos.name,videos.video_id,
                websites.url,
                collection.name  
                ; where id = " . $id . ";";
        $response = json_decode($this->API_conection("games", $query));

        $msg = "";

        $value_arr = $response[0];

        $genres = $value_arr->genres;

//busqueda e inserción de generos
        foreach ($genres as $key => $gen) {
            $ids = $this->existing_genre_ids();
            if (in_array($gen->id, $ids)) {
                $msg .= "El genero " . $gen->name . " existe<br>";
            } else {
                $msg .= "El genero " . $gen->name . " no existe<br>";

                $array_genero = array("idGenero" => $gen->id, "name" => "'" . addslashes($gen->name) . "'");
                if ($this->insert("genero", $array_genero)) {
                    $msg .= "Se ha insertado el género " . $gen->name . " con la id " . $gen->id . "<br>";
                } else {
                    $msg .= "No se ha podido insertar el género " . $gen->name . ". Ha habido un error inesperado<br>";
                }
            }
        }


//busqueda e inserción de companias
        $involved_companies = $value_arr->involved_companies;
        foreach ($involved_companies as $key => $inv_comp) {
            $comp = $inv_comp->company;

            $ids = $this->existing_ids("compania");
//            print_r($ids);
            if (in_array($comp->id, $ids)) {
                $msg .= "La compania " . $comp->name . " existe<br>";
            } else {
                $msg .= "La compania " . $comp->name . " no existe<br>";
                if (isset($comp->logo->url)) {
                    $logo = str_replace("t_thumb", "t_logo_med", $comp->logo->url);
                    $logo_bien = str_replace(".jpg", ".png", $logo);
                } else {
                    $logo_bien = "";
                }
                $array_compania = array("idCompania" => $comp->id, "name" => "'" . $comp->name . "'", "logo" => "'" . $logo_bien . "'");
                if ($this->insert("compania", $array_compania)) {
                    $msg .= "Se ha insertado la compania " . $comp->name . " con la id " . $comp->id . "<br>";
                } else {
                    $msg .= "No se ha podido insertar la compania " . $comp->name . ". Ha habido un error inesperado<br>";
                }
            }
        }


//busqueda de fechas de estreno y plataformas
        $release_dates = $value_arr->release_dates;
        foreach ($release_dates as $key => $release_date) {
//            $date = date("d-m-Y",$release_date->date);
            $plat_id = $release_date->platform->id;
            $plat_name = $release_date->platform->name;
            if (isset($release_date->platform->platform_logo->url)) {
                $logo = $release_date->platform->platform_logo->url;
            } else {
                $logo = "";
            }

            $ids = $this->existing_ids("plataforma");
            if (in_array($plat_id, $ids)) {
                $msg .= "La plataforma " . $plat_name . " existe<br>";
            } else {
                $msg .= "La plataforma " . $plat_name . " no existe<br>";
                $logo = str_replace("t_thumb", "t_logo_med", $logo);
                $logo_bien = str_replace(".jpg", ".png", $logo);
                $array_plataforma = array("idPlataforma" => $plat_id, "name" => "'" . $plat_name . "'", "logo" => "'" . $logo_bien . "'");
                if ($this->insert("plataforma", $array_plataforma)) {
                    $msg .= "Se ha insertado la plataforma " . $plat_name . " con la id " . $plat_id . "<br>";
                } else {
                    $msg .= "No se ha podido insertar la plataforma " . $plat_name . ". Ha habido un error inesperado<br>";
                }
            }
        }


//inserción datos juego
        $name = $value_arr->name;
        $frd = date("Y-m-d", $value_arr->first_release_date);
        if ($this->existing_id("juego", "idJuego", $id)) {
            $msg .= "El juego " . $name . " existe<br>";
        } else {
            $msg .= "El juego " . $name . " no existe<br>";

            $array_juego = array("idJuego" => $id, "name" => "'" . addslashes($name) . "'", "PrimeraFechaEstreno" => "'" . $frd . "'");
            if ($this->insert("juego", $array_juego)) {
                $msg .= "Se ha insertado el juego " . $name . " con la id " . $id . "<br>";
            } else {
                $msg .= "No se ha podido insertar el juegos " . $name . ". Ha habido un error inesperado<br>";
            }
        }


//enlace juego -> generos
        foreach ($genres as $key => $gen) {
            if ($this->existing_2_id("juegogenero", "idJuego", $id, "idGenero", $gen->id)) {
                $msg .= "El genero " . $gen->name . " ya estaba enlazado con el juego " . $name . "<br>";
            } else {
                $array_juego_genero = array("idJuego" => $id, "idGenero" => $gen->id);
                if ($this->insert("juegogenero", $array_juego_genero)) {
                    $msg .= "El genero " . $gen->name . " esta enlazado con el juego " . $name . "<br>";
                } else {
                    $msg .= "No se ha podido enlazar el género " . $gen->name . " con el juego " . $name . ". ha habido un error inesperado<br>";
                }
            }
        }


//enlace juego -> companias
        foreach ($involved_companies as $key => $inv_comp) {
            $comp = $inv_comp->company;
            $dev = ($inv_comp->developer == "true" ? 1 : 0);
            $publi = ($inv_comp->publisher == "true" ? 1 : 0);
            $port = ($inv_comp->porting == "true" ? 1 : 0);
            $support = ($inv_comp->supporting == "true" ? 1 : 0);

            if ($this->existing_2_id("juegocompania", "idJuego", $id, "idCompania", $comp->id)) {
                $msg .= "La compania " . $comp->name . " ya estaba enlazada con el juego " . $name . "<br>";
            } else {
                $array_juego_compania = array("idJuego" => $id, "idCompania" => $comp->id, "desarrollador" => $dev, "publisher" => $publi, "porting" => $port, "supporting" => $support);
                if ($this->insert("juegocompania", $array_juego_compania)) {
                    $msg .= "La compania " . $comp->name . " esta enlazada con el juego " . $name . "<br>";
                } else {
                    $msg .= "No se ha podido enlazar la compania " . $comp->name . " con el juego " . $name . ". ha habido un error inesperado<br>";
                }
            }
        }


//enlace juego -> plataformas

        foreach ($release_dates as $key => $release_date) {
            if (isset($release_date->date)) {
                $date = date("Y-m-d", $release_date->date);
                $plat_id = $release_date->platform->id;
                $plat_name = $release_date->platform->name;
                $region = $release_date->region;

                if ($this->existing_3_id("juegoplataforma", "idJuego", $id, "idPlataforma", $plat_id, "region", $region)) {
                    $msg .= "La plataforma " . $plat_name . " ya estaba enlazada con el juego " . $name . "<br>";
                } else {
                    $array_juego_plataforma = array("idJuego" => $id, "idplataforma" => $plat_id, "fechaEstreno" => "'" . $date . "'", "region" => $region);
                    if ($this->insert("juegoplataforma", $array_juego_plataforma)) {
                        $msg .= "La plataforma " . $plat_name . " esta enlazada con el juego " . $name . "<br>";
                    } else {
                        $msg .= "No se ha podido enlazar la plataforma " . $plat_name . " con el juego " . $name . ". ha habido un error inesperado<br>";
                    }
                }
            }
        }


// cover del juego
        $cover_url = str_replace("t_thumb", "t_cover_big", $value_arr->cover->url);
        $arr_media = array("idJuego" => $id, "tipo" => "'cover'", "idAPI" => $value_arr->cover->id, "url" => "'" . $cover_url . "'");

        if ($this->existing_2_id("mediajuego", "idJuego", $id, "idAPI", $value_arr->cover->id)) {
            $msg .= "Ya existe este cover asignado<br>";
        } else {
            $msg .= "Este cover no está asignado<br>";
            if ($this->insert("mediajuego", $arr_media)) {
                $msg .= "Se ha asignado el cover<br>";
            } else {
                $msg .= "NO se ha asignado el cover. Ha habido un error inesperado<br>";
            }
        }


//screenshots del juego
        $screenshots = $value_arr->screenshots;
        foreach ($screenshots as $key => $screenshot) {
            $screenshot_url = str_replace("t_thumb", "t_original", $screenshot->url);
            $arr_media = array("idJuego" => $id, "tipo" => "'screenshot'", "idAPI" => $screenshot->id, "url" => "'" . $screenshot_url . "'");

            if ($this->existing_2_id("mediajuego", "idJuego", $id, "idAPI", $screenshot->id)) {
                $msg .= "Ya existe este screenshot asignado<br>";
            } else {
                $msg .= "Este screenshot no está asignado<br>";
                if ($this->insert("mediajuego", $arr_media)) {
                    $msg .= "Se ha asignado el screenshot<br>";
                } else {
                    $msg .= "NO se ha asignado el screenshot. Ha habido un error inesperado<br>";
                }
            }
        }


//artworks del juego

        if (isset($value_arr->artworks)) {
            $artworks = $value_arr->artworks;
            foreach ($artworks as $key => $artwork) {
                $artwork_url = str_replace("t_thumb", "t_original", $artwork->url);
                $arr_media = array("idJuego" => $id, "tipo" => "'artwork'", "idAPI" => $artwork->id, "url" => "'" . $artwork_url . "'");

                if ($this->existing_2_id("mediajuego", "idJuego", $id, "idAPI", $artwork->id)) {
                    $msg .= "Ya existe este artwork asignado<br>";
                } else {
                    $msg .= "Este artwork no está asignado<br>";
                    if ($this->insert("mediajuego", $arr_media)) {
                        $msg .= "Se ha asignado el artwork<br>";
                    } else {
                        $msg .= "NO se ha asignado el artwork. Ha habido un error inesperado<br>";
                    }
                }
            }
        } else {
            $msg .= "No hay artworks para este juego";
        }



//videos del juego
        if (isset($value_arr->videos)) {
            $videos = $value_arr->videos;
            foreach ($videos as $key => $video) {
                $arr_media = array("idJuego" => $id, "tipo" => "'video'", "idAPI" => $video->id, "nombre" => "'" . $video->name . "'", "idVideo" => "'" . $video->video_id . "'");

                if ($this->existing_2_id("mediajuego", "idJuego", $id, "idAPI", $video->id)) {
                    $msg .= "Ya existe el video " . $video->name . " asignado<br>";
                } else {
                    $msg .= "El video " . $video->name . " no está asignado<br>";
                    if ($this->insert("mediajuego", $arr_media)) {
                        $msg .= "Se ha asignado el video " . $video->name . "<br>";
                    } else {
                        $msg .= "No se ha asignado el video " . $video->name . ". Ha habido un error inesperado<br>";
                    }
                }
            }
        }


//websites del juego
        if (isset($value_arr->websites)) {
            $websites = $value_arr->websites;
            foreach ($websites as $key => $website) {
                $arr_media = array("idJuego" => $id, "idWebJuego" => $website->id, "url" => "'" . addslashes($website->url) . "'");

                if ($this->existing_2_id("webjuego", "idJuego", $id, "idWebJuego", $website->id)) {
                    $msg .= "Ya existe el website " . $website->id . " asignado<br>";
                } else {
                    $msg .= "El website " . $website->url . " no está asignado<br>";
                    if ($this->insert("webjuego", $arr_media)) {
                        $msg .= "Se ha asignado el website " . $website->id . "<br>";
                    } else {
                        $msg .= "NO se ha asignado el website " . $website->id . ". Ha habido un error inesperado<br>";
                    }
                }
            }
        }


//collection del juego
        if (isset($value_arr->collection)) {
            $collection = $value_arr->collection;
//            foreach ($websites as $key => $website) {
                $arr_media = array("idSerie" => $collection->id, "name" => "'" . addslashes($collection->name) . "'");
                $arr_media2 = array("idJuego" => $id, "idSerie" => $collection->id);

                if ($this->existing_id("serie", "idSerie", $collection->id)) {
                    $msg .= "Ya existe la serie " . addslashes($collection->name) . "<br>";
                } else {
                    $msg .= "La serie " . addslashes($collection->name) . " no existe<br>";
                    if ($this->insert("serie", $arr_media)) {
                        $msg .= "Se ha creado la serie " . addslashes($collection->name) . "<br>";
                    } else {
                        $msg .= "NO se ha creado la serie " . addslashes($collection->name) . ". Ha habido un error inesperado<br>";
                    }
                }

                if (!$this->existing_2_id("juegoserie", "idJuego", $id, "idSerie", $collection->id)) {
                    if ($this->insert("juegoserie", $arr_media2)) {
                        $msg .= "Se ha asignado la serie " . addslashes($collection->name) . "<br>";
                    } else {
                        $msg .= "NO se ha asignado la serie " . addslashes($collection->name) . ". Ha habido un error inesperado<br>";
                    }
                }
//            }
        }

        $this->result["location"] = $this->url;
        $this->result["alert"] = 1;
        $this->result["msg"] = "Se ha añadido el juego " . $name . " a la aplicación";
    }

    public function save_game_by_id_api() {
        $dates = $this->op["dates"];

        $this->save_game_by_id($dates);
    }

    public function search_game_by_name_in_bbdd() {
        $name = $_POST["id"];
        $sql = "
            SELECT j.*, mj.url, (
                SELECT GROUP_CONCAT(DISTINCT CONCAT(p.logo, ',',p.colorFondo) SEPARATOR '|') 
                FROM plataforma p
                INNER JOIN juegoplataforma jp ON jp.idPlataforma = p.idPlataforma
                WHERE jp.idJuego = j.idJuego
                ORDER BY fechaEstreno DESC
            ) logos 
            FROM juego j 
            INNER JOIN mediajuego mj ON mj.idJuego = j.idJuego
            WHERE mj.tipo = 'cover' AND j.name LIKE '%" . $name . "%'";
//        echo $sql;
        $query = $this->get_query($sql);
        $str = "";
        $count0 = 0;
        while ($row = $query->fetch_assoc()) {
            $str .= '<div class="item_slider col-md-3 col-sm-6 col-xs-8">
                <div class="item_slider_content row">
                    <div class="plat_imgs_cont">';
            $logos_arr = explode("|", $row["logos"]);
            $count = 0;
            foreach ($logos_arr as $key => $dats_logo) {
                $logo_arr = explode(",", $dats_logo);
                $logo = $logo_arr[0];
                $fondo = $logo_arr[1];

                if ($count <= 3) {
                    $str .= '<img src="' . $logo . '" class="plat_img ' . ($fondo ? "rondo" : "") . '" style="left: calc(100%/6 * <?= $count ?>); ' . ($fondo ? "background-color:" . $fondo : "") . '" >';
                }
                $count++;
            }
            if (($count - 4) > 0) {
                $str .= '<div class="plat_img" style="border-radius: 50%; background-color: white;" >
                                <div style="font-size: 16px;margin: calc((100% - 18px)/2);">+' . ($count - 4) . '</div>
                            </div>';
            }
            $str .= '</div>
                    <img src="' . $row["url"] . '" class="w-100 game_img" alt="...">
                </div>
            </div>';
            $count0++;
        }
        $this->result["str"] = $count0;
        $this->result["result"] = $str;
    }

    public function set_order_1() {
        $order = $this->op["id2"];
        setcookie($this->op["id1"], $order, time() + 3600, "/VGO2/", "localhost", 1);
        setcookie("game_page", "", time() - 3600, "/VGO2/", "localhost", 1);
        setcookie("game_lmits", "", time() - 3600, "/VGO2/", "localhost", 1);
        $this->result["reload"] = 1;
    }

    public function set_order_2() {
        $order = $this->op["id2"];
        setcookie($this->op["id1"], $order, time() + 3600, "/VGO2/", "localhost", 1);
        setcookie("game_page", "", time() - 3600, "/VGO2/", "localhost", 1);
        setcookie("game_lmits", "", time() - 3600, "/VGO2/", "localhost", 1);
        $this->result["reload"] = 1;
    }

    public function set_limits() {
//        echo json_encode($this->op);
        $limit1 = $this->op["id1"];
        $limit2 = $this->op["id2"];
        setcookie("limit1", $limit1, time() + 3600, "/VGO2/", "localhost", 1);
        setcookie("limit2", $limit2, time() + 3600, "/VGO2/", "localhost", 1);
        $this->result["location"] = $this->url . "juegos/";
    }

    public function del_limits() {
        setcookie("limit1", "", time() - 3600, "/VGO2/", "localhost", 1);
        setcookie("limit2", "", time() - 3600, "/VGO2/", "localhost", 1);
        $this->result["location"] = $this->url . "juegos/";
    }

    public function fin_partida(){
        $idPartida = $this->op["id1"];
        $fecha_fin = ($this->op["id2"]?"'".$this->op["id2"]."'":"NULL");
        $this->update("partida", array("fechaFin"=>$fecha_fin), array("idPartida"=>$idPartida));
        $this->result["reload"] = 1;
    }
    
    public function jugar(){
        $idPartida = $this->op["id1"];
        $idUsuarioJuegoPlataforma = $this->op["id2"];
        $this->insert("tiempojuego", array("idUsuarioJuegoPlataforma"=>$idUsuarioJuegoPlataforma,"fechaInicio"=>"CURRENT_TIMESTAMP","idPartida"=>$idPartida));
        $this->result["reload"] = 1;
    }
    
    public function caduca(){
        $idUsuarioJuegoPlataforma = $this->op["id1"];
        $this->update("UsuarioJuegoPlataforma", array("caducado"=> 1),array("idUsuarioJuegoPlataforma"=>$idUsuarioJuegoPlataforma));
        $this->result["reload"] = 1;
    }
    
    public function renueva(){
        $idUsuarioJuegoPlataforma = $this->op["id1"];
        $this->update("UsuarioJuegoPlataforma", array("caducado"=> "NULL"),array("idUsuarioJuegoPlataforma"=>$idUsuarioJuegoPlataforma));
        $this->result["reload"] = 1;
    }
    
    public function parar(){
        $idPartida = $this->op["id1"];
        $idUsuarioJuegoPlataforma = $this->op["id2"];
        $idTiempoJuego = $this->op["id3"];
        $this->update("tiempojuego", array("fechaFin"=>"CURRENT_TIMESTAMP") ,array("idUsuarioJuegoPlataforma"=>$idUsuarioJuegoPlataforma,"idTiempoJuego"=>$idTiempoJuego,"idPartida"=>$idPartida));
        $this->result["reload"] = 1;
    }
    
    public function parar2(){
        $idPartida = $this->op["id1"];
        $idUsuarioJuegoPlataforma = $this->op["id2"];
        $this->update("tiempojuego", array("fechaFin"=>"CURRENT_TIMESTAMP") ,array("idUsuarioJuegoPlataforma"=>$idUsuarioJuegoPlataforma,"idPartida"=>$idPartida, "fechaFin"=>"NULL"));
        $this->result["reload"] = 1;
    }

}

session_start(); //abre $_SESSION
$ajax = new game($_POST, $_SESSION);
$function = $_POST["funcion"];
$ajax->$function();
//$ajax->search_game_by_name();
echo json_encode($ajax->result);
