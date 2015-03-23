<?php

return array(
    'debug' => false,
    'buildOnRequest' => true,
//    'combine' => false,
    'cacheEnabled' => true,
    'cachePath' => __DIR__ . '/../../../data/cache/',
    // every page on 'page' router
    'routes' => array(
        'page' => array(
            '@base_js_libs',
            '@base_js',
            '@base_css',
        ),
        'options' => array(
            'mixin' => true,
        ),
    ),
    // even on not found pages
    'default' => array(
        'assets' => array(
            '@base_css',
            '@base_js_libs',
            '@base_js',
        ),
        'options' => array(
            'mixin' => true,
        ),
    ),
    'controllers' => array(
        'options' => array(
            'mixin' => true,
        ),
    ),
    'webPath' => getcwd() . '/public/assets',
    'basePath' => '/assets',
    'modules' => array(
        'Application' => array(
            'root_path' => __DIR__ . '/../assets',
            'collections' => array(

                'base_css' => array(
                    'assets' => array(
                        'css/bootstrap-theme.min.css',
                        'css/bootstrap.min.css',
                        'css/style.css',
                    ),
                    'filters' => array(
                        'CssRewriteFilter' => array(
                            'name' => 'Assetic\Filter\CssRewriteFilter'
                        ),
                        '?CssMinFilter' => array(
                            'name' => 'Assetic\Filter\CssMinFilter'
                        ),
                    ),
                    'options' => array(
                        'output' => 'base.css',
                    )
                ),
                'base_js_libs' => array(
                    'assets' => array(
                        'js/libs/jquery.min.js',
                        'js/libs/bootstrap.js',
                        'js/libs/html5shiv.js',
                        'js/libs/respond.min.js',
                    ),
                    'filters' => array(
                        '?JSMinFilter' => array(
                            'name' => 'Assetic\Filter\JSMinFilter'
                        ),
                    ),
                    'options' => array(
                    //'output' => 'file_name',
                    ),
                ),
                'base_js' => array(
                    'assets' => array(
                        'js/application/base.js',
                    ),
                    'filters' => array(
                        '?JSMinFilter' => array(
                            'name' => 'Assetic\Filter\JSMinFilter'
                        ),
                    ),
                    'options' => array(
                    //'output' => 'file_name',
                    ),
                ),
                'base_images' => array(
                    'assets' => array(
                        'fonts/*.*',
                        'img/*.*',
                    ),
                    'options' => array(
                        'move_raw' => true,
                    )
                ),
            ),
        ),
    ),
);
