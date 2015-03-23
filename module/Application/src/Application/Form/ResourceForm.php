<?php

namespace Application\Form;

use Zend\Form\Form;

class ResourceForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('ResourceForm');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'resource',
            'type' => 'text',
            'options' => array(
                'label' => 'Resource:',
            ),
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
            ),
        ));
    }

}