<?php
 
class co_propuestas
{
    function get_proximo_numero()
    {
	    $sql = "SELECT MAX(propuesta) + 1 as propuesta
		    FROM propuestas
        ";
	return toba::db()->consultar_fila($sql);
    }

}
?>
