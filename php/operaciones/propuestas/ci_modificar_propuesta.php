<?php
class ci_modificar_propuesta extends investigaciones_ci
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

    function conf__form(investigaciones_ei_formulario $form)
    {
        if ($this->relacion()->esta_cargada()) {
            $datos = $this->tabla('propuestas')->get();
            
            if ($datos['archivo'] != '') {
                // el 23 es para que corte la cadena despues del caracter 19, de /home/fce/informes_inv/
                $nombre = substr($datos['archivo'],23);
                $dir_tmp = toba::proyecto()->get_www_temp();
                exec("cp '". $datos['archivo']. "' '" .$dir_tmp['path']."/".$nombre."'");
                $temp_archivo = toba::proyecto()->get_www_temp($nombre);
                $tamanio = round(filesize($temp_archivo['path']) / 1024);
                $datos['propuesta_path_v'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
                $datos['propuesta_archivo'] = $nombre. ' - Tam.: '.$tamanio. ' KB';  
            }
            
            $form->set_datos($datos);
        }
    }        

    function evt__form__modificacion($datos)
    {
        if (isset($datos['propuesta_archivo'])) {
            $nombre_archivo = $datos['propuesta_archivo']['name'];
            $nuevo = $datos['propuesta'];
            $nombre_nuevo = 'PROPUESTA_'.$nuevo.'.pdf';   
            $destino = '/home/fce/informes_inv/'.$nombre_nuevo;
            move_uploaded_file($datos['propuesta_archivo']['tmp_name'], $destino);   
            $datos['archivo'] = $destino;   
	}
        toba::memoria()->set_dato('persona',$datos['director']);
        $this->tabla('propuestas')->set($datos);
    }       

    //-----------------------------------------------------------------------------------
    //---- form_ml ----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_ml(investigaciones_ei_formulario_ml $form_ml)
    {
        if ($this->relacion()->esta_cargada()) {
            $datos = $this->tabla('evaluadores_en_propuestas')->get_filas();
            $form_ml->set_datos($datos);
        }        
    }

    function evt__form_ml__modificacion($datos)
    {
        $this->tabla('evaluadores_en_propuestas')->procesar_filas($datos); 
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
    //---- Eventos ----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

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
    
    function evt__mail()
    {
        try {
            $director = toba::memoria()->get_dato('persona');
            $email_dir = toba::consulta_php('co_investigadores')->get_mail_investigador($director);
            $email_eval = toba::consulta_php('co_investigadores')->get_mail_evaluador($datos['evaluador']);
            if ($email['email'] == '')
                return;
            $asunto   = "Propuesta de investigación";
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