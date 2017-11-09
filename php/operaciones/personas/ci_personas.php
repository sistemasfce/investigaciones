<?php
class ci_personas extends investigaciones_ci
{
	
	//-------------------------------------------------------------------------
	function relacion()
	{   
		return $this->controlador->dep('relacion');
	}   
	
	//-------------------------------------------------------------------------
	function tabla($id)
	{   
		return $this->controlador->dep('relacion')->tabla($id);    
	}   
		
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(investigaciones_ei_cuadro $cuadro)
	{
		$where = $this->dep('filtro')->get_sql_where();
		$datos = toba::consulta_php('co_personas')->get_personas($where); 
		$cuadro->set_datos($datos);

	}

	function evt__cuadro__seleccion($seleccion)
	{
		$this->relacion()->cargar($seleccion);
		$this->set_pantalla('edicion');
		
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


	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form(investigaciones_ei_formulario $form)
	{
		if ($this->relacion()->esta_cargada()) {
			$datos = $this->tabla('investigadores')->get();
			$form->set_datos($datos);
		}
	}

	function evt__form__modificacion($datos)
	{
		$this->tabla('investigadores')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- form_ml ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml(investigaciones_ei_formulario_ml $form_ml)
	{
		if ($this->relacion()->esta_cargada()) {
			$datos = $this->tabla('investigadores_categorias')->get_filas();
			$form_ml->set_datos($datos);
		}
	}

	function evt__form_ml__modificacion($datos)
	{
		$this->tabla('investigadores_categorias')->procesar_filas($datos);
	}    
	
	//-----------------------------------------------------------------------------------
	//---- eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	

	function evt__agregar()
	{
		$this->set_pantalla('edicion');
	}

	function evt__cancelar()
	{
		$this->dep('relacion')->resetear();
		$this->set_pantalla('seleccion');
	}

	function evt__eliminar()
	{
		try {
			$this->dep('relacion')->eliminar_todo();
			$this->set_pantalla('seleccion');
		} catch (toba_error $e) {
			toba::notificacion()->agregar('No es posible eliminar el registro.');
		}
	}

	function evt__guardar()
	{
		try {
			$this->dep('relacion')->sincronizar();
			$this->dep('relacion')->resetear();
		} catch (toba_error $e) {
			$this->informar_msg('Error al dar de alta usuario - '. $e->get_mensaje());
			return;
		}
		$this->set_pantalla('seleccion');
	}
}
?>