<?php

namespace YOOtheme;

abstract class Database implements DatabaseInterface
{
    const SINGLE_QUOTED_TEXT = '\'([^\'\\\\]*(?:\\\\.[^\'\\\\]*)*)\'';
    const DOUBLE_QUOTED_TEXT = '"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"';

    /**
     * The table prefix.
     *
     * @var string
     */
    protected $prefix;

    /**
     * The table prefix placeholder.
     *
     * @var string
     */
    protected $placeholder = '@';

    /**
     * The regex for parsing SQL query parts.
     *
     * @var array
     */
    protected $regex = [];

    /**
     * Cache class reflections.
     *
     * @var array
     */
    protected $reflClasses = [];

    /**
     * Cache class reflection properties.
     *
     * @var array
     */
    protected $reflFields = [];

    /**
     * Gets the table prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Replaces the table prefix placeholder with actual one.
     *
     * @param  string $query
     * @return string
     */
    public function replacePrefix($query)
    {
        $offset = 0;
        $length = strlen($this->prefix) - strlen($this->placeholder);

        foreach ($this->getUnquotedQueryParts($query) as $part) {

            if (strpos($part[0], $this->placeholder) === false) {
                continue;
            }

            $replace = preg_replace($this->regex['placeholder'], $this->prefix.'$1', $part[0], -1, $count);

            if ($count) {
                $query = substr_replace($query, $replace, $part[1] + $offset, strlen($part[0]));
                $offset += $length;
            }
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchObject($statement, array $params = [], $class = 'stdClass', $args = [])
    {
        if (!$row = $this->fetchAssoc($statement, $params)) {
            return false;
        }

        return $this->hydrate($class, $row, $args);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAllObjects($statement, array $params = [], $class = 'stdClass', $args = [])
    {
        $result = [];

        foreach ($this->fetchAll($statement, $params) as $row) {
            $result[] = $this->hydrate($class, $row, $args);
        }

        return $result;
    }

    /**
     * Parses the unquoted SQL query parts.
     *
     * @param  string $query
     * @return array
     */
    protected function getUnquotedQueryParts($query)
    {
        if (!$this->regex) {
            $this->regex['quotes'] = "/([^'\"]+)(?:".self::DOUBLE_QUOTED_TEXT."|".self::SINGLE_QUOTED_TEXT.")?/As";
            $this->regex['placeholder'] = "/".preg_quote($this->placeholder)."([a-zA-Z_][a-zA-Z0-9_]*)/";
        }

        preg_match_all($this->regex['quotes'], $query, $parts, PREG_OFFSET_CAPTURE);

        return $parts[1];
    }

    /**
     * Prepares a parametrized SQL query string.
     *
     * @param  string $statement
     * @param  array  $params
     * @return string
     */
    protected function prepareQuery($statement, array $params = [])
    {
        $parameters = [];

        foreach ($params as $key => $value) {

            if (substr($key, 0, 1) !== ':') {
                $key = ":$key";
            }

            if (is_string($value)) {
                $value = '"'.$this->escape($value).'"';
            }

            if (is_null($value)) {
                $value = 'NULL';
            }

            $parameters[$key] = $value;
        }

        return strtr($this->replacePrefix($statement), $parameters);
    }

    /**
     * Creates object from data.
     *
     * @param  string $class
     * @param  array  $data
     * @param  array  $args
     * @return mixed
     */
    protected function hydrate($class, array $data, array $args = [])
    {
        if ('stdClass' === $class) {
            return (object) $data;
        }

        $reflClass = $this->getReflectionClass($class);
        $reflFields = $this->getReflectionFields($class);

        $values = array_intersect_key($data, $reflFields);
        $instance = $reflClass->newInstanceWithoutConstructor();

        foreach ($values as $key => $value) {
            $reflFields[$key]->setValue($instance, $value);
        }

        if ($constructor = $reflClass->getConstructor()) {
            $constructor->invokeArgs($instance, $args);
        }

        return $instance;
    }

    /**
     * Gets ReflectionClass for given class name.
     *
     * @param  string $class
     * @return \ReflectionClass
     */
    protected function getReflectionClass($class)
    {
        if (!isset($this->reflClasses[$class])) {
            $this->reflClasses[$class] = new \ReflectionClass($class);
        }

        return $this->reflClasses[$class];
    }

    /**
     * Gets ReflectionProperty array for given class name.
     *
     * @param  string $class
     * @return \ReflectionProperty[]
     */
    protected function getReflectionFields($class)
    {
        if (!isset($this->reflFields[$class])) {

            $this->reflFields[$class] = [];

            foreach ($this->getReflectionClass($class)->getProperties() as $property) {
                $property->setAccessible(true);
                $this->reflFields[$class][$property->getName()] = $property;
            }
        }

        return $this->reflFields[$class];
    }
}
