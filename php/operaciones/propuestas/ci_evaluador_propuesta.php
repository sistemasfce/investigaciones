<?php
class ci_evaluador_propuesta extends investigaciones_ci
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
        $datos = toba::consulta_php('co_propuestas')->get_propuestas($where);
        $cuadro->set_datos($datos);
    }

    function evt__cuadro__seleccion($seleccion)
    {
        $this->relacion()->cargar($seleccion);
        $this->set_pantalla('edicion');
    } 
    
    //-----------------------------------------------------------------------------------
    //---- form -------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

   function conf__form(investigaciones_ei_formulario $form)
    {
        if ($this->relacion()->esta_cargada()) {
            $datos = $this->tabla('evaluadores_en_propuestas')->get();
            $form->set_datos($datos);
        }
    }        

    function evt__form__modificacion($datos)
    {
        $this->tabla('evaluadores_en_propuestas')->set($datos);
    }    

    function evt__procesar()
    {
        $this->dep('relacion')->sincronizar();
        $this->dep('relacion')->resetear();
        $this->set_pantalla('seleccion');
    }

    function evt__cancelar()
    {
        $this->dep('relacion')->resetear();
        $this->set_pantalla('seleccion');
    }           

    //-----------------------------------------------------------------------------------
    //---- form -------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function evt__form__mail($datos)
    {
        try {
            $email = toba::consulta_php('co_propuestas')->get_mail_evaluador($datos['evaluador']);
            if ($email['email'] == '')
                return;
            $asunto   = "Propuesta ";
            $cuerpo_mail = '<p>'." ".
                "".
                "<br/><br/> ";
            $mail_destino = $email['email'];

            $mail = new toba_mail($mail_destino, $asunto, $cuerpo_mail);
            $mail->set_html(true);
            $mail->enviar();    
        } catch (toba_error $e) {
            toba::notificacion()->agregar('No es posible enviar el email.');
        }    
    }

}
?>