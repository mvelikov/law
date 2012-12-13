<?php

class Application_Form_InsertCase extends Zend_Form
{
    public function init()
    {}
    private function insertForm($values)
    {
        $form = array();
        $form['type'] = $this->createElement('select', 'type');
        $form['type']   ->setLabel('Дело/НБПП')
                ->addMultiOption('', 'Избери')
                ->addMultiOptions($values['types'])
                ->setRequired(true);

        $form['town'] = $this->createElement('select', 'town');
        $form['town']   ->setLabel('Град')
                ->addMultiOption('', 'Избери')
                ->addMultiOptions($values['towns'])
                ->AddValidator('NotEmpty')
                ->AddValidator('Digits')
                ->setRequired(true);

        $form['court'] = $this->createElement('select', 'court');
        $form['court']  ->setLabel('Съд')
                ->addMultiOption('', 'Избери')
                ->AddMultiOptions($values['courts'])
                ->AddValidator('Digits')
                ->AddValidator('NotEmpty')
                ->setRequired(true);

        $form['case'] = $this->createElement('select', 'case');
        $form['case']
                ->setLabel('Тип')
                ->setRequired(true)
                ->addMultiOption('', 'Избери')
                ->addMultiOptions($values['cases'])
                ->AddValidator('Digits')
                ->AddValidator('NotEmpty');

        $form['number'] = $this->createElement('text', 'number');
        $form['number'] ->setLabel('Номер (номер/година)')
                ->setRequired(true)
                ->addValidator('regex', false, array('/^\d+\/\d{4}$/'))
                ->AddValidator('NotEmpty');
                // ->AddValidator('alnum')
                // ->addFilter("StringTrim")
                // ->addFilter("Alnum");

        $form['claimant'] = $this->createElement('text', 'claimant');
        $form['claimant']
                ->setLabel('Ищец')
                ->setRequired(true)
                ->AddValidator('NotEmpty')
                ->AddValidator('alnum')
                ->addFilter("StringTrim")
                ->addFilter("Alnum");

        $form['defendant'] = $this->createElement('text', 'defendant');
        $form['defendant']  ->setLabel('Ответник')
                    ->setRequired(true)
                    ->AddValidator('NotEmpty')
                    ->AddValidator('alnum')
                    ->addFilter("StringTrim")
                    ->addFilter("Alnum");
        $form['date'] = $this->createElement('text', 'date');
        $form['date']
                ->setLabel('Дата')
                ->setRequired(true)
                ->AddValidator('NotEmpty')
                ->addFilter("StringTrim")
                ->setAttrib('data-role', 'datebox')
                ->setAttrib('data-options', '{"mode": "calbox", "calStartDay": 1, "afterToday" : true,  "closeCallback": "$(\"#hour\").datebox(\"open\");"}');

        $form['hour'] = $this->createElement("text", "hour");
        $form['hour']
                ->setLabel('Час')
                ->setRequired(true)
                ->AddValidator('NotEmpty')
                ->addFilter("StringTrim")
                ->setAttrib('data-role', 'datebox')
                ->setAttrib('data-options', '{"mode" : "timebox", "timeFormat": 12, "minuteStep" : 5}');

        return $form;
    }
    public function startForm($values)
    {
        $this->setMethod('post');

        $this->addElements($this->insertForm($values));
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Запиши',
        ));
    }

    public function editForm($values)
    {
        $this->setMethod('post');

        $form = array();
        $form['date'] = $this->createElement('text', 'date');
        $form['date']->setLabel('Нова дата')
                        ->setRequired(true)
                        ->AddValidator('NotEmpty')
                        ->addFilter("StringTrim")
                        ->setAttrib('data-role', 'datebox')
                        ->setAttrib('data-options', '{"mode": "calbox", "calStartDay": 1,  "afterToday" : true, "closeCallback": "$(\"#hour\").datebox(\"open\");"}');
        $form['hour'] = $this->createElement("text", "hour");
        $form['hour']->setLabel('Нов час')
                        ->setRequired(true)
                        ->AddValidator('NotEmpty')
                        ->addFilter("StringTrim")
                        ->setAttrib('data-role', 'datebox')
                        ->setAttrib('data-options', '{"mode" : "timebox", "timeFormat": 12, "minuteStep" : 5}');
        $insertForm = $this->insertForm($values);
        // unset($insertForm['date'], $insertForm['hour']);
        $this->addElements(array_merge($insertForm, $form));

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Запиши',
        ));
    }
}