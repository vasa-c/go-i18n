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
                                'testid' => '#3',
                                'table' => 'i18n_three',
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
            'storage' => array(
                'classname' => 'go\Tests\I18n\Items\mocks\TStorage',
                'testid' => '#1',
                'table' => 'i18n_one',
            ),
        ),
    ),
    'types' => array(
        'invalid' => array(
            'fields' => array(),
        ),
    ),
);
