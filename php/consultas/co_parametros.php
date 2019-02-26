<?php
 
class co_parametros
{
    // listado de ubicaciones
    function get_ubicaciones($where='1=1')
    {
        $sql = "SELECT *
		FROM ubicaciones 
		WHERE $where AND ubicacion <> 4
        ";
	return toba::db()->consultar($sql);
    }
    
    function get_categorias($where='1=1')
    {
        $sql = "SELECT *
		FROM categorias
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }

    function get_roles($where='1=1')
    {
        $sql = "SELECT *
		FROM roles
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }

    function get_aptitudes($where='1=1')
    {
        $sql = "SELECT *
		FROM aptitudes
		WHERE $where
        ";
	return toba::db()->consultar($sql);
    }

    function get_estados($where='1=1')
    {
        $sql = "SELECT *
		FROM proyectos_estados
		WHERE $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_proyectos_tipos($where='1=1')
    {
        $sql = "SELECT *
		FROM proyectos_tipos
		WHERE $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }

    function get_propuestas_estados($where='1=1')
    {
        $sql = "SELECT *
		FROM propuestas_estados
		WHERE $where
		ORDER BY descripcion
        ";
	return toba::db()->consultar($sql);
    }
}
?>
