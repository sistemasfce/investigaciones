<?php
class ci_reporte_proyectos_pic extends investigaciones_ci
{ 
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(investigaciones_ei_cuadro $cuadro)
    {
        $datos = toba::consulta_php('co_proyectos_inv')->get_pic(); 
        $cuadro->set_datos($datos);
    }   
}
