<?php

return array(
    'default' => array(
        array(
            'label' => 'News',
            'route' => 'listNews',
            'pages' => array(
                array(
                    'label' => 'List News',
                    'route' => 'listNews',
                ),
                array(
                    'label' => 'Add news',
                    'route' => 'addNews',
                ),
                array(
                    'label' => 'News in JSON',
                    'route' => 'newsJSON',
                ), 
                array(
                    'label' => 'News in Xml',
                    'route' => 'newsXml',
                ), 
                
            ),
        ),
    ),
    'settings' => array(
        array(
            'label' => 'Settings',
            'route' => 'users',
            'pages' => array(
                array(
                    'label' => 'List not approved news',
                    'route' => 'listNotApprovedNews',
                ),
                array(
                    'label' => 'Users',
                    'route' => 'users',
                ),
                array(
                    'label' => 'Roles',
                    'route' => 'roles',
                ),
                array(
                    'label' => 'Resources',
                    'route' => 'resources',
                ),
                array(
                    'label' => 'Scan resources',
                    'route' => 'scanResources',
                ),
            )
        ),
    ),
);
