<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Form\RoleForm;
use Application\Model\Role;
//use Application\Model\RoleTable;
use Application\Form\ActionForm;

class RoleController extends AbstractActionController
{

    public function rolesAction()
    {
        $sm = $this->getServiceLocator();
        $roleTable = $sm->get('RoleTable');
        $roles = $roleTable->getRoles();

        return array(
            'roles' => $roles
        );
    }

    public function addRoleAction()
    {
        $sm = $this->getServiceLocator();
        $roleTable = $sm->get('RoleTable');
        $request = $this->getRequest();

        $form = new RoleForm();

        if ($request->getPost('submit')) {

            $role = new Role;
            $form->setInputFilter($role->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $role->exchangeArray($form->getData());

                if ($roleTable->save($role)) {
                    $this->flashMessenger()->setNamespace('success')->addMessage('Success add role.');
                    return $this->redirect()->toRoute('roles');
                } else {
                    $this->flashMessenger()->setNamespace('error')->addMessage('Error saving role!');
                }
            }
        }

        return array(
            'form' => $form,
        );
    }

    public function editRoleAction()
    {
        $sm = $this->getServiceLocator();
        $roleTable = $sm->get('RoleTable');
        $id = $this->params('id', 0);
        if(!$role = $roleTable->getRoleById($id)){
            return $this->redirect()->toRoute('roles');
        }
        $request = $this->getRequest();

        $form = new RoleForm();

        $form->setData($role);

        if ($request->getPost('submit')) {
            $post = $request->getPost();
            $newRole = new Role;
            $form->setInputFilter($newRole->getInputFilter());
            $form->setData($post);
            unset($post['submit']);
            if ($form->isValid() && array_diff_assoc($post->toArray(), $role)) {
                $newRole->exchangeArray($form->getData());
                if ($status = $roleTable->save($newRole)) {
                    $this->flashMessenger()->setNamespace('success')->addMessage('Success edit role.');
                    return $this->redirect()->toRoute('roles');
                } else {
                    $this->flashMessenger()->setNamespace('error')->addMessage('Error saving role!');
                }
            }
        }

        return array(
            'form' => $form,
        );
    }

    public function deleteRoleAction()
    {
        $sm = $this->getServiceLocator();
        $roleTable = $sm->get('RoleTable');
        $id = (int) $this->params('id');

        if ($roleTable->delete($id)) {
            $this->flashMessenger()->setNamespace('success')->addMessage('Success delete role.');
        } else {
            $this->flashMessenger()->setNamespace('error')->addMessage('Error deleting role!');
        }

        return $this->redirect()->toRoute('roles');
    }

    public function rolePermissionsAction()
    {
        $sm = $this->getServiceLocator();
        $roleTable = $sm->get('RoleTable');
        $ruleTable = $sm->get('RuleTable');
        $resourceTable = $sm->get('ResourceTable');
        $request = $this->getRequest();

        $id = (int) $this->params('id', 0);

        $role = $roleTable->getRoleById($id);
        $permissions = $ruleTable->getResourcesByRole($role['role_id']);
        $allResources = $resourceTable->getResources();

        $permissions = array_map(function($permission) {
            return $permission['resource'];
        }, $permissions);

        $form = new ActionForm;

        if ($resources = $request->getPost('resource')) {

            if ($ruleTable->resourceModify($resources, $role['role_id'])) {
                $this->flashMessenger()->setNamespace('success')->addMessage('Changes are successfully saved');
            } else {
                $this->flashMessenger()->setNamespace('error')->addMessage('Save failed');
            }

            return $this->redirect()->toRoute('rolePermissions', array( 'id' => $id) );
        }

        return array(
            'form' => $form,
            'allResources' => $allResources,
            'permissions' => $permissions,
            'role' => $role['role_id'],
        );
    }
}