<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Form\NewsForm;
use Application\Model\News;

class NewsFormFactory implements \Zend\ServiceManager\FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $form = new NewsForm();
        $model = new News();
        $form->setInputFilter($model->getInputFilter());
        return $form;
    }
}