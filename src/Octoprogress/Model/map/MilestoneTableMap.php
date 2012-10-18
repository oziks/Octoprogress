<?php

namespace Octoprogress\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'milestone' table.
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
class MilestoneTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Octoprogress.Model.map.MilestoneTableMap';

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
        $this->setName('milestone');
        $this->setPhpName('Milestone');
        $this->setClassname('Octoprogress\\Model\\Milestone');
        $this->setPackage('Octoprogress.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('PROJECT_ID', 'ProjectId', 'INTEGER', 'project', 'ID', false, null, null);
        $this->addColumn('GITHUB_ID', 'GithubId', 'INTEGER', false, null, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('DESCRIPTION', 'Description', 'VARCHAR', false, 255, null);
        $this->addColumn('STATE', 'State', 'VARCHAR', false, 255, null);
        $this->addColumn('OPEN_ISSUES', 'OpenIssues', 'INTEGER', false, null, null);
        $this->addColumn('CLOSED_ISSUES', 'ClosedIssues', 'INTEGER', false, null, null);
        $this->addColumn('DUE_DATE', 'DueDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Project', 'Octoprogress\\Model\\Project', RelationMap::MANY_TO_ONE, array('project_id' => 'id', ), null, null);
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

} // MilestoneTableMap
