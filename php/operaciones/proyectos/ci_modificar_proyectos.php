<?php
class ci_modificar_proyectos extends investigaciones_ci
{ 
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
        $aux = array();
        $tipo = $cuadro->get_parametro('a');
        $where = $where . ' AND proyectos_inv.tipo = '.$tipo;
        $datos = toba::consulta_php('co_proyectos_inv')->get_proyectos($where);
        foreach ($datos as $dat) {
            if (isset($dat['director'])) {
            $nombre = toba::consulta_php('co_personas')->get_datos_persona($dat['director']);
            $dat['nombre_completo'] = $nombre['nombre_completo'];
            }
            if (isset($dat['carrera'])) {
                $nombre = toba::consulta_php('co_carreras')->get_carreras('carrera = '.$dat['carrera']);
                $dat['carrera_desc'] = $nombre[0]['nombre'];     
            }
            if (isset($dat['departamento'])) {
                $nombre = toba::consulta_php('co_carreras')->get_departamentos('departamento = '.$dat['departamento']);
                $dat['departamento_desc'] = $nombre[0]['descripcion'];     
            }    
            if (isset($dat['asignatura'])) {
                $nombre = toba::consulta_php('co_carreras')->get_asignaturas('actividad = '.$dat['asignatura']);
                $dat['asignatura_desc'] = $nombre[0]['descripcion'];     
            } 
            if (isset($dat['ambito'])) {
                $nombre = toba::consulta_php('co_carreras')->get_ambitos('ambito = '.$dat['ambito']);
                $dat['ambito_desc'] = $nombre[0]['descripcion'];     
            }             
            $aux[] = $dat;
        }
        //$datos_ordenados = rs_ordenar_por_columna($aux, 'numero');
        $cuadro->set_datos($aux);
    }

    function evt__cuadro__seleccion($seleccion)
    {
        $this->relacion()->cargar($seleccion);
        $this->s__proyecto = $seleccion;
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
            $datos = $this->tabla('proyectos_inv')->get();
            $nombre = substr($datos['proyecto_path'],23);
            $dir_tmp = toba::proyecto()->get_www_temp();
            exec("cp '". $datos['proyecto_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
            $temp_archivo = toba::proyecto()->get_www_temp($nombre);
            $tamanio = round(filesize($temp_archivo['path']) / 1024);
            $datos['proyecto_path'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
            $datos['proyecto'] = $nombre. ' - Tam.: '.$tamanio. ' KB'; 
            $alcances = $this->tabla('proyectos_inv_alcances_inv')->get_filas();
            $tipos = $this->tabla('proyectos_inv_tipos_inv')->get_filas();
            foreach ($tipos as $ti) {
                $array_tipo[] = $ti['tipo'];
            }
            foreach ($alcances as $al) {
                $array_alcance[] = $al['alcance'];
            }
            $datos['tipo_inv'] = $array_tipo;
            $datos['alcance'] = $array_alcance;
            $form->set_datos($datos);
        }
    }    
    
    function evt__form__modificacion($datos)
    {
        if (isset($datos['proyecto_archivo'])) {
            $nombre_archivo = $datos['proyecto_archivo']['name'];
            $nuevo = $datos['ciclo_lectivo'].'_'.$datos['entrada_numero'];
            $nuevo = $this->sanear_string($nuevo);
            $nombre_nuevo = 'PROYECTO_'.$nuevo.'.pdf';   
            $destino = '/home/fce/informes_inv/'.$nombre_nuevo;
            move_uploaded_file($datos['proyecto_archivo']['tmp_name'], $destino);   
            $datos['proyecto_path'] = $destino;   
	}
        $this->tabla('proyectos_inv')->set($datos);
        foreach ($datos['alcance'] as $alc) {
            $aux['alcance'] = $alc;
            $aux['apex_ei_analisis_fila'] = 'A';
            $alcance[] = $aux;
        }
        if (isset($alcance))
            $this->tabla('proyectos_inv_alcances_inv')->procesar_filas($alcance);
        foreach ($datos['tipo_inv'] as $ti) {
            $aux['tipo'] = $ti;
            $aux['apex_ei_analisis_fila'] = 'A';
            $tipo[] = $aux;
        }
        if (isset($tipo))
            $this->tabla('proyectos_inv_tipos_inv')->procesar_filas($tipo);
    }
    
    //-----------------------------------------------------------------------------------
    //---- form_ml ----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_ml_ue(investigaciones_ei_formulario_ml $form_ml)
    {
        if ($this->relacion()->esta_cargada()) {
            $datos = $this->tabla('proyectos_inv_ue')->get_filas();
            $form_ml->set_datos($datos);
        }
    }
    
    function evt__form_ml_ue__modificacion($datos)
    {

        $this->tabla('proyectos_inv_ue')->procesar_filas($datos);
    }   

    function evt__procesar()
    {
       try {
            $this->dep('relacion')->sincronizar();
            $this->dep('relacion')->resetear();
            $this->set_pantalla('seleccion');
       }catch (toba_error $e) {
           toba::notificacion()->agregar('No se puede modificar el registro', 'error');
       }
    }

    function evt__cancelar()
    {
        $this->dep('relacion')->resetear();
        $this->set_pantalla('seleccion');
    }  
    
    function sanear_string($string)
    {
        $string = trim($string);
        $string = str_replace(
                                        array('á', 'Á'),
                                        array('a', 'A'),
                                        $string);
        $string = str_replace(
                                        array('é', 'É'),
                                        array('e', 'E'),
                                        $string);
        $string = str_replace(
                                        array('í', 'Í'),
                                        array('i', 'I'),
                                        $string);
        $string = str_replace(
                                        array('ó', 'Ó'),
                                        array('o', 'O'),
                                        $string);
        $string = str_replace(
                                        array('ú', 'Ú', 'ü', 'Ü'),
                                        array('u', 'U', 'u', 'U'),
                                        $string);
        $string = str_replace(
                                        array('ñ', 'Ñ'),
                                        array('n', 'N'),
                                        $string);

        //Esta parte se encarga de eliminar cualquier caracter extraÃ±o
        $string = str_replace(
                                        array("/","(", ")", "?", "Â¿", ";", ",", ":","."),'',
                                        $string);

        // reemplazo los espacios por guion bajo
        $string = str_replace(' ','_',$string);
        return $string;
    }
}
?>