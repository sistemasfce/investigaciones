<?php
class form_carga_evaluacion extends investigaciones_ei_formulario
{
    //-----------------------------------------------------------------------------------
    //---- JAVASCRIPT -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function extender_objeto_js()
    {
        echo "
        {$this->objeto_js}.ini = function(es_inicial)
        {
            if (this.ef('tipo').get_estado() == 1) {
                this.ef('ubicacion').mostrar();
                this.ef('carrera').mostrar();
                this.ef('departamento').mostrar();
                this.ef('asignatura').mostrar();
                this.ef('ambito').ocultar();
                this.ef('incentivo').ocultar();
            } else {
                if (this.ef('tipo').get_estado() == 2) {
                    this.ef('ubicacion').mostrar();  
                    this.ef('ambito').mostrar();
                    this.ef('carrera').ocultar();
                    this.ef('departamento').ocultar();
                    this.ef('asignatura').ocultar();
                    this.ef('incentivo').ocultar();
                } else {
                    if (this.ef('tipo').get_estado() == 3) {    
                        this.ef('ubicacion').mostrar();
                        this.ef('incentivo').mostrar();
                        this.ef('carrera').ocultar();
                        this.ef('departamento').ocultar();
                        this.ef('asignatura').ocultar();
                        this.ef('ambito').ocultar();
                    } else {
                        this.ef('ubicacion').ocultar();
                        this.ef('carrera').ocultar();
                        this.ef('departamento').ocultar();
                        this.ef('asignatura').ocultar();
                        this.ef('ambito').ocultar();
                        this.ef('incentivo').ocultar();
                    }
                }
            }
            if (this.ef('proyecto_presentado').get_estado() == 'S') {
                this.ef('proyecto_fecha').mostrar();
                this.ef('proyecto_numero').mostrar();
            } else {
                this.ef('proyecto_fecha').ocultar();
                this.ef('proyecto_numero').ocultar();
            }
        } 

        //---- Procesamiento de EFs --------------------------------
        {$this->objeto_js}.evt__proyecto_presentado__procesar = function(es_inicial)
        {
            if (this.ef('proyecto_presentado').get_estado() == 'S') {
                this.ef('proyecto_fecha').mostrar();
                this.ef('proyecto_numero').mostrar();
            } else {
                this.ef('proyecto_fecha').ocultar();
                this.ef('proyecto_numero').ocultar();
            }
        }            
        ";
    }
}
?>