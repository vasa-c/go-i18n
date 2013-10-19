<?php

return array(
    'ru' => function ($number, $forms) {
        return $forms[$number % 3];
    },
    'en' => function ($number, $forms) {
        return $forms[$number % 2];
    },
);