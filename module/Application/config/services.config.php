<?php

namespace Application;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceManager;

return array(
    'factories' => array(
        'dbAdapter' => function(ServiceManager $sm) {
            $config = $sm->get('Config');
            $dbAdapter = new \Zend\Db\Adapter\Adapter($config['db']);
            return $dbAdapter;
        },
        'dbCache' => function(ServiceManager $sm) {
            $config = $sm->get('Config');
            $cacheConfig = $config['cache_config'];
            $cache = \Zend\Cache\StorageFactory::factory($config[$cacheConfig]);
            return $cache;
        },
        'NewsTable' => function(ServiceManager $sm) {
            $tableGateway = $sm->get('NewsTableGateway');
            $config = $sm->get('Config');
            $cache = ($config['memcache']['enabled']) ? $sm->get('dbCache') : $cache = null;
            $table = new Model\NewsTable($tableGateway, $cache);
            return $table;
        },
        'NewsTableGateway' => function (ServiceManager $sm) {
            $dbAdapter = $sm->get('dbAdapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Model\News());
            return new TableGateway('news', $dbAdapter, null, $resultSetPrototype);
        },
 
            
        /*********************************************************************************************************************/        
                
        'UserTable' => function(ServiceManager $sm) {
            $tableGateway = $sm->get('UserTableGateway');
            $table = new Model\UserTable($tableGateway);
            return $table;
        },
        'UserTableGateway' => function(ServiceManager $sm) {
            $dbAdapter = $sm->get('dbAdapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Model\User());
            return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
        },
        'RoleTable' => function(ServiceManager $sm) {
            $tableGateway = $sm->get('RoleTableGateway');
            $table = new Model\RoleTable($tableGateway);
            return $table;
        },
        'RoleTableGateway' => function(ServiceManager $sm) {
            $dbAdapter = $sm->get('dbAdapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Model\Role());
            return new TableGateway('user_role', $dbAdapter, null, $resultSetPrototype);
        },
        'UserRoleTable' => function(ServiceManager $sm) {
            $tableGateway = $sm->get('UserRoleTableGateway');
            $table = new Model\UserRoleTable($tableGateway);
            return $table;
        },
        'UserRoleTableGateway' => function(ServiceManager $sm) {
            $dbAdapter = $sm->get('dbAdapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Model\UserRole());
            return new TableGateway('user_role_linker', $dbAdapter, null, $resultSetPrototype);
        },
        'ResourceTable' => function(ServiceManager $sm) {
            $tableGateway = $sm->get('ResourceTableGateway');
            $table = new Model\ResourceTable($tableGateway);
            return $table;
        },
        'ResourceTableGateway' => function(ServiceManager $sm) {
            $dbAdapter = $sm->get('dbAdapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Model\Resource());
            return new TableGateway('resources', $dbAdapter, null, $resultSetPrototype);
        },
        'RuleTable' => function(ServiceManager $sm) {
            $tableGateway = $sm->get('RuleTableGateway');
            $table = new Model\RuleTable($tableGateway);
            return $table;
        },
        'RuleTableGateway' => function(ServiceManager $sm) {
            $dbAdapter = $sm->get('dbAdapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Model\Rule());
            return new TableGateway('rule', $dbAdapter, null, $resultSetPrototype);
        },
        'UserPermissionTable' => function(ServiceManager $sm) { // not yet used
            $tableGateway = $sm->get('UserPermissionTableGateway');
            $table = new Model\UserPermissionTable($tableGateway);
            return $table;
        },
        'UserPermissionTableGateway' => function(ServiceManager $sm) {
            $dbAdapter = $sm->get('dbAdapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Model\UserPermission());
            return new TableGateway('user_permission', $dbAdapter, null, $resultSetPrototype);
        },
        //        'zfcuser_login_form' => function ($sm) {
//            $options = $sm->get('zfcuser_module_options');
//            $form = new ZfcUser\Form\Login('zfcuser-login', $options, new Figlet(), $sm->get('BruteForceAttemptTable'));
//            //$form->setInputFilter(new LoginFilter($options));
//            return $form;
//        },        
                
    ),
    'aliases' => array(
        'Zend\Db\Adapter\Adapter' => 'dbAdapter',
        'zfcuser_zend_db_adapter' => function(ServiceManager $sm) {
            $config = $sm->get('Config');
            $settings = $config['zfcuser'];
            return (isset($settings['zend_db_adapter'])) ? $settings['zend_db_adapter'] : 'Zend\Db\Adapter\Adapter';
        },
    ),
);
