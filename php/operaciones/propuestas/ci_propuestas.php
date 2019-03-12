<?php
class ci_propuestas extends investigaciones_ci
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

    function fin()
    {
        $datos = array();
        toba::memoria()->set_dato('datos',$datos);
    }
    
    //-----------------------------------------------------------------------------------
    //---- form -------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form(investigaciones_ei_formulario $form)
    {
        $datos = toba::memoria()->get_dato('datos');
        $form->set_datos($datos);
    }    
    
    function evt__form__modificacion($datos)
    {
        $datos['ciclo_lectivo'] = date('Y');
        if (isset($datos['propuesta_archivo'])) {
            $nombre_archivo = $datos['propuesta_archivo']['name'];
            $nuevo = $datos['ciclo_lectivo'].'_'.$datos['entrada_numero'];
            $nombre_nuevo = 'PROPUESTA_'.$nuevo.'.pdf';   
            $destino = '/home/fce/informes_inv/'.$nombre_nuevo;
            move_uploaded_file($datos['propuesta_archivo']['tmp_name'], $destino);   
            $datos['propuesta_path'] = $destino;   
	}
        $datos['estado'] = 1;
        $this->tabla('propuestas')->set($datos);
        toba::memoria()->set_dato('datos',$datos);
    }

    function evt__procesar()
    {
        try {
            $this->dep('relacion')->sincronizar();
            $numero = toba::consulta_php('co_propuestas')->get_ultimo_numero();
            toba::notificacion()->agregar('Propuesta registrada con N° '.$numero['numero'], 'info');
            $datos = toba::memoria()->get_dato('datos');
            $datos['numero'] = $numero['numero'];
            $datos['ciclo_lectivo'] = $numero['ciclo_lectivo'];
            toba::memoria()->set_dato('datos',$datos);
            $this->evento('procesar')->ocultar();
            $this->pantalla('pant_inicial')->agregar_evento('confirmar');
        }catch (toba_error $e) {
            toba::notificacion()->agregar('No se puede insertar el registro', 'error');
        }
    }
    function evt__confirmar()
    {
        try {
            $datos = array();
            toba::memoria()->set_dato('datos',$datos);
            $this->dep('relacion')->sincronizar();
            $this->dep('relacion')->resetear();
        }catch (toba_error $e) {
            toba::notificacion()->agregar('No se puede insertar el registro', 'error');
        }
    }
    function evt__cancelar()
    {
        $datos = array();
        toba::memoria()->set_dato('datos',$datos);
        $this->dep('relacion')->resetear();
    }          

}
?>