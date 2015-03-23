<?php

namespace Application\Navigation\Service;

use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Navigation;

class DefaultNavigationFactory extends AbstractNavigationFactory
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $pages = $this->getPages($serviceLocator);
        $navigation = new Navigation($pages);

        return $navigation;
    }

    protected function getName()
    {
        return 'default';
    }

    private function getUserPermissions($serviceLocator)
    {
        $userPermissionTable = $serviceLocator->get('UserPermissionTable');
        $ruleTable = $serviceLocator->get('RuleTable');
        $userRoleTable = $serviceLocator->get('UserRoleTable');

        $id = $serviceLocator->get('zfcuser_auth_service')->getIdentity()->getId();

        $permissions = $userPermissionTable->getPermissionsByUserId($id);
       
        $role = $userRoleTable->getRoleByUserId($id);
        $rule = $ruleTable->getResourcesByRole($role['role_id']);

        $permissions = array_merge($permissions, $rule);
        $permissions = array_map(function($permission) {
            return $permission['resource'];
        }, $permissions);

        return $permissions;
    }

    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if(!$serviceLocator->get('zfcuser_auth_service')->hasIdentity()) {
            return parent::getPages($serviceLocator);
        }

        $config = $serviceLocator->get('Config');
        $pagesConfiguration = $config['navigation'][$this->getName()];

        $permissions = $this->getUserPermissions($serviceLocator);
        foreach($pagesConfiguration as $k=>$pagesConfig) {
            foreach($pagesConfiguration[$k]['pages'] as $key=>$pageConfiguration) {
                if(!in_array('route/' . $pageConfiguration['route'], $permissions)) {
                    unset($pagesConfiguration[$k]['pages'][$key]);
                }
            }
        }

        $pages = $this->getPagesFromConfig($pagesConfiguration);
        $this->pages = $this->preparePages($serviceLocator, $pages);
        return $this->pages;
    }

}
