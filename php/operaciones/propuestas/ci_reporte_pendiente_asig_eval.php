<?php
class ci_reporte_pendiente_asig_eval extends investigaciones_ci
{ 
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_carreras(investigaciones_ei_cuadro $cuadro)
    {
        $datos = toba::consulta_php('co_propuestas')->get_pendientes_evaluador_por_carrera(); 
        $cuadro->set_datos($datos);
    } 
    
    function conf__cuadro_deptos(investigaciones_ei_cuadro $cuadro)
    {
        $datos = toba::consulta_php('co_propuestas')->get_pendientes_evaluador_por_depto(); 
        $cuadro->set_datos($datos);
    }      

}
