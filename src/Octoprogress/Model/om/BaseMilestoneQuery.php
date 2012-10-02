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
use Octoprogress\Model\Milestone;
use Octoprogress\Model\MilestonePeer;
use Octoprogress\Model\MilestoneQuery;
use Octoprogress\Model\Project;

/**
 * Base class that represents a query for the 'milestone' table.
 *
 *
 *
 * @method MilestoneQuery orderById($order = Criteria::ASC) Order by the id column
 * @method MilestoneQuery orderByProjectId($order = Criteria::ASC) Order by the project_id column
 * @method MilestoneQuery orderByGithubId($order = Criteria::ASC) Order by the github_id column
 * @method MilestoneQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method MilestoneQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method MilestoneQuery orderByState($order = Criteria::ASC) Order by the state column
 * @method MilestoneQuery orderByOpenIssues($order = Criteria::ASC) Order by the open_issues column
 * @method MilestoneQuery orderByClosedIssues($order = Criteria::ASC) Order by the closed_issues column
 * @method MilestoneQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method MilestoneQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method MilestoneQuery groupById() Group by the id column
 * @method MilestoneQuery groupByProjectId() Group by the project_id column
 * @method MilestoneQuery groupByGithubId() Group by the github_id column
 * @method MilestoneQuery groupByName() Group by the name column
 * @method MilestoneQuery groupByDescription() Group by the description column
 * @method MilestoneQuery groupByState() Group by the state column
 * @method MilestoneQuery groupByOpenIssues() Group by the open_issues column
 * @method MilestoneQuery groupByClosedIssues() Group by the closed_issues column
 * @method MilestoneQuery groupByCreatedAt() Group by the created_at column
 * @method MilestoneQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method MilestoneQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method MilestoneQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method MilestoneQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method MilestoneQuery leftJoinProject($relationAlias = null) Adds a LEFT JOIN clause to the query using the Project relation
 * @method MilestoneQuery rightJoinProject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Project relation
 * @method MilestoneQuery innerJoinProject($relationAlias = null) Adds a INNER JOIN clause to the query using the Project relation
 *
 * @method Milestone findOne(PropelPDO $con = null) Return the first Milestone matching the query
 * @method Milestone findOneOrCreate(PropelPDO $con = null) Return the first Milestone matching the query, or a new Milestone object populated from the query conditions when no match is found
 *
 * @method Milestone findOneByProjectId(int $project_id) Return the first Milestone filtered by the project_id column
 * @method Milestone findOneByGithubId(int $github_id) Return the first Milestone filtered by the github_id column
 * @method Milestone findOneByName(string $name) Return the first Milestone filtered by the name column
 * @method Milestone findOneByDescription(string $description) Return the first Milestone filtered by the description column
 * @method Milestone findOneByState(string $state) Return the first Milestone filtered by the state column
 * @method Milestone findOneByOpenIssues(int $open_issues) Return the first Milestone filtered by the open_issues column
 * @method Milestone findOneByClosedIssues(int $closed_issues) Return the first Milestone filtered by the closed_issues column
 * @method Milestone findOneByCreatedAt(string $created_at) Return the first Milestone filtered by the created_at column
 * @method Milestone findOneByUpdatedAt(string $updated_at) Return the first Milestone filtered by the updated_at column
 *
 * @method array findById(int $id) Return Milestone objects filtered by the id column
 * @method array findByProjectId(int $project_id) Return Milestone objects filtered by the project_id column
 * @method array findByGithubId(int $github_id) Return Milestone objects filtered by the github_id column
 * @method array findByName(string $name) Return Milestone objects filtered by the name column
 * @method array findByDescription(string $description) Return Milestone objects filtered by the description column
 * @method array findByState(string $state) Return Milestone objects filtered by the state column
 * @method array findByOpenIssues(int $open_issues) Return Milestone objects filtered by the open_issues column
 * @method array findByClosedIssues(int $closed_issues) Return Milestone objects filtered by the closed_issues column
 * @method array findByCreatedAt(string $created_at) Return Milestone objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Milestone objects filtered by the updated_at column
 *
 * @package    propel.generator.Octoprogress.Model.om
 */
abstract class BaseMilestoneQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseMilestoneQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'octoprogress', $modelName = 'Octoprogress\\Model\\Milestone', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new MilestoneQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     MilestoneQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return MilestoneQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof MilestoneQuery) {
            return $criteria;
        }
        $query = new MilestoneQuery();
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
     * @return   Milestone|Milestone[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MilestonePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(MilestonePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Milestone A model object, or null if the key is not found
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
     * @return   Milestone A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `PROJECT_ID`, `GITHUB_ID`, `NAME`, `DESCRIPTION`, `STATE`, `OPEN_ISSUES`, `CLOSED_ISSUES`, `CREATED_AT`, `UPDATED_AT` FROM `milestone` WHERE `ID` = :p0';
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
            $obj = new Milestone();
            $obj->hydrate($row);
            MilestonePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Milestone|Milestone[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Milestone[]|mixed the list of results, formatted by the current formatter
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
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MilestonePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MilestonePeer::ID, $keys, Criteria::IN);
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
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(MilestonePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the project_id column
     *
     * Example usage:
     * <code>
     * $query->filterByProjectId(1234); // WHERE project_id = 1234
     * $query->filterByProjectId(array(12, 34)); // WHERE project_id IN (12, 34)
     * $query->filterByProjectId(array('min' => 12)); // WHERE project_id > 12
     * </code>
     *
     * @see       filterByProject()
     *
     * @param     mixed $projectId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function filterByProjectId($projectId = null, $comparison = null)
    {
        if (is_array($projectId)) {
            $useMinMax = false;
            if (isset($projectId['min'])) {
                $this->addUsingAlias(MilestonePeer::PROJECT_ID, $projectId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($projectId['max'])) {
                $this->addUsingAlias(MilestonePeer::PROJECT_ID, $projectId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MilestonePeer::PROJECT_ID, $projectId, $comparison);
    }

    /**
     * Filter the query on the github_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGithubId(1234); // WHERE github_id = 1234
     * $query->filterByGithubId(array(12, 34)); // WHERE github_id IN (12, 34)
     * $query->filterByGithubId(array('min' => 12)); // WHERE github_id > 12
     * </code>
     *
     * @param     mixed $githubId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function filterByGithubId($githubId = null, $comparison = null)
    {
        if (is_array($githubId)) {
            $useMinMax = false;
            if (isset($githubId['min'])) {
                $this->addUsingAlias(MilestonePeer::GITHUB_ID, $githubId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($githubId['max'])) {
                $this->addUsingAlias(MilestonePeer::GITHUB_ID, $githubId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MilestonePeer::GITHUB_ID, $githubId, $comparison);
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
     * @return MilestoneQuery The current query, for fluid interface
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

        return $this->addUsingAlias(MilestonePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MilestonePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the state column
     *
     * Example usage:
     * <code>
     * $query->filterByState('fooValue');   // WHERE state = 'fooValue'
     * $query->filterByState('%fooValue%'); // WHERE state LIKE '%fooValue%'
     * </code>
     *
     * @param     string $state The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function filterByState($state = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($state)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $state)) {
                $state = str_replace('*', '%', $state);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MilestonePeer::STATE, $state, $comparison);
    }

    /**
     * Filter the query on the open_issues column
     *
     * Example usage:
     * <code>
     * $query->filterByOpenIssues(1234); // WHERE open_issues = 1234
     * $query->filterByOpenIssues(array(12, 34)); // WHERE open_issues IN (12, 34)
     * $query->filterByOpenIssues(array('min' => 12)); // WHERE open_issues > 12
     * </code>
     *
     * @param     mixed $openIssues The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function filterByOpenIssues($openIssues = null, $comparison = null)
    {
        if (is_array($openIssues)) {
            $useMinMax = false;
            if (isset($openIssues['min'])) {
                $this->addUsingAlias(MilestonePeer::OPEN_ISSUES, $openIssues['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($openIssues['max'])) {
                $this->addUsingAlias(MilestonePeer::OPEN_ISSUES, $openIssues['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MilestonePeer::OPEN_ISSUES, $openIssues, $comparison);
    }

    /**
     * Filter the query on the closed_issues column
     *
     * Example usage:
     * <code>
     * $query->filterByClosedIssues(1234); // WHERE closed_issues = 1234
     * $query->filterByClosedIssues(array(12, 34)); // WHERE closed_issues IN (12, 34)
     * $query->filterByClosedIssues(array('min' => 12)); // WHERE closed_issues > 12
     * </code>
     *
     * @param     mixed $closedIssues The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function filterByClosedIssues($closedIssues = null, $comparison = null)
    {
        if (is_array($closedIssues)) {
            $useMinMax = false;
            if (isset($closedIssues['min'])) {
                $this->addUsingAlias(MilestonePeer::CLOSED_ISSUES, $closedIssues['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($closedIssues['max'])) {
                $this->addUsingAlias(MilestonePeer::CLOSED_ISSUES, $closedIssues['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MilestonePeer::CLOSED_ISSUES, $closedIssues, $comparison);
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
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MilestonePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MilestonePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MilestonePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(MilestonePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MilestonePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MilestonePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Project object
     *
     * @param   Project|PropelObjectCollection $project The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   MilestoneQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByProject($project, $comparison = null)
    {
        if ($project instanceof Project) {
            return $this
                ->addUsingAlias(MilestonePeer::PROJECT_ID, $project->getId(), $comparison);
        } elseif ($project instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MilestonePeer::PROJECT_ID, $project->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByProject() only accepts arguments of type Project or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Project relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function joinProject($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Project');

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
            $this->addJoinObject($join, 'Project');
        }

        return $this;
    }

    /**
     * Use the Project relation Project object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Octoprogress\Model\ProjectQuery A secondary query class using the current class as primary query
     */
    public function useProjectQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinProject($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Project', '\Octoprogress\Model\ProjectQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Milestone $milestone Object to remove from the list of results
     *
     * @return MilestoneQuery The current query, for fluid interface
     */
    public function prune($milestone = null)
    {
        if ($milestone) {
            $this->addUsingAlias(MilestonePeer::ID, $milestone->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     MilestoneQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(MilestonePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     MilestoneQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(MilestonePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     MilestoneQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(MilestonePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     MilestoneQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(MilestonePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     MilestoneQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(MilestonePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     MilestoneQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(MilestonePeer::CREATED_AT);
    }
}
