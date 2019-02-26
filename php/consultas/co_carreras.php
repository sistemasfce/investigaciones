<?php
 
class co_carreras
{
    function get_carreras_ubicacion($ubicacion)
    {
        $sql = "SELECT  carreras.carrera,
                        carreras.nombre
		FROM carreras LEFT OUTER JOIN carreras_ubicaciones ON carreras.carrera = carreras_ubicaciones.carrera
		WHERE carreras_ubicaciones.ubicacion = $ubicacion
		ORDER BY nombre
        ";
	return toba::db('plantadb')->consultar($sql);
    }
   
    function get_asignaturas_departamento($carrera, $departamento)
    {
        $sql = "SELECT  actividades.actividad,
                        actividades.descripcion
		FROM actividades LEFT OUTER JOIN carreras_actividades ON actividades.actividad = carreras_actividades.actividad
		WHERE carreras_actividades.carrera = $carrera AND actividades.departamento = $departamento
                ORDER BY actividades.descripcion
        ";
	return toba::db('plantadb')->consultar($sql);
    }       
    
    function get_departamentos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM departamentos
		WHERE departamento in (1,2,3,4,5,6,8)
        ";
	return toba::db('plantadb')->consultar($sql);
    }
}
?>
