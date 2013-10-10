<?php

return array(
    'containers' => array(
        'one' => array(
            'containers' => array(
                'two' => array(
                    'types' => array(
                        'three' => array(
                            'name' => 'threetype',
                            'fields' => array(),
                            'storage' => array(
                                'classname' => 'go\Tests\I18n\Items\mocks\TStorage',
                                'param' => 'value',
                            ),
                        ),
                    ),
                ),
            ),
            'types' => array(
                'four' => array(
                    'fields' => array(),
                ),
            ),
            'storage' => 'go\Tests\I18n\Items\mocks\TStorage',
        ),
    ),
    'types' => array(
        'invalid' => array(
            'fields' => array(),
        ),
    ),
);
