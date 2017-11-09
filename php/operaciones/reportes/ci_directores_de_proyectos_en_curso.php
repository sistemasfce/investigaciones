<?php
class ci_directores_de_proyectos_en_curso extends investigaciones_ci
{
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(investigaciones_ei_cuadro $cuadro)
	{
		$datos = toba::consulta_php('co_proyectos')->get_directores_en_curso(); 
		$cuadro->set_datos($datos);

	}    
}
?>