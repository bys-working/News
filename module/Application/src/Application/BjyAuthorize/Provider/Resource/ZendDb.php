<?php

/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\BjyAuthorize\Provider\Resource;

use BjyAuthorize\Provider\Resource\ProviderInterface;
//use Application\BjyAuthorize\Acl\Resource;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Role provider based on a {@see \Zend\Db\Adaper\Adapter}
 *
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 */
class ZendDb implements ProviderInterface
{

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var string
     */
    protected $adapterName = 'bjyauthorize_zend_db_adapter';

    /**
     * @var string
     */
    protected $tableName = 'resources';

    /**
     * @var string
     */
    protected $resourceField = 'resource_field';

    /**
     * @param                         $options
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct($options, ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        if (isset($options['adapter'])) {
            $this->adapterName = $options['adapter'];
        }

        if (isset($options['table'])) {
            $this->tableName = $options['table'];
        }

        if (isset($options['resource_field'])) {
            $this->resourceField = $options['resource_field'];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getResources()
    {
        /* @var $adapter \Zend\Db\Adapter\Adapter */
        $adapter = $this->serviceLocator->get($this->adapterName);
        $tableGateway = new TableGateway($this->tableName, $adapter);
        $sql = new Select;

        $sql->from($this->tableName);

        $rowset = $tableGateway->selectWith($sql);
        $resources = $rowset->toArray();

        foreach ($resources as $resource) {
            $array[$resource['resource']] = array();
        }
        //var_dump($array);exit;
        return $array;
    }

}
