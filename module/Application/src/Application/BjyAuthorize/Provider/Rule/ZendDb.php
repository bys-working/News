<?php

/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\BjyAuthorize\Provider\Rule;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;

use BjyAuthorize\Provider\Rule\ProviderInterface;
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
    protected $tableName = 'rule';

    /**
     * @var string
     */
    protected $idFieldName = 'id';
    protected $ruleField = 'resource';
    protected $roleField = 'role';
    protected $adminRole = 'admin';

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

        if (isset($options['id_field'])) {
            $this->idFieldName = $options['id_field'];
        }

        if (isset($options['rule_field'])) {
            $this->ruleField = $options['rule_field'];
        }

        if (isset($options['role_field'])) {
            $this->roleField = $options['role_field'];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getRules()
    {
        /* @var $adapter \Zend\Db\Adapter\Adapter */
        $adapter = $this->serviceLocator->get($this->adapterName);
        $tableGateway = new TableGateway($this->tableName, $adapter);
        $platform = $adapter->getPlatform();

        if ($this->serviceLocator->get('zfcuser_auth_service')->getIdentity()) {
            $id = $this->serviceLocator->get('zfcuser_auth_service')->getIdentity()->getId();
        } else {
            $id = null;
        }

        $array = array();

        $sql = new Select;

        $leftJoinResources = new Where();
        $leftJoinResources->equalTo('r.resource_id', new Expression('rs.id'));

        $query = $sql
            ->from(array('r' => $this->tableName))
            ->join(array('rs' => $this->serviceLocator->get('ResourceTable')->getTableGateway()->getTable()), $leftJoinResources, array('resource'), 'left')
            ->getSqlString($platform);

        $resultSet = $adapter->query($query, $adapter::QUERY_MODE_EXECUTE)->toArray();

        foreach ($resultSet as $rule) {
            $array['allow'][] = array(
                array($rule['role'], $this->adminRole),
                $rule[$this->ruleField]
            );
        }

        $sql = new Select;

        $leftJoinUser = new Where();
        $leftJoinUser->equalTo('up.user_id', new Expression('u.user_id'));

        $leftJoinResources = new Where();
        $leftJoinResources->equalTo('up.resource_id', new Expression('r.id'));

        $query = $sql
            ->from(array('up' => $this->serviceLocator->get('UserPermissionTable')->getTableGateway()->getTable()))
            ->join(array('u' => $this->serviceLocator->get('UserTable')->getTableGateway()->getTable()), $leftJoinUser, array('username'), 'left')
            ->join(array('r' => $this->serviceLocator->get('ResourceTable')->getTableGateway()->getTable()), $leftJoinResources, array('resource'), 'left')
            ->where(array('u.user_id' => $id))
            ->getSqlString($platform);

        $resultSet = $adapter->query($query, $adapter::QUERY_MODE_EXECUTE)->toArray();

        foreach($resultSet as $result) {
            $array['allow'][] = array(
                array($result['username'], $this->adminRole),
                $result['resource']
            );
        }

        return $array;
    }

}
