<?php
namespace Application\Form;

use Zend\Form\Form;

class NewsForm extends Form
{
    public function __construct()
    {
        parent::__construct('NewsForm');

        $this->setAttribute('method', 'post');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Title:',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type' => 'textarea',
            ),
            'options' => array(
                'label' => 'Description:',
            ),
        ));

        $this->add(array(
            'name' => 'image',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'Image:',
            ),
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));
        
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