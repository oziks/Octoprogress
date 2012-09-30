<?php

namespace Octoprogress\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Octoprogress\Model\Job;
use Octoprogress\Model\JobLog;
use Octoprogress\Model\JobPeer;
use Octoprogress\Model\JobQuery;

/**
 * Base class that represents a query for the 'job' table.
 *
 *
 *
 * @method JobQuery orderById($order = Criteria::ASC) Order by the id column
 * @method JobQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method JobQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method JobQuery orderByParams($order = Criteria::ASC) Order by the params column
 * @method JobQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method JobQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method JobQuery orderByCompletedAt($order = Criteria::ASC) Order by the completed_at column
 * @method JobQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method JobQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method JobQuery groupById() Group by the id column
 * @method JobQuery groupByName() Group by the name column
 * @method JobQuery groupByType() Group by the type column
 * @method JobQuery groupByParams() Group by the params column
 * @method JobQuery groupByMessage() Group by the message column
 * @method JobQuery groupByStatus() Group by the status column
 * @method JobQuery groupByCompletedAt() Group by the completed_at column
 * @method JobQuery groupByCreatedAt() Group by the created_at column
 * @method JobQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method JobQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method JobQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method JobQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method JobQuery leftJoinJobLog($relationAlias = null) Adds a LEFT JOIN clause to the query using the JobLog relation
 * @method JobQuery rightJoinJobLog($relationAlias = null) Adds a RIGHT JOIN clause to the query using the JobLog relation
 * @method JobQuery innerJoinJobLog($relationAlias = null) Adds a INNER JOIN clause to the query using the JobLog relation
 *
 * @method Job findOne(PropelPDO $con = null) Return the first Job matching the query
 * @method Job findOneOrCreate(PropelPDO $con = null) Return the first Job matching the query, or a new Job object populated from the query conditions when no match is found
 *
 * @method Job findOneByName(string $name) Return the first Job filtered by the name column
 * @method Job findOneByType(string $type) Return the first Job filtered by the type column
 * @method Job findOneByParams(string $params) Return the first Job filtered by the params column
 * @method Job findOneByMessage(string $message) Return the first Job filtered by the message column
 * @method Job findOneByStatus(int $status) Return the first Job filtered by the status column
 * @method Job findOneByCompletedAt(string $completed_at) Return the first Job filtered by the completed_at column
 * @method Job findOneByCreatedAt(string $created_at) Return the first Job filtered by the created_at column
 * @method Job findOneByUpdatedAt(string $updated_at) Return the first Job filtered by the updated_at column
 *
 * @method array findById(int $id) Return Job objects filtered by the id column
 * @method array findByName(string $name) Return Job objects filtered by the name column
 * @method array findByType(string $type) Return Job objects filtered by the type column
 * @method array findByParams(string $params) Return Job objects filtered by the params column
 * @method array findByMessage(string $message) Return Job objects filtered by the message column
 * @method array findByStatus(int $status) Return Job objects filtered by the status column
 * @method array findByCompletedAt(string $completed_at) Return Job objects filtered by the completed_at column
 * @method array findByCreatedAt(string $created_at) Return Job objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Job objects filtered by the updated_at column
 *
 * @package    propel.generator.Octoprogress.Model.om
 */
abstract class BaseJobQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseJobQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'octoprogress', $modelName = 'Octoprogress\\Model\\Job', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new JobQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     JobQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return JobQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof JobQuery) {
            return $criteria;
        }
        $query = new JobQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Job|Job[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = JobPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(JobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return   Job A model object, or null if the key is not found
     * @throws   PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return   Job A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `NAME`, `TYPE`, `PARAMS`, `MESSAGE`, `STATUS`, `COMPLETED_AT`, `CREATED_AT`, `UPDATED_AT` FROM `job` WHERE `ID` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Job();
            $obj->hydrate($row);
            JobPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Job|Job[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Job[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(JobPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(JobPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(JobPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(JobPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%'); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $type)) {
                $type = str_replace('*', '%', $type);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(JobPeer::TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the params column
     *
     * Example usage:
     * <code>
     * $query->filterByParams('fooValue');   // WHERE params = 'fooValue'
     * $query->filterByParams('%fooValue%'); // WHERE params LIKE '%fooValue%'
     * </code>
     *
     * @param     string $params The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function filterByParams($params = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($params)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $params)) {
                $params = str_replace('*', '%', $params);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(JobPeer::PARAMS, $params, $comparison);
    }

    /**
     * Filter the query on the message column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE message = 'fooValue'
     * $query->filterByMessage('%fooValue%'); // WHERE message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $message The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $message)) {
                $message = str_replace('*', '%', $message);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(JobPeer::MESSAGE, $message, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE status > 12
     * </code>
     *
     * @param     mixed $status The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(JobPeer::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(JobPeer::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(JobPeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the completed_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCompletedAt('2011-03-14'); // WHERE completed_at = '2011-03-14'
     * $query->filterByCompletedAt('now'); // WHERE completed_at = '2011-03-14'
     * $query->filterByCompletedAt(array('max' => 'yesterday')); // WHERE completed_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $completedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function filterByCompletedAt($completedAt = null, $comparison = null)
    {
        if (is_array($completedAt)) {
            $useMinMax = false;
            if (isset($completedAt['min'])) {
                $this->addUsingAlias(JobPeer::COMPLETED_AT, $completedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($completedAt['max'])) {
                $this->addUsingAlias(JobPeer::COMPLETED_AT, $completedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(JobPeer::COMPLETED_AT, $completedAt, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(JobPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(JobPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(JobPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(JobPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(JobPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(JobPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related JobLog object
     *
     * @param   JobLog|PropelObjectCollection $jobLog  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   JobQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByJobLog($jobLog, $comparison = null)
    {
        if ($jobLog instanceof JobLog) {
            return $this
                ->addUsingAlias(JobPeer::ID, $jobLog->getJobId(), $comparison);
        } elseif ($jobLog instanceof PropelObjectCollection) {
            return $this
                ->useJobLogQuery()
                ->filterByPrimaryKeys($jobLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByJobLog() only accepts arguments of type JobLog or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the JobLog relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function joinJobLog($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('JobLog');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'JobLog');
        }

        return $this;
    }

    /**
     * Use the JobLog relation JobLog object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Octoprogress\Model\JobLogQuery A secondary query class using the current class as primary query
     */
    public function useJobLogQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinJobLog($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'JobLog', '\Octoprogress\Model\JobLogQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Job $job Object to remove from the list of results
     *
     * @return JobQuery The current query, for fluid interface
     */
    public function prune($job = null)
    {
        if ($job) {
            $this->addUsingAlias(JobPeer::ID, $job->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     JobQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(JobPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     JobQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(JobPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     JobQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(JobPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     JobQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(JobPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     JobQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(JobPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     JobQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(JobPeer::CREATED_AT);
    }
}
