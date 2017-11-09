<?php
 
class co_parametros
{
    function get_ubicaciones($where='1=1')
    {
        $sql = "SELECT *
		FROM ubicaciones 
		WHERE $where
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



}
?>
