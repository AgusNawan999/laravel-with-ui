<?php

return [
    'characters' => ['1', '2', '3', '4', '6', '7', '8', '9', '0'],
    'default' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'math' => false,
        'expire' => 60,
        'encrypt' => false,
        'bgColor' => '#ffffff',
    ],
    'math' => [
        'length' => 9,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'math' => true,
    ],

    'flat' => [
        'length' => 5,
        'width' => 130,
        'height' => 45,
        'quality' => 90,
        'lines' => -1,
        'angle' => -10,
        'bgImage' => false,
        'bgColor' => '#ffffff',
        'fontColors' => ['#333333','#444444','#555555'],
        'contrast' => -5,
        'encrypt' => false,
        'text' => 'abc'
    ],
    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
    ],
    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => true,
        'contrast' => -5,
    ]
];
