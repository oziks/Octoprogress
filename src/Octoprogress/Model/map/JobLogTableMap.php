<?php

namespace Octoprogress\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'job_log' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.Octoprogress.Model.map
 */
class JobLogTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Octoprogress.Model.map.JobLogTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('job_log');
        $this->setPhpName('JobLog');
        $this->setClassname('Octoprogress\\Model\\JobLog');
        $this->setPackage('Octoprogress.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('JOB_ID', 'JobId', 'INTEGER', 'job', 'ID', true, null, null);
        $this->addColumn('LEVEL', 'Level', 'ENUM', true, null, null);
        $this->getColumn('LEVEL', false)->setValueSet(array (
  0 => 'error',
  1 => 'info',
));
        $this->addColumn('MESSAGE', 'Message', 'CLOB', false, null, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Job', 'Octoprogress\\Model\\Job', RelationMap::MANY_TO_ONE, array('job_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

} // JobLogTableMap
