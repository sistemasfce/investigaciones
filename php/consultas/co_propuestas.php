<?php
 
class co_propuestas
{
    function get_ultimo_numero()
    {
        $sql = "SELECT numero FROM propuestas WHERE numero <> '' ORDER BY propuesta DESC LIMIT 1";
        return toba::db()->consultar_fila($sql);
    }   

    function get_propuestas($where='1=1')
    {
	    $sql = "SELECT *
		    FROM propuestas
                    WHERE $where
        ";
	return toba::db()->consultar($sql);
    }    

}
?>
