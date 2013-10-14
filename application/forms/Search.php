<?php 

class Application_Form_Search extends Zend_Form
{
    public function init()
    {
        
    }

    public function startForm($values)
    {
        $this->setMethod('post');

        $this->addElement('hidden', 'type', array(
            'required' => true,
            'value' => $values['type']
        ));
        $this->addElement('text', 'number', array(
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                array(
                    'validator' => 'NotEmpty',
                    'breakChainOnFailure' => true
                ),
                array(
                    "validator" => 'regex', 
                    'options' => array('/^\d+\/\d{4}$/')
                ),
            ),
            'label' => 'Номер Дело'
        ));
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Търси',
            'value' => 'Value'
        ));
    }
}