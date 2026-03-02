<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\SalesPaymentCollectionTransfer;
use Generated\Shared\Transfer\SalesPaymentCriteriaTransfer;
use Spryker\Zed\SalesPayment\Persistence\SalesPaymentRepositoryInterface;

class SalesPaymentReader implements SalesPaymentReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesPayment\Persistence\SalesPaymentRepositoryInterface $salesPaymentRepository
     */
    protected SalesPaymentRepositoryInterface $salesPaymentRepository;

    public function __construct(SalesPaymentRepositoryInterface $salesPaymentRepository)
    {
        $this->salesPaymentRepository = $salesPaymentRepository;
    }

    public function getSalesPaymentCollection(SalesPaymentCriteriaTransfer $salesPaymentCriteriaTransfer): SalesPaymentCollectionTransfer
    {
        $salesPaymentCollectionTransfer = new SalesPaymentCollectionTransfer();
        $salesPaymentConditionsTransfer = $salesPaymentCriteriaTransfer->getSalesPaymentConditionsOrFail();
        $salesPaymentTransfers = $this->salesPaymentRepository->getSalesPaymentsByIdSalesOrder(current($salesPaymentConditionsTransfer->getSalesOrderIds()));

        return $salesPaymentCollectionTransfer->setSalesPayments(new ArrayObject($salesPaymentTransfers));
    }
}
