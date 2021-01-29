<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_miprogreso'),
            get_string('descconfig', 'block_miprogreso')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'simplehtml/Allow_HTML',
            get_string('labelallowhtml', 'block_miprogreso'),
            get_string('descallowhtml', 'block_miprogreso'),
            '0'
        ));