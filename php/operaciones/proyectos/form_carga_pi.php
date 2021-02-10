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
            if (this.ef('ambito').get_estado() == 8){
                this.ef('proyecto').mostrar();
                this.controlador.dep('form_ml_prog').ocultar();
            }
            else{
                if (this.ef('ambito').get_estado() == 6) {
                    this.ef('proyecto').ocultar();     
                    this.controlador.dep('form_ml_prog').mostrar();
                }
                else {
                    this.ef('proyecto').ocultar(); 
                    this.controlador.dep('form_ml_prog').ocultar();
                }
            }
        }
            
        //---- Procesamiento de EFs --------------------------------
        {$this->objeto_js}.evt__ambito__procesar = function(es_inicial)
        {
            if (this.ef('ambito').get_estado() == 8){
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