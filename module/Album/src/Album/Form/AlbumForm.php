<?php

namespace Album\Form;
use Zend\Form\Form;

class AlbumForm extends Form{
    public function __construct($name = null, $options = array()){
        parent::__construct("album");
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));
        
        $this->add(array(
            'name' => 'title',
            'type' => 'text', 
            'options' => array(
                'label' => 'Title',
            ),'attributes'=>array(
                    'class' => 'form-control',)
        ));
        
        $this->add(array(
            'name' => 'artist',
            'type' => 'text',
            'options' => array(
                'label' => 'Artist',
             ),'attributes'=>array(
                    'class' => 'form-control',)
        ));
        
        $this->add(array(
                'name' => 'file',
                'type' => 'file',
                'options' => array(
                    'label' => 'Image',
            ),'attributes'=>array(
                'class'=>'file-inputs',
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit', 
            'attributes' => array(
                'value' => 'Go',
                'id' => 'botao',
                'class' => 'btn btn-default btn-file',
            )
        ));
        
    }
}