<?php
class form_carga_pi extends investigaciones_ei_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

    
    function extender_objeto_js()
    {
        echo "
        {$this->objeto_js}.ini = function(es_inicial)
        {
            this.ef('proyecto').ocultar();
         
        }
            
        //---- Procesamiento de EFs --------------------------------
        {$this->objeto_js}.evt__ambito___procesar = function(es_inicial)
        {
            if (this.ef('ambito_').get_estado() == 8){
                this.ef('proyecto').mostrar();
                this.controlador.dep('form_ml_prog').ocultar();
           }
            else{
                this.ef('proyecto').ocultar();     
                this.controlador.dep('form_ml_prog').mostrar();
                 }
        }            
        ";
    }
}
?>