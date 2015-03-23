<?php
namespace Application\Form;

use Zend\Form\Form;

class ActionForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('ActionForm');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'save',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Save',
                'id' => 'saveButton',
            ),
        ));
    }
}