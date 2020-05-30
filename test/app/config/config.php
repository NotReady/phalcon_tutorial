<?php
use Phalcon\Config;

return new Config(
    [
        'application' => [
            'controllersDir' => '/app/controllers/',
            'modelsDir' => '/app/models/',
            'viewsDir' => '/app/views/',
            'formsDir' => '/app/forms/',
            'servicesDir' => '/app/services/',
            'helpersDir' => '/app/helpers/',
            'cacheDir' => '/app/cache/',
        ]
    ]
)
?>