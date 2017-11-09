<?php
class ci_copadece_proyectos extends investigaciones_ci
{
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(investigaciones_ei_cuadro $cuadro)
	{
		$where = 'proyectos.proyecto_estado = 6';
		$datos = toba::consulta_php('co_proyectos')->get_proyectos_por_estado($where);
		$cuadro->set_datos($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__procesar()
	{
		$mensaje = 'Tengo el agrado de dirigirme a Uds. a efectos de solicitarle tenga a bien
			consultar a los docentes que se detallan mas abajo sobre la factibilidad de ser
			convocados a participar como evaluador de proyectos de investigación de nuestra Facultad.
			De ser posible, solicito tenga bien enviar su respuesta al mail';
		$remitente = $this->s__solicitante['solicitante'];
		$universidad = $this->s__solicitante['universidad'];
		$remitente_mail = $this->s__solicitante['solicitante_mail'];
		
		if (!isset($remitente) or !isset($remitente) or !isset($remitente)) {
			toba::notificacion()->agregar("Los datos de remitente son obligatorios","error");
			return;
		}
		
		if (isset($this->s__docentes)) {
			$proyectos = '';
			foreach($this->s__docentes as $doc) {
				$proyectos.= '<p>'.$doc['titulo'].'</p>';
			}
		}
		
		
		$asunto = 'Solicitud de docente para evaluar proyectos';
		$cuerpo_mail = '<p>'.$mensaje.' '.$remitente_mail.'. Cordialmente '.$remitente.' - '.$universidad.$proyectos.'</p>';
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
}
?>