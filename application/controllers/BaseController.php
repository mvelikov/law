<?php

class BaseController extends Zend_Controller_Action
{
    protected $_homeMenu = array(
        array(  'controller' => 'index',
                'action'     => 'search',
                'label'      => 'Търси Дело',
                'options'    => array(
                    'type' => '1'
                ),
        ),
        array(
            'controller' => 'index',
            'action'     => 'search',
            'label'      => 'Търси НБПП',
            'options'    => array(
                'type' => '2'
            ),
        ),
        array(
            'controller' => 'index',
            'action'     => 'insert-case',
            'label'      => 'Въведи ново дело',
            'options'    => array(
                // 'type' => 'НБПП'
            ),
        ),
        array(
            'controller' => 'index',
            'action'     => 'archive',
            'label'      => 'Архив',
            'options'    => array(
                // 'type' => 'НБПП'
            ),
        )
    );
    protected $_types = array(
        1 => "Дело",
        2 => "НБПП",
    );

    protected $_towns = array(
        1 => "Варна",
        2 => "Провадия",
        3 => "София",
        4 => "Шумен",
        5 => "Девня"
    );
    protected $_cases = array(
        1 => 'Граждански',
        2 => 'Наказателни',
        3 => 'Административни',
        4 => 'Нотариални',
        5 => 'Брачни'
    );
    protected $_home;
    protected $_courts = array(
        1 => "Районен",
        2 => "Окръжен",
        3 => "Касационен",
        4 => "Административен"
    );
    protected $_city;
    protected $_case;
    protected $_type;
    protected $_number;
}