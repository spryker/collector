<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence\Pdo\PostgreSql;

use Spryker\Zed\Collector\Persistence\Pdo\BulkDeleteTouchByIdQueryInterface;

class BulkDeleteTouchByIdQuery extends AbstractBulkTouchQuery implements BulkDeleteTouchByIdQueryInterface
{

    protected $queryTemplate = "DELETE FROM %s WHERE %s IN (%s)";

    /**
     * @param string $tableName
     * @param string $idColumnName
     * @param array $idsToDelete
     *
     * @return $this
     */
    public function addQuery($tableName, $idColumnName, $idsToDelete)
    {
        $this->queries[] = sprintf(
            $this->getQueryTemplate(),
            $tableName,
            $idColumnName,
            implode(',', $idsToDelete)
        );

        return $this;
    }

}
