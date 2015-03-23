<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Form\UserRoleForm;
use Application\Model\UserRole;
//use Application\Model\UserRoleTable;
//use Application\Model\Role;
//use Application\Model\RoleTable;
use Application\Form\ActionForm;

class UserController extends AbstractActionController
{

    public function usersAction()
    {
        $sm = $this->getServiceLocator();
        $userTable = $sm->get('UserTable');
        $users = $userTable->getUsersAndRoles();

        return array(
            'users' => $users
        );
    }

    public function editUserAction()
    {
        $sm = $this->getServiceLocator();
        $userTable = $sm->get('UserTable');
        $id = (int) $this->params('id', 0);
        if(!$user = $userTable->getUserById($id)){
            return $this->redirect()->toRoute('users');
        }
        $roleTable = $sm->get('RoleTable');
        $userRoleTable = $sm->get('UserRoleTable');
        $request = $this->getRequest();

        $role = $userRoleTable->getRoleByUserId((int) $user->user_id);

        $form = new UserRoleForm();
        $form->get('role_id')->setAttribute('options', array('' => '-- Choose --') + $roleTable->getRolesOptions());

        if ($role) {
            $form->setData($role);
        } else {
            $form->setData(array('user_id' => $id));
        }

        if ($request->getPost('submit')) {

            $userRole = new UserRole;
            $form->setInputFilter($userRole->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $userRole->exchangeArray($form->getData());
                if ($status = $userRoleTable->save($userRole)) {
                    $this->flashMessenger()->setNamespace('success')->addMessage('Success edit role.');
                    return $this->redirect()->toRoute('users');
                } else {
                    $this->flashMessenger()->setNamespace('error')->addMessage('Error saving role!');
                }
            }
        }



        return array(
            'form' => $form,
            'username' => $user->username,
        );
    }

    public function userPermissionsAction()
    {
        $sm = $this->getServiceLocator();
        $userTable = $sm->get('UserTable');
//        $roleTable = $sm->get('RoleTable');
        $ruleTable = $sm->get('RuleTable');
        $resourceTable = $sm->get('ResourceTable');
        $userRoleTable = $sm->get('UserRoleTable');
        $userPermissionTable = $sm->get('UserPermissionTable');
        $request = $this->getRequest();

        $id = (int) $this->params('id', 0);

        $user = $userTable->getUserById($id);
        $permissions = $userPermissionTable->getPermissionsByUserId($id);

        $role = $userRoleTable->getRoleByUserId($id);
        $rule = $ruleTable->getResourcesByRole($role['role_id']);

        $allResources = $resourceTable->getResources();

        $permissions = array_merge($permissions, $rule);
        $permissions = array_map(function($permission) {
            return $permission['resource'];
        }, $permissions);

        $form = new ActionForm;

        if ($request->getPost('save')) {
            $resources = $request->getPost('resource');
            if ( ! isset($resources)) {
                $recources = array();
            }

            if ($userPermissionTable->resourceModify($resources, $id)) {
                $this->flashMessenger()->setNamespace('success')->addMessage('Changes are successfully saved');
            } else {
                $this->flashMessenger()->setNamespace('error')->addMessage('Save failed');
            }

            return $this->redirect()->toRoute('userPermissions', array('id' => $id));
        }

        return array(
            'form' => $form,
            'allResources' => $allResources,
            'permissions' => $permissions,
            'username' => $user->username,
            'role' => $role['role_id'],
        );
    }

}
