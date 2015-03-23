<?php

namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class News
{
    public $id;
    public $user_id;
    public $title;
    public $description;
    public $image;
    public $approved;
    protected $inputFilter;
    
    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->image = (isset($data['image'])) ? $data['image'] : null;
        $this->approved = (isset($data['approved'])) ? $data['approved'] : 0;
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'approved' => $this->approved,
        );
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                    'name' => 'title',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                    'name' => 'description',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
            )));

            $inputFilter->add($factory->createInput(array(
                    'name' => 'id',
                    'required' => false,
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function getArrayCopy()
    {
        return $this->toArray();
    }
}
