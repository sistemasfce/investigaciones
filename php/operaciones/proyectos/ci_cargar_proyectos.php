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
        $this->tabla('proyectos_inv_alcances_inv')->procesar_filas($alcance);
        foreach ($datos['tipo_inv'] as $ti) {
            $aux['tipo'] = $ti;
            $aux['apex_ei_analisis_fila'] = 'A';
            $tipo[] = $aux;
        }
        $this->tabla('proyectos_inv_tipos_inv')->procesar_filas($tipo);
    }
    
    //-----------------------------------------------------------------------------------
    //---- form_ml ----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_ml_ue(investigaciones_ei_formulario_ml $form_ml)
    {
//        if ($this->relacion()->esta_cargada()) {
//            $datos = $this->tabla('investigadores_en_proyectos')->get_filas();
//            $form_ml->set_datos($datos);
//        }
    }
    
    function evt__form_ml_ue__modificacion($datos)
    {
        //$this->tabla('investigadores_en_proyectos')->procesar_filas($datos);
    }   

    function evt__procesar()
    {
       try {
            $this->dep('relacion')->sincronizar();
            $this->dep('relacion')->resetear();
            $datos = toba::consulta_php('co_proyectos_inv')->get_ultimo_numero();
            toba::notificacion()->agregar('Proyecto registrado con N� '.$datos['numero'], 'info');
       }catch (toba_error $e) {
           toba::notificacion()->agregar('No se puede insertar el registro', 'error');
       }
    }

    function evt__cancelar()
    {
        $this->dep('relacion')->resetear();
    }          

}
?>