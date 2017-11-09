<?php
class ci_proyectos extends investigaciones_ci
{
	protected $s__filtro;
	
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
		$datos = toba::consulta_php('co_proyectos')->get_proyectos($where); 
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
			$datos = $this->tabla('proyectos')->get();
			// si esta cargada informe_1 armo el link para descarga
			if ($datos['entrego_informe_1'] == 'S') {
				// el 23 es para que corte la cadena despues del caracter 19, de /home/fce/informes_inv/
				$nombre = substr($datos['informe_1_path'],23);
				$dir_tmp = toba::proyecto()->get_www_temp();
				exec("cp '". $datos['informe_1_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
				$temp_archivo = toba::proyecto()->get_www_temp($nombre);
				$tamanio = round(filesize($temp_archivo['path']) / 1024);
				$datos['informe_1_path_v'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
				$datos['informe_1'] = $nombre. ' - Tam.: '.$tamanio. ' KB';  
			}
			if ($datos['entrego_informe_2'] == 'S') {
				// el 23 es para que corte la cadena despues del caracter 19, de /home/fce/informes_inv/
				$nombre = substr($datos['informe_2_path'],23);
				$dir_tmp = toba::proyecto()->get_www_temp();
				exec("cp '". $datos['informe_2_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
				$temp_archivo = toba::proyecto()->get_www_temp($nombre);
				$tamanio = round(filesize($temp_archivo['path']) / 1024);
				$datos['informe_2_path_v'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
				$datos['informe_2'] = $nombre. ' - Tam.: '.$tamanio. ' KB';  
			} 
				if ($datos['entrego_informe_3'] == 'S') {
				// el 23 es para que corte la cadena despues del caracter 19, de /home/fce/informes_inv/
				$nombre = substr($datos['informe_3_path'],23);
				$dir_tmp = toba::proyecto()->get_www_temp();
				exec("cp '". $datos['informe_3_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
				$temp_archivo = toba::proyecto()->get_www_temp($nombre);
				$tamanio = round(filesize($temp_archivo['path']) / 1024);
				$datos['informe_3_path_v'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
				$datos['informe_3'] = $nombre. ' - Tam.: '.$tamanio. ' KB';  
			}             
			if ($datos['entrego_proyecto'] == 'S') {
				// el 23 es para que corte la cadena despues del caracter 19, de /home/fce/informes_inv/
				$nombre = substr($datos['proyecto_path'],23);
				$dir_tmp = toba::proyecto()->get_www_temp();
				exec("cp '". $datos['proyecto_path']. "' '" .$dir_tmp['path']."/".$nombre."'");
				$temp_archivo = toba::proyecto()->get_www_temp($nombre);
				$tamanio = round(filesize($temp_archivo['path']) / 1024);
				$datos['proyecto_path_v'] = "<a href='{$temp_archivo['url']}'target='_blank'>Descargar archivo</a>";
				$datos['proyecto'] = $nombre. ' - Tam.: '.$tamanio. ' KB';  
			}
			$form->set_datos($datos);
		}
	}

	function evt__form__modificacion($datos)
	{
		if (isset($datos['informe_1'])) {
			$nombre_archivo = $datos['informe_1']['name'];
			if ($datos['numero_pi'] != '')
				$nuevo = $datos['numero_pi'];
			else 
				$nuevo = substr($datos['titulo'],0,50);
			$nuevo = $this->sanear_string($nuevo);
			$nombre_nuevo = 'INF1_'.$nuevo.'.pdf';   
			$destino = '/home/fce/informes_inv/'.$nombre_nuevo;
		
			move_uploaded_file($datos['informe_1']['tmp_name'], $destino);   
			$datos['informe_1_path'] = $destino;   
			$datos['entrego_informe_1'] = 'S'; 
		}
		if (isset($datos['informe_2'])) {
			$nombre_archivo = $datos['informe_2']['name'];
			if ($datos['numero_pi'] != '')
				$nuevo = $datos['numero_pi'];
			else 
				$nuevo = substr($datos['titulo'],0,50);
			$nuevo = $this->sanear_string($nuevo);
			$nombre_nuevo = 'INF2_'.$nuevo.'.pdf';   
			$destino = '/home/fce/informes_inv/'.$nombre_nuevo;
	
			move_uploaded_file($datos['informe_2']['tmp_name'], $destino);   
			$datos['informe_2_path'] = $destino;   
			$datos['entrego_informe_2'] = 'S'; 
		}  
		if (isset($datos['informe_3'])) {
			$nombre_archivo = $datos['informe_3']['name'];
			if ($datos['numero_pi'] != '')
				$nuevo = $datos['numero_pi'];
			else 
				$nuevo = substr($datos['titulo'],0,50);
			$nuevo = $this->sanear_string($nuevo);
			$nombre_nuevo = 'INF3_'.$nuevo.'.pdf';   
			$destino = '/home/fce/informes_inv/'.$nombre_nuevo;
			
			move_uploaded_file($datos['informe_3']['tmp_name'], $destino);   
			$datos['informe_3_path'] = $destino;   
			$datos['entrego_informe_3'] = 'S'; 
		}        
		if (isset($datos['proyecto'])) {
			$nombre_archivo = $datos['proyecto']['name'];
			if ($datos['numero_pi'] != '')
				$nuevo = $datos['numero_pi'];
			else 
				$nuevo = substr($datos['titulo'],0,50);
			$nuevo = $this->sanear_string($nuevo);
			$nombre_nuevo = 'PROYECTO_'.$nuevo.'.pdf';   
			$destino = '/home/fce/informes_inv/'.$nombre_nuevo;

			move_uploaded_file($datos['proyecto']['tmp_name'], $destino);   
			$datos['proyecto_path'] = $destino;   
			$datos['entrego_proyecto'] = 'S'; 
		}       
		$this->tabla('proyectos')->set($datos);
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
		//try {
			$this->relacion()->sincronizar();
			$this->relacion()->resetear();
		//} catch (toba_error $e) {
		//    $this->informar_msg('Error al guardar proyecto - '. $e->get_mensaje());
		//    return;
		//}
		$this->set_pantalla('seleccion');
	}    
	//-----------------------------------------------------------------------------------
	//---- form_ml ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_ml(investigaciones_ei_formulario_ml $form_ml)
	{
		if ($this->relacion()->esta_cargada()) {
			$datos = $this->tabla('proyectos_rendiciones')->get_filas();
			$form_ml->set_datos($datos);
		}
	}

	function evt__form_ml__modificacion($datos)
	{
		$this->tabla('proyectos_rendiciones')->procesar_filas($datos);
	}
	
	
	function sanear_string($string)
	{
	
		$string = trim($string);
	
		$string = str_replace(
			array('�', '�', '�', '�', '�', '�', '�', '�', '�'),
			array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
			$string);
		$string = str_replace(
			array('�', '�', '�', '�', '�', '�', '�', '�'),
			array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
			$string);
		$string = str_replace(
			array('�', '�', '�', '�', '�', '�', '�', '�'),
			array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
			$string);
		$string = str_replace(
			array('�', '�', '�', '�', '�', '�', '�', '�'),
			array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
			$string);
		$string = str_replace(
			array('�', '�', '�', '�', '�', '�', '�', '�'),
			array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
			$string);
		$string = str_replace(
			array('�', '�', '�', '�'),
			array('n', 'N', 'c', 'C',),
			$string);
		
		//Esta parte se encarga de eliminar cualquier caracter extra�o
		$string = str_replace(
			array("(", ")", "?", "�", ";", ",", ":","."),'',
			$string);        

		// reemplazo los espacios por guion bajo
		$string = str_replace(' ','_',$string);
		return $string;
	}
}
?>