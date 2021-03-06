<?php
class ci_cargar_resol_acr_interna extends investigaciones_ci
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
        $where = $where . ' AND proyectos_inv.estado = 23 AND proyectos_inv.tipo::Int = 1';
        $datos = toba::consulta_php('co_proyectos_inv')->get_proyectos($where);
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
            $datos = $this->tabla('proyectos_inv')->get();
            $form->set_datos($datos);
        }
    }        

    function evt__form__modificacion($datos)
    {
        $this->tabla('proyectos_inv')->set($datos);
    } 
    
    //-----------------------------------------------------------------------------------
    //---- form_ml ----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_ml(investigaciones_ei_formulario_ml $form_ml)
    {
        if ($this->relacion()->esta_cargada()) {
            $datos = $this->tabla('proyectos_inv_resoluciones')->get_filas();
            $form_ml->set_datos($datos);
        }
    }

    function evt__form_ml__modificacion($datos)
    {
        $proyecto = $this->tabla('proyectos_inv')->get();
        $proyecto['estado'] = 25; // proyecto aprobado
        $this->tabla('proyectos_inv')->set($proyecto);
        $this->tabla('proyectos_inv_resoluciones')->procesar_filas($datos);
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
}