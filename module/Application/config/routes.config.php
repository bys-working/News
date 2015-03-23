<?php

return array(
    'home' => array(
        'type' => 'Zend\Mvc\Router\Http\Literal',
        'options' => array(
            'route' => '/',
            'defaults' => array(
                'controller' => 'zfcuser',
                'action' => 'index',
            ),
        ),
    ),
    'listNews' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/listNews',
            'resource' => 'route/listNews',
            'defaults' => array(
                'controller' => 'Application\Controller\News',
                'action' => 'listNews',
            ),
        ),
    ),
    'listNotApprovedNews' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/listNotApprovedNews',
            'resource' => 'route/listNotApprovedNews',
            'defaults' => array(
                'controller' => 'Application\Controller\News',
                'action' => 'listNotApprovedNews',
            ),
        ),
    ),
    'addNews' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/addNews',
            'resource' => 'route/addNews',
            'defaults' => array(
                'controller' => 'Application\Controller\News',
                'action' => 'addNews',
            ),
        ),
    ),
    'approveNews' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/approveNews[/:id]',
            'resource' => 'route/approveNews',
            'defaults' => array(
                'controller' => 'Application\Controller\News',
                'action' => 'approveNews',
            ),
        ),
    ),
    'newsJSON' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/newsJSON',
            'resource' => 'route/newsJSON',
            'defaults' => array(
                'controller' => 'Application\Controller\News',
                'action' => 'newsJSON',
            ),
        ),
    ),
    'newsXml' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/newsXml',
            'resource' => 'route/newsXml',
            'defaults' => array(
                'controller' => 'Application\Controller\News',
                'action' => 'newsXml',
            ),
        ),
    ),
    
     /********************************  Users  *****************************************/

    'users' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/users',
            'resource' => 'route/users',
            'defaults' => array(
                'controller' => 'Application\Controller\User',
                'action' => 'users',
            ),
        ),
    ),
    'editUser' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/editUser[/:id]',
            'resource' => 'route/editUser',
            'defaults' => array(
                'controller' => 'Application\Controller\User',
                'action' => 'editUser',
            ),
        ),
    ),
    'userPermissions' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/userPermissions[/:id]',
            'resource' => 'route/userPermissions',
            'defaults' => array(
                'controller' => 'Application\Controller\User',
                'action' => 'userPermissions',
            ),
        ),
    ),

    /********************************  Roles  *****************************************/

    'roles' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/roles',
            'resource' => 'route/roles',
            'defaults' => array(
                'controller' => 'Application\Controller\Role',
                'action' => 'roles',
            ),
        ),
    ),
    'addRole' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/addRole',
            'resource' => 'route/addRole',
            'defaults' => array(
                'controller' => 'Application\Controller\Role',
                'action' => 'addRole',
            ),
        ),
    ),
    'editRole' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/editRole[/:id]',
            'resource' => 'route/editRole',
            'defaults' => array(
                'controller' => 'Application\Controller\Role',
                'action' => 'editRole',
            ),
        ),
    ),
    'deleteRole' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/deleteRole[/:id]',
            'resource' => 'route/deleteRole',
            'defaults' => array(
                'controller' => 'Application\Controller\Role',
                'action' => 'deleteRole',
            ),
        ),
    ),
    'rolePermissions' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/rolePermissions[/:id]',
            'resource' => 'route/rolePermissions',
            'defaults' => array(
                'controller' => 'Application\Controller\Role',
                'action' => 'rolePermissions',
            ),
        ),
    ),

    /********************************  Resources  *****************************************/

    'resources' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/resources',
            'resource' => 'route/resources',
            'defaults' => array(
                'controller' => 'Application\Controller\Resource',
                'action' => 'resources',
            ),
        ),
    ),
    'addResource' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/addResource[/:resource]',
            'resource' => 'route/addResource',
            'defaults' => array(
                'controller' => 'Application\Controller\Resource',
                'action' => 'addResource',
            ),
        ),
    ),
    'deleteResource' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/deleteResource[/:id]',
            'resource' => 'route/deleteResource',
            'defaults' => array(
                'controller' => 'Application\Controller\Resource',
                'action' => 'deleteResource',
            ),
        ),
    ),
    'scanResources' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/scanResources',
            'resource' => 'route/scanResources',
            'defaults' => array(
                'controller' => 'Application\Controller\Resource',
                'action' => 'scanResources',
            ),
        ),
    ),

    
    // The following is a route to simplify getting started creating
    // new controllers and actions without needing to create a new
    // module. Simply drop new controllers in, and you can access them
    // using the path /application/:controller/:action
    'application' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/application',
            'defaults' => array(
                '__NAMESPACE__' => 'Application\Controller',
                'controller' => 'Index',
                'action' => 'index',
            ),
        ),
        'may_terminate' => true,
        'child_routes' => array(
            'default' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/[:controller[/:action]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                    ),
                ),
            ),
        ),
    ),
);
