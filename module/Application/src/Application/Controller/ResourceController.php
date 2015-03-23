<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use Application\Form\ResourceForm;
//use Application\Model\Resource;
//use Application\Model\ResourceTable;

class ResourceController extends AbstractActionController
{

    public function resourcesAction()
    {
        $sm = $this->getServiceLocator();
        $resourceTable = $sm->get('ResourceTable');
        $resources = $resourceTable->getResources();

        return array(
            'resources' => $resources
        );
    }

    public function addResourceAction()
    {
        $sm = $this->getServiceLocator();
        $resourceTable = $sm->get('ResourceTable');
        $ruleTable = $sm->get('RuleTable');
        //$request = $this->getRequest();

        $resource = $this->params('resource');

        $resource = 'route/' . $resource;

        $resource_id = $resourceTable->save(array('resource' => $resource));
        $ruleTable->save(array('resource_id' => $resource_id, 'role' => 'admin'));

        $this->flashMessenger()->setNamespace('success')->addMessage('Success add resource.');
        return $this->redirect()->toRoute('scanResources');
    }

    public function scanResourcesAction()
    {
        $sm = $this->getServiceLocator();
        $resourceTable = $sm->get('ResourceTable');

        $dirpath = __DIR__; //$this->getFrontController()->getControllerDirectory();
        $resources = array();
        $hiddenMethods = array('route/index', 'route/notFound', 'route/getMethodFrom');

        $insertedResources = array_map(function ($resource) {
                return $resource['resource'];
            }, $resourceTable->getResources());

        $diritem = new \DirectoryIterator($dirpath);
        foreach ($diritem as $item) {
            if ($item->isFile()) {
                if (strstr($item->getFilename(), 'Controller.php') != FALSE) {
                    include_once $dirpath . '/' . $item->getFilename();
                }
            }
        }

        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, 'Zend\Mvc\Controller\AbstractActionController')) {
                $functions = array();

                $c = explode('\\', $class);
                $num = (count($c) - 1);
                $c = $c[$num];

                $controller = strtolower(substr($c, 0, strpos($c, "Controller")));

                foreach (get_class_methods($class) as $method) {

                    if (strstr($method, 'Action') != false) {
                        $method = 'route/' . str_replace('Action', '', $method);

                        if (!(in_array($method, $hiddenMethods) || in_array($method, $insertedResources))) {
                            $resources[] = array(
                                'controller' => $controller,
                                'resource' => $method,
                            );
                            array_push($functions, $method);
                        }
                    }
                }
            }
        }

        return array(
            'resources' => $resources,
        );
    }

    public function deleteResourceAction()
    {
        $sm = $this->getServiceLocator();
        $resourceTable = $sm->get('ResourceTable');
        $id = (int) $this->params('id');

        if ($resourceTable->delete($id)) {
            $this->flashMessenger()->setNamespace('success')->addMessage('Success delete resource.');
        } else {
            $this->flashMessenger()->setNamespace('error')->addMessage('Error deleting resource!');
        }

        return $this->redirect()->toRoute('resources');
    }

//    public function editResourceAction()
//    {
//        $sm = $this->getServiceLocator();
//        $resourceTable = $sm->get('ResourceTable');
//        $id = $this->params('id');
//        $request = $this->getRequest();
//
//        $form = new ResourceForm();
//
//        $resource = $resourceTable->getResourceById($id);
//
//        $form->setData($resource);
//
//        if ($request->getPost('submit')) {
//
//            $resource = new Resource;
//            $form->setInputFilter($resource->getInputFilter());
//            $form->setData($request->getPost());
//
//            if ($form->isValid()) {
//                $resource->exchangeArray($form->getData());
//                if ($status = $resourceTable->save($resource)) {
//                    $this->flashMessenger()->setNamespace('success')->addMessage('Success edit resource.');
//                    return $this->redirect()->toRoute('resources', array('id' => $id));
//                } else {
//                    $this->flashMessenger()->setNamespace('error')->addMessage('Error saving resource!');
//                }
//            }
//        }
//
//        return array(
//            'form' => $form,
//        );
//    }

}
