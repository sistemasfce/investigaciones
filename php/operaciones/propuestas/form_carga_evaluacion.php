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