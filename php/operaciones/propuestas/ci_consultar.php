<?php
class ci_consultar extends investigaciones_ci
{ 
    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro(investigaciones_ei_cuadro $cuadro)
    {
        $where = $this->dep('filtro')->get_sql_where();
        $aux = array();
        $tipo = $cuadro->get_parametro('a');
        $where = $where . ' AND propuestas.tipo = '.$tipo;
        $datos = toba::consulta_php('co_propuestas')->get_propuestas($where);
        foreach ($datos as $dat) {
            $nombre = toba::consulta_php('co_personas')->get_datos_persona($dat['proponente']);
            $dat['nombre_completo'] = $nombre['nombre_completo'];
            if (isset($dat['evaluador'])) {
                $nombre = toba::consulta_php('co_personas')->get_datos_persona($dat['evaluador']);
                $dat['evaluador_nombre'] = $nombre['nombre_completo'];
            }
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
        //$datos_ordenados = rs_ordenar_por_columna($aux, 'numero');
        $cuadro->set_datos($aux);
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