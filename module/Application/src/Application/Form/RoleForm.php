<?php

namespace Application\Form;

use Zend\Form\Form;

class RoleForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('RoleForm');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'role_id',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Role:',
            ),
        ));

        $this->add(array(
            'name' => 'parent',
            'type' => 'hidden',
            'attributes' => array(
                'value' => 'guest',
            )
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Save',
                'id' => 'submitButton',
                'class' => 'btn btn-success',
            ),
        ));
        
    }

}