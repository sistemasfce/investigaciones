<?php
class ci_copadece_investigadores extends investigaciones_ci
{
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(investigaciones_ei_cuadro $cuadro)
	{
		$where = $this->dep('filtro')->get_sql_where();
		$datos = toba::consulta_php('co_personas')->get_copadece_investigadores($where);
		$cuadro->set_datos($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__procesar()
	{
		$mensaje = 'Tengo el agrado de dirigirme a Uds. a efectos de solicitarle información sobre los 
				proyectos de investigación citados abajo.
			De ser posible, solicito tenga bien enviar su respuesta al mail';
		$remitente = $this->s__solicitante['solicitante'];
		$universidad = $this->s__solicitante['universidad'];
		$remitente_mail = $this->s__solicitante['solicitante_mail'];
		
		if (!isset($remitente) or !isset($remitente) or !isset($remitente)) {
			toba::notificacion()->agregar("Los datos de remitente son obligatorios","error");
			return;
		}
		
		if (isset($this->s__docentes)) {
			$docentes = '';
			foreach($this->s__docentes as $doc) {
				$docentes.= '<p>'.$doc['nombre'].'</p>';
			}
		}
		
		
		$asunto = 'Solicitud de información sobre proyectos';
		$cuerpo_mail = '<p>'.$mensaje.' '.$remitente_mail.'. Cordialmente '.$remitente.' - '.$universidad.$docentes.'</p>';
		//secinvestigacion.fce@gmail.com
		//departamentostw@economicasunp.edu.ar
		try {
			$mail = new toba_mail('secinvestigacion.fce@gmail.com', $asunto, $cuerpo_mail);
			$mail->set_configuracion_smtp('plantadocente');
			$mail->set_html(true);
			$mail->enviar();
			toba::notificacion()->agregar("El E-Mail se envió correctamente","info");
			} catch(Exception $e) {
				echo $e->getMessage();
			}
	}

	function evt__cuadro__marcar($datos)
	{
		$this->s__docentes = $datos;
	}
	
	function evt__cancelar()
	{
		header ("Location: http://www.economicasunp.edu.ar/copadece");
	}

	//-----------------------------------------------------------------------------------
	//---- form -------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------


	function evt__form__modificacion($datos)
	{
		$this->s__solicitante = $datos;
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
?>
