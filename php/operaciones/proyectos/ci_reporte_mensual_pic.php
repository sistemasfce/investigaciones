<?php
class ci_reporte_mensual_pic extends investigaciones_ci
{ 
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(investigaciones_ei_cuadro $cuadro)
    {
        $datos = toba::consulta_php('co_proyectos_inv')->get_pic_pendientes_resol(); 
        $cuadro->set_datos($datos);
    }   
}