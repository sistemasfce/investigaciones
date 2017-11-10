<?php
class ci_proyectos_por_evaluador extends investigaciones_ci
{
    protected $s__filtro;
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(investigaciones_ei_cuadro $cuadro)
    {
            $where = $this->dep('filtro')->get_sql_where();
            //if ($where == '1=1')
            //    return;
            $datos = toba::consulta_php('co_proyectos')->get_proyectos_por_evaluador($where); 
            $cuadro->set_datos($datos);

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
}
?>