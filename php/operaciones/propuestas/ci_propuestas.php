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

    //-----------------------------------------------------------------------------------
    //---- form -------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form(investigaciones_ei_formulario $form)
    {
        $datos = toba::consulta_php('co_propuestas')->get_proximo_numero();
        $form->set_datos($datos);
    }    
    
    function evt__form__modificacion($datos)
    {
        $this->tabla('propuestas')->set($datos);
    }

    function evt__procesar()
    {
        try {
            $this->dep('relacion')->sincronizar();
            $this->dep('relacion')->resetear();
            $this->informar_msg("La propuesta fue cargada exitosamente","aviso");
        }catch (toba_error $e) {
            toba::notificacion()->agregar('No se puede eliminar el registro', 'error');
        }
    }

    function evt__cancelar()
    {
        $this->dep('relacion')->resetear();
    }          

}
?>