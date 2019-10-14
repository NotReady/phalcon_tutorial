<?php
use Phalcon\Config;

return new Config(
    [
        'application' => [
            'controllersDir' => '/app/controllers/',
            'modelsDir' => '/app/models/',
            'viewsDir' => 'views',
            'formsDir' => '/app/forms/',
        ]
    ]
)
?>