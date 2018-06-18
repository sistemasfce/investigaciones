<?php
class ci_proyectos_catedras extends investigaciones_ci
{
    protected $s__filtro;

    //-------------------------------------------------------------------------
    function relacion()
    {   
        return $this->controlador->dep('relacion');
    }   

    //-------------------------------------------------------------------------
    function tabla($id)
    {   
        return $this->controlador->dep('relacion')->tabla($id);    
    } 
	
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(investigaciones_ei_cuadro $cuadro)
    {
        $where = $this->dep('filtro')->get_sql_where();
        $datos = toba::consulta_php('co_proyectos')->get_proyectos_catedras($where); 
        $cuadro->set_datos($datos);
    }

    function evt__cuadro__seleccion($seleccion)
    {
        $this->relacion()->cargar($seleccion);
        $this->set_pantalla('edicion');
    }

    //-----------------------------------------------------------------------------------
    //---- filtro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__filtro(investigaciones_ei_filtro $filtro)
    {   
        if (isset($this->s__filtro)) {
            $filtro->set_datos($this->s__filtro);
        }   
    }  

    function evt__filtro__filtrar($datos)
    {   
        $this->s__filtro = $datos;
    }   

    function evt__filtro__cancelar()
    {   
        unset($this->s__filtro);
    }   

    //-----------------------------------------------------------------------------------
    //---- form -------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form(investigaciones_ei_formulario $form)
    {
        if ($this->relacion()->esta_cargada()) {
            $datos = $this->tabla('proyectos_catedras')->get();
            // si esta cargada informe_1 armo el link para descarga          
            if ($datos['entrego_proyecto'] == 'S') {
                // el 23 es para que corte la cadena despues del caracter 19, de /home/fce/informes_inv/
                $nombre = substr($datos['proyecto_path'],23);
                $dir_tmp = toba::proyecto()->get_www_temp();
                exec("cp '". $datos['proyecto_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
                $temp_archivo = toba::proyecto()->get_www_temp($nombre);
                $tamanio = round(filesize($temp_archivo['path']) / 1024);
                $datos['proyecto_path_v'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
                $datos['proyecto'] = $nombre. ' - Tam.: '.$tamanio. ' KB';  
            }
            $form->set_datos($datos);
        }
    }

    function evt__form__modificacion($datos)
    {   
        if (isset($datos['proyecto'])) {
            $nombre_archivo = $datos['proyecto']['name'];
            if ($datos['numero_pi'] != '')
                $nuevo = $datos['numero_pi'];
            else 
                $nuevo = substr($datos['titulo'],0,50);
            $nuevo = $this->sanear_string($nuevo);
            $nombre_nuevo = 'PR_CATEDRA_'.$nuevo.'.pdf';   
            $destino = '/home/fce/informes_inv/'.$nombre_nuevo;

            move_uploaded_file($datos['proyecto']['tmp_name'], $destino);   
            $datos['proyecto_path'] = $destino;   
            $datos['entrego_proyecto'] = 'S'; 
        }       
        $this->tabla('proyectos')->set($datos);
    }
	
    //-----------------------------------------------------------------------------------
    //---- eventos ----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function evt__agregar()
    {
        $this->set_pantalla('edicion');
    }

    function evt__cancelar()
    {
        $this->dep('relacion')->resetear();
        $this->set_pantalla('seleccion');
    }

    function evt__eliminar()
    {
        try {
            $this->dep('relacion')->eliminar_todo();
            $this->set_pantalla('seleccion');
        } catch (toba_error $e) {
            toba::notificacion()->agregar('No es posible eliminar el registro.');
        }
    }

    function evt__guardar()
    {
        //try {
        $this->relacion()->sincronizar();
        $this->relacion()->resetear();
        //} catch (toba_error $e) {
        //    $this->informar_msg('Error al guardar proyecto - '. $e->get_mensaje());
        //    return;
        //}
        $this->set_pantalla('seleccion');
    }    
    
    //-----------------------------------------------------------------------------------
    //---- form_ml ----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_ml_ue(investigaciones_ei_formulario_ml $form_ml)
    {
        if ($this->relacion()->esta_cargada()) {
            $datos = $this->tabla('investigadores_en_proyectos')->get_filas();
            $form_ml->set_datos($datos);
        }
    }

    function evt__form_ml_ue__modificacion($datos)
    {
        $this->tabla('investigadores_en_proyectos')->procesar_filas($datos);
    }     

    //-----------------------------------------------------------------------------------
    //---- form_ml ----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_ml_eval(investigaciones_ei_formulario_ml $form_ml)
    {
        if ($this->relacion()->esta_cargada()) {
            $datos_fila = $this->tabla('evaluadores_en_proyectos')->get_filas();
            $aux = array();
            foreach ($datos_fila as $datos) {
                // el 23 es para que corte la cadena despues del caracter 19, de /home/fce/informes_inv/
                $nombre = substr($datos['evaluacion_path'],23);
                $dir_tmp = toba::proyecto()->get_www_temp();
                exec("cp '". $datos['evaluacion_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
                $temp_archivo = toba::proyecto()->get_www_temp($nombre);
                $tamanio = round(filesize($temp_archivo['path']) / 1024);
                $datos['evaluacion_path_v'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
                $datos['evaluacion_archivo'] = $nombre. ' - Tam.: '.$tamanio. ' KB';   
                $aux[] = $datos;
            }
            $form_ml->set_datos($aux);
        }
    }

    function evt__form_ml_eval__modificacion($datos)
    {
        $aux = array();
        foreach ($datos as $dat) {
            $numero_pi = toba::memoria()->get_dato('numero_pi');
            $titulo = toba::memoria()->get_dato('titulo');
            if (isset($dat['evaluacion_archivo'])) {
                $nombre_archivo = $dat['evaluacion_archivo']['name'];
                if ($numero_pi != '')
                        $nuevo = $numero_pi;
                else 
                        $nuevo = substr($titulo,0,50);
                $nuevo = $this->sanear_string($nuevo);
                $nombre_nuevo = 'evaluacion_'.$this->sanear_string($dat['tipo_informe']).'_'.$nuevo.'.pdf';   
                $destino = '/home/fce/informes_inv/'.$nombre_nuevo;

                move_uploaded_file($dat['evaluacion_archivo']['tmp_name'], $destino);   
                $dat['evaluacion_path'] = $destino;   
            }
            $aux[] = $dat;
        }
        $this->tabla('evaluadores_en_proyectos')->procesar_filas($aux);
    }  

    //-----------------------------------------------------------------------------------
    //---- form_ml_informes -------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_ml_informes(investigaciones_ei_formulario_ml $form_ml)
    {
        if ($this->relacion()->esta_cargada()) {
            $datos_fila = $this->tabla('proyectos_informes')->get_filas();
            $aux = array();
            foreach ($datos_fila as $datos) {
                // el 23 es para que corte la cadena despues del caracter 19, de /home/fce/informes_inv/
                $nombre = substr($datos['informe_path'],23);
                $dir_tmp = toba::proyecto()->get_www_temp();
                exec("cp '". $datos['informe_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
                $temp_archivo = toba::proyecto()->get_www_temp($nombre);
                $tamanio = round(filesize($temp_archivo['path']) / 1024);
                $datos['informe_path_v'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
                $datos['informe_archivo'] = $nombre. ' - Tam.: '.$tamanio. ' KB';   
                $aux[] = $datos;
            }
            $form_ml->set_datos($aux);
        }        
    }

    function evt__form_ml_informes__modificacion($datos)
    {
        $aux = array();
        foreach ($datos as $dat) {
            $numero_pi = toba::memoria()->get_dato('numero_pi');
            $titulo = toba::memoria()->get_dato('titulo');
            if (isset($dat['informe_archivo'])) {
                $nombre_archivo = $dat['informe_archivo']['name'];
                if ($numero_pi != '')
                        $nuevo = $numero_pi;
                else 
                        $nuevo = substr($titulo,0,50);
                $nuevo = $this->sanear_string($nuevo);
                $nombre_nuevo = $dat['tipo'].'_'.$nuevo.'.pdf';   
                $destino = '/home/fce/informes_inv/'.$nombre_nuevo;

                move_uploaded_file($dat['informe_archivo']['tmp_name'], $destino);   
                $dat['informe_path'] = $destino;   
            }
            $aux[] = $dat;
        }
        $this->tabla('proyectos_informes')->procesar_filas($aux);        
    }  
	
	
    function sanear_string($string)
    {
        $string = trim($string);

        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
                array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
                $string);
        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
                array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
                $string);
        $string = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
                array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
                $string);
        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
                array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
                $string);
        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                $string);
        $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'),
                array('n', 'N', 'c', 'C',),
                $string);

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
                array("(", ")", "?", "¿", ";", ",", ":","."),'',
                $string);        

        // reemplazo los espacios por guion bajo
        $string = str_replace(' ','_',$string);
        return $string;
    }
}
?>