<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Log\Writer;

/**
 * Db log writer class
 *
 * @category   Pop
 * @package    Pop_Log
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
class Db implements WriterInterface
{

    /**
     * Sql object
     * @var \Pop\Db\Sql
     */
    protected $sql = null;

    /**
     * Constructor
     *
     * Instantiate the DB writer object
     *
     * The DB table requires the following fields at a minimum:
     *     timestamp  DATETIME
     *     priority   INT
     *     name       VARCHAR
     *     message    TEXT, VARCHAR, etc.
     *
     * @param  \Pop\Db\Sql $sql
     * @param  string      $table
     * @throws Exception
     * @return Db
     */
    public function __construct(\Pop\Db\Sql $sql, $table = null)
    {
        if (null !== $table) {
            $sql->setTable($table);
        }
        if (null === $sql->getTable()) {
            throw new Exception('Error: The SQL object does not have a table defined.');
        }

        $this->sql = $sql;

        if (!in_array($sql->getTable(), $sql->db()->getTables())) {
            $this->createTable();
        }
    }

    /**
     * Write to the log
     *
     * @param  array $logEntry
     * @return Db
     */
    public function writeLog(array $logEntry)
    {
        $columns = [];
        $params  = [];

        $i = 1;
        foreach ($logEntry as $column => $value) {
            $placeholder = $this->sql->getPlaceholder();

            if ($placeholder == ':') {
                $placeholder .= $column;
            } else if ($placeholder == '$') {
                $placeholder .= $i;
            }
            $columns[$column] = $placeholder;
            $params[$column]  = $value;
            $i++;
        }

        $this->sql->insert($columns);
        $this->sql->db()->prepare((string)$this->sql)
                        ->bindParams($params)
                        ->execute();

        return $this;
    }

    /**
     * Create table in databse
     *
     * @return void
     */
    protected function createTable()
    {
        if (file_exists(__DIR__ . '/Sql/' . strtolower($this->sql->getDbType()) . '.sql')) {
            $sql = str_replace(
                '[{table}]',
                $this->sql->getTable(),
                file_get_contents(__DIR__ . '/Sql/' . strtolower($this->sql->getDbType()) . '.sql')
            );
            $queries = explode(';', $sql);
            foreach ($queries as $query) {
                if (!empty($query) && ($query != '')) {
                    $this->sql->db()->query($query);
                }
            }
        }
    }

}
