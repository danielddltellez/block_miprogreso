<?php
 
class block_miprogreso_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        // Section header title according to language file.
        /*$mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 		
 		// A sample string variable with a default value.
        $mform->addElement('text', 'config_text', get_string('Título del bloque', 'block_miprogreso'));
        $mform->setDefault('config_text', 'default value');
        $mform->setType('config_text', PARAM_RAW);     
       
       // A sample string variable with a default value.
   		 $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_miprogreso'));
    		$mform->setDefault('config_title', 'default value');
    	$mform->setType('config_title', PARAM_TEXT);   */
        if (! empty($this->config->text)) {
	    $this->content->text = $this->config->text;
		}    
    }
    public function specialization() {
    	if (isset($this->config)) {
        	if (empty($this->config->title)) {
            	$this->title = get_string('defaulttitle', 'block_miprogreso');            
        	} else {
            	$this->title = $this->config->title;
        	}
        	if (empty($this->config->text)) {
            	$this->config->text = get_string('defaulttext', 'block_miprogreso');
        	}    
    	}
	}
	public function instance_allow_multiple() {
 		return true;
	}
}