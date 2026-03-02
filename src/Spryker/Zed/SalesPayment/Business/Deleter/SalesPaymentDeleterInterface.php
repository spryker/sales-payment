<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business\Deleter;

use Generated\Shared\Transfer\SalesPaymentCollectionTransfer;

interface SalesPaymentDeleterInterface
{
    public function deleteSalesPayments(SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer): void;
}
