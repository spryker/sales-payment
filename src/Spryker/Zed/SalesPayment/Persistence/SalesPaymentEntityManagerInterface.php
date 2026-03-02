<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Persistence;

use Generated\Shared\Transfer\SalesPaymentTransfer;

interface SalesPaymentEntityManagerInterface
{
    public function createSalesPayment(SalesPaymentTransfer $salesPaymentTransfer): SalesPaymentTransfer;

    /**
     * @param list<int> $salesPaymentIds
     *
     * @return void
     */
    public function deleteSalesPayments(array $salesPaymentIds): void;
}
