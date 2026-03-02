<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Persistence;

use Generated\Shared\Transfer\SalesPaymentTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentPersistenceFactory getFactory()
 */
class SalesPaymentEntityManager extends AbstractEntityManager implements SalesPaymentEntityManagerInterface
{
    public function createSalesPayment(SalesPaymentTransfer $salesPaymentTransfer): SalesPaymentTransfer
    {
        $idSalesPaymentMethodType = $this->getIdSalesPaymentMethodType(
            $salesPaymentTransfer->getPaymentProviderOrFail(),
            $salesPaymentTransfer->getPaymentMethodOrFail(),
        );

        $salesPaymentEntity = $this->getFactory()
            ->createSalesPaymentMapper()
            ->mapSalesPaymentTransferToSalesPaymentEntity($salesPaymentTransfer, (new SpySalesPayment()));
        $salesPaymentEntity->setFkSalesPaymentMethodType($idSalesPaymentMethodType);

        $salesPaymentEntity->save();

        return $this->getFactory()
            ->createSalesPaymentMapper()
            ->mapSalesPaymentEntityToSalesPaymentTransfer($salesPaymentEntity, $salesPaymentTransfer);
    }

    /**
     * @param list<int> $salesPaymentIds
     *
     * @return void
     */
    public function deleteSalesPayments(array $salesPaymentIds): void
    {
        $this->getFactory()
            ->createSalesPaymentQuery()
            ->filterByIdSalesPayment_In($salesPaymentIds)
            ->delete();
    }

    protected function getIdSalesPaymentMethodType(string $paymentProvider, string $paymentMethod): int
    {
        $salesPaymentMethodTypeEntity = $this->getFactory()
            ->createSalesPaymentMethodTypeQuery()
            ->filterByPaymentMethod($paymentMethod)
            ->filterByPaymentProvider($paymentProvider)
            ->findOneOrCreate();

        $salesPaymentMethodTypeEntity->save();

        return $salesPaymentMethodTypeEntity->getIdSalesPaymentMethodType();
    }
}
