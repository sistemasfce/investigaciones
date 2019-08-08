<?php
class ci_cargar_proyectos extends investigaciones_ci
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
    //---- form -------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form(investigaciones_ei_formulario $form)
    {
        $form->set_datos($datos);
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
        $datos['estado'] = 23;  // aceptado
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
            $datos = toba::consulta_php('co_proyectos_inv')->get_ultimo_numero();
            toba::notificacion()->agregar('Proyecto registrado con N° '.$datos['numero'], 'info');
       }catch (toba_error $e) {
           toba::notificacion()->agregar('No se puede insertar el registro', 'error');
       }
    }

    function evt__cancelar()
    {
        $this->dep('relacion')->resetear();
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