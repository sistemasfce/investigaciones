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
        $datos['proyecto_estado'] = 23;  // aceptado
        $this->tabla('proyectos')->set($datos);
    }

    function evt__procesar()
    {
       // try {
            $this->dep('relacion')->sincronizar();
            $this->dep('relacion')->resetear();
            $datos = toba::consulta_php('co_proyectos')->get_ultimo_numero();
            toba::notificacion()->agregar('Proyecto registrado con N° '.$datos['numero'], 'info');
       // }catch (toba_error $e) {
        //    toba::notificacion()->agregar('No se puede insertar el registro', 'error');
        //}
    }

    function evt__cancelar()
    {
        $this->dep('relacion')->resetear();
    }          

}
?>