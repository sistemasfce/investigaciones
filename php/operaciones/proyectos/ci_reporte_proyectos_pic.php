<?php
class ci_reporte_proyectos_pic extends investigaciones_ci
{ 
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(investigaciones_ei_cuadro $cuadro)
    {
        $where = 'proyectos_inv.tipo = 1';
        $datos = toba::consulta_php('co_proyectos_inv')->get_proyectos($where); 
        $cuadro->set_datos($datos);
    }   
}
