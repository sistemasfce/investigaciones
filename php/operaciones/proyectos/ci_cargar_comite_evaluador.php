<?php
class ci_cargar_comite_evaluador extends investigaciones_ci
{ 
    protected $s__proyecto;
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
        $aux = array();
        $tipo = $cuadro->get_parametro('a');
        $where = $where . ' AND proyectos_inv.estado = 23 AND proyectos_inv.tipo = '.$tipo;
        $datos = toba::consulta_php('co_proyectos_inv')->get_proyectos($where);
        foreach ($datos as $dat) {
            $nombre = toba::consulta_php('co_personas')->get_datos_persona($dat['director']);
            $dat['nombre_completo'] = $nombre['nombre_completo'];
            if (isset($dat['carrera'])) {
                $nombre = toba::consulta_php('co_carreras')->get_carreras('carrera = '.$dat['carrera']);
                $dat['carrera_desc'] = $nombre[0]['nombre'];     
            }
            if (isset($dat['departamento'])) {
                $nombre = toba::consulta_php('co_carreras')->get_departamentos('departamento = '.$dat['departamento']);
                $dat['departamento_desc'] = $nombre[0]['descripcion'];     
            }    
            if (isset($dat['asignatura'])) {
                $nombre = toba::consulta_php('co_carreras')->get_asignaturas('actividad = '.$dat['asignatura']);
                $dat['asignatura_desc'] = $nombre[0]['descripcion'];     
            } 
            if (isset($dat['ambito'])) {
                $nombre = toba::consulta_php('co_carreras')->get_ambitos('ambito = '.$dat['ambito']);
                $dat['ambito_desc'] = $nombre[0]['descripcion'];     
            }             
            $aux[] = $dat;
        }
        //$datos_ordenados = rs_ordenar_por_columna($aux, 'numero');
        $cuadro->set_datos($aux);
    }

    function evt__cuadro__seleccion($seleccion)
    {
        $this->relacion()->cargar($seleccion);
        $this->s__proyecto = $seleccion;
        $this->set_pantalla('edicion');
    }    


    function conf__form(investigaciones_ei_formulario $form)
    {
        if ($this->relacion()->esta_cargada()) {
            $datos = $this->tabla('proyectos_inv')->get();
            $form->set_datos($datos);
        }
    }        

    function evt__form__modificacion($datos)
    {
        $datos['estado'] = 24; // pasa a estado en evaluacion
        $this->tabla('proyectos_inv')->set($datos);
   
        if (isset($datos['secretario_investigacion'])){
            $aux['proyecto']=$this->s__proyecto['proyecto'];
            $aux['persona']= $datos['secretario_investigacion'];
            $aux['rol']= 14;
            $aux['fecha_asignacion']= $datos['fecha_asignacion_sec'];
            $aux['apex_ei_analisis_fila'] = 'A';
            $comite[]= $aux;
        }
        if (isset($datos['investigador_categoria'])){
            $aux['proyecto']=$this->s__proyecto['proyecto'];
            $aux['persona']= $datos['investigador_categoria'];
            $aux['rol']= 11;
            $aux['fecha_asignacion']= $datos['fecha_asignacion_inv'];  
            $aux['apex_ei_analisis_fila'] = 'A';
            $comite[]= $aux;
        }
        if (isset($datos['especialista_disciplinar'])){
            $aux['proyecto']=$this->s__proyecto['proyecto'];
            $aux['persona']= $datos['especialista_disciplinar'];
            $aux['rol']= 15;
            $aux['fecha_asignacion']= $datos['fecha_asignacion_esp'];
            $aux['apex_ei_analisis_fila'] = 'A';
            $comite[]= $aux;
        }
       // ei_arbol($comite);
        if (isset($comite))
            $this->tabla('proyectos_inv_comite')->procesar_filas($comite);
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
    //---- Eventos ----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function evt__procesar()
    {
        
        $this->relacion()->sincronizar();
        $this->relacion()->resetear();
        $this->set_pantalla('seleccion');
    }

    function evt__cancelar()
    {
        $this->relacion()->resetear();
        $this->set_pantalla('seleccion');
    }
}