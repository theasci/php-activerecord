<?php

namespace ActiveRecord;

/**
 * Generic base exception for all ActiveRecord specific errors.
 */
class ActiveRecordException extends \Exception {}

/**
 * Thrown when a record cannot be found.
 */
class RecordNotFound extends ActiveRecordException {}

/**
 * Thrown when there was an error performing a database operation.
 *
 * The error will be specific to whatever database you are running.
 */
class DatabaseException extends ActiveRecordException
{
    public function __construct($adapter_or_string_or_mystery)
    {
        if ($adapter_or_string_or_mystery instanceof Connection) {
            $error_message = join(",", $adapter_or_string_or_mystery->connection->errorInfo());
            try {
                if ($adapter_or_string_or_mystery->last_query) {
                    $error_message .= "\n Last SQL query: {$adapter_or_string_or_mystery->last_query}";
                }
                if ($adapter_or_string_or_mystery->last_values) {
                    $error_message .=
                        "\n Last bound parameters: " .
                        implode(", ", $adapter_or_string_or_mystery->last_values);
                }
            } catch (\Exception $e) {
                // don't throw an error while trying to build our error message :)
                $error_message .= " (Unable to retrieve last query/parameters)";
            }
            parent::__construct($error_message);
        } elseif ($adapter_or_string_or_mystery instanceof \PDOStatement) {
            parent::__construct(
                implode(", ", $adapter_or_string_or_mystery->errorInfo()),
                intval($adapter_or_string_or_mystery->errorCode()),
            );
        } elseif ($adapter_or_string_or_mystery instanceof \PDOException) {
            if ($adapter_or_string_or_mystery->errorInfo) {
                $message = implode(
                    ", ",
                    $adapter_or_string_or_mystery->errorInfo,
                );
            } else {
                $message = $adapter_or_string_or_mystery->getMessage();
            }
            parent::__construct(
                $message,
                intval($adapter_or_string_or_mystery->getCode()),
                $adapter_or_string_or_mystery,
            );
        } else {
            parent::__construct($adapter_or_string_or_mystery);
        }
    }
}

/**
 * Thrown by {@link Model}.
 */
class ModelException extends ActiveRecordException {}

/**
 * Thrown by {@link Expressions}.
 */
class ExpressionsException extends ActiveRecordException {}

/**
 * Thrown for configuration problems.
 */
class ConfigException extends ActiveRecordException {}

/**
 * Thrown when attempting to access an invalid property on a {@link Model}.
 */
class UndefinedPropertyException extends ModelException
{
    /**
     * Sets the exception message to show the undefined property's name.
     *
     * @param  string  $property_name  name of undefined property
     */
    public function __construct($class_name, $property_name)
    {
        if (is_array($property_name)) {
            $this->message = implode("\r\n", $property_name);

            return;
        }

        $this->message = "Undefined property: {$class_name}->{$property_name} in {$this->file} on line {$this->line}";
        parent::__construct();
    }
}

/**
 * Thrown when attempting to perform a write operation on a {@link Model} that is in read-only mode.
 */
class ReadOnlyException extends ModelException
{
    /**
     * Sets the exception message to show the undefined property's name.
     *
     * @param  string  $class_name  name of the model that is read only
     * @param  string  $method_name  name of method which attempted to modify the model
     */
    public function __construct($class_name, $method_name)
    {
        $this->message = "{$class_name}::{$method_name}() cannot be invoked because this model is set to read only";
        parent::__construct();
    }
}

/**
 * Thrown for validations exceptions.
 */
class ValidationsArgumentError extends ActiveRecordException {}

/**
 * Thrown for relationship exceptions.
 */
class RelationshipException extends ActiveRecordException {}

/**
 * Thrown for has many thru exceptions.
 */
class HasManyThroughAssociationException extends RelationshipException {}
