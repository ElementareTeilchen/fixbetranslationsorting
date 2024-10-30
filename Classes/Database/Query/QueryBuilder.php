<?php
declare(strict_types=1);

namespace ElementareTeilchen\Fixbetranslationsorting\Database\Query;

class QueryBuilder extends \TYPO3\CMS\Core\Database\Query\QueryBuilder
{
    /**
     * Specifies an ordering for the query results.
     * Replaces any previously specified orderings, if any.
     *
     * @param string $fieldName The fieldName to order by.
     * @param string $order The ordering direction. No automatic quoting/escaping.
     * @param bool $quote Flag if to quote the given field name.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function orderBy(string $fieldName, string $order = null, bool $quote = true): \TYPO3\CMS\Core\Database\Query\QueryBuilder
    {
        $this->concreteQueryBuilder->orderBy($quote ? $this->connection->quoteIdentifier($fieldName) : $fieldName, $order);

        return $this;
    }
}