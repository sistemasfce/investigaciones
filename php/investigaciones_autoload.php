<?php
/**
 * Esta clase fue y será generada automáticamente. NO EDITAR A MANO.
 * @ignore
 */
class investigaciones_autoload 
{
	static function existe_clase($nombre)
	{
		return isset(self::$clases[$nombre]);
	}

	static function cargar($nombre)
	{
		if (self::existe_clase($nombre)) { 
			 require_once(dirname(__FILE__) .'/'. self::$clases[$nombre]); 
		}
	}

	static protected $clases = array(
		'investigaciones_ci' => 'extension_toba/componentes/investigaciones_ci.php',
		'investigaciones_cn' => 'extension_toba/componentes/investigaciones_cn.php',
		'investigaciones_datos_relacion' => 'extension_toba/componentes/investigaciones_datos_relacion.php',
		'investigaciones_datos_tabla' => 'extension_toba/componentes/investigaciones_datos_tabla.php',
		'investigaciones_ei_arbol' => 'extension_toba/componentes/investigaciones_ei_arbol.php',
		'investigaciones_ei_archivos' => 'extension_toba/componentes/investigaciones_ei_archivos.php',
		'investigaciones_ei_calendario' => 'extension_toba/componentes/investigaciones_ei_calendario.php',
		'investigaciones_ei_codigo' => 'extension_toba/componentes/investigaciones_ei_codigo.php',
		'investigaciones_ei_cuadro' => 'extension_toba/componentes/investigaciones_ei_cuadro.php',
		'investigaciones_ei_esquema' => 'extension_toba/componentes/investigaciones_ei_esquema.php',
		'investigaciones_ei_filtro' => 'extension_toba/componentes/investigaciones_ei_filtro.php',
		'investigaciones_ei_firma' => 'extension_toba/componentes/investigaciones_ei_firma.php',
		'investigaciones_ei_formulario' => 'extension_toba/componentes/investigaciones_ei_formulario.php',
		'investigaciones_ei_formulario_ml' => 'extension_toba/componentes/investigaciones_ei_formulario_ml.php',
		'investigaciones_ei_grafico' => 'extension_toba/componentes/investigaciones_ei_grafico.php',
		'investigaciones_ei_mapa' => 'extension_toba/componentes/investigaciones_ei_mapa.php',
		'investigaciones_servicio_web' => 'extension_toba/componentes/investigaciones_servicio_web.php',
		'investigaciones_comando' => 'extension_toba/investigaciones_comando.php',
		'investigaciones_modelo' => 'extension_toba/investigaciones_modelo.php',
	);
}
?>