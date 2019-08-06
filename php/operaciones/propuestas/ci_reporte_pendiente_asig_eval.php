<?php
class ci_reporte_pendiente_asig_eval extends investigaciones_ci
{ 
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

//    function conf__cuadro_carreras(investigaciones_ei_cuadro $cuadro)
//    {
//        $datos = toba::consulta_php('co_propuestas')->get_pendientes_evaluador_por_carrera(); 
//        $cuadro->set_datos($datos);
//    } 
//    
//    function conf__cuadro_deptos(investigaciones_ei_cuadro $cuadro)
//    {
//        $datos = toba::consulta_php('co_propuestas')->get_pendientes_evaluador_por_depto(); 
//        $cuadro->set_datos($datos);
//    }      

    function conf__cuadro_departamento(investigaciones_ei_cuadro $cuadro)
    {
        $aux = array();
        $tipo = $cuadro->get_parametro('a');
        $estado = $cuadro->get_parametro('b');
        $where = ' propuestas.estado = '.$estado.' AND propuestas.tipo = '.$tipo;
        $datos = toba::consulta_php('co_propuestas')->get_propuestas($where);
        foreach ($datos as $dat) {
            $nombre = toba::consulta_php('co_personas')->get_datos_persona($dat['proponente']);
            $dat['nombre_completo'] = $nombre['nombre_completo'];
            if (isset($dat['carrera'])) {
                $nombre = toba::consulta_php('co_carreras')->get_carreras('carrera = '.$dat['carrera']);
                $dat['carrera_desc'] = $nombre[0]['nombre'];     
            }
            if (isset($dat['departamento'])) {
                $nombre = toba::consulta_php('co_carreras')->get_departamentos('departamento = '.$dat['departamento']);
                $dat['departamento_desc'] = $nombre[0]['descripcion'];     
            }    
            if (isset($dat['asignatura'])) {
                $nombre = toba::consulta_php('co_carreras')->get_asignaturas('actividad = '.$dat['asignatura']);
                $dat['asignatura_desc'] = $nombre[0]['descripcion'];     
            } 
            if (isset($dat['ambito'])) {
                $nombre = toba::consulta_php('co_carreras')->get_ambitos('ambito = '.$dat['ambito']);
                $dat['ambito_desc'] = $nombre[0]['descripcion'];     
            }             
            $aux[] = $dat;
        }
        $cuadro->set_datos($aux);
    }  
}
