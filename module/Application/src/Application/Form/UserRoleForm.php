<?php

namespace Application\Form;

use Zend\Form\Form;

class UserRoleForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('RoleForm');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'role_id',
            'type' => 'select',
            'options' => array(
                'label' => 'Role:',
            ),
        ));

        $this->add(array(
            'name' => 'user_id',
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