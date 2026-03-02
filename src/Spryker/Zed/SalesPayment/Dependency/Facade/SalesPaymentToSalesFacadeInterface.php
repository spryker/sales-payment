<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;

interface SalesPaymentToSalesFacadeInterface
{
    public function findOrderByIdSalesOrder(int $idSalesOrder): ?OrderTransfer;
}
