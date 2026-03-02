<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Zed\SalesPayment\Persistence\SalesPaymentRepositoryInterface;

class SalesOrderExpander implements SalesOrderExpanderInterface
{
    /**
     * @var array<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\OrderPaymentExpanderPluginInterface>
     */
    protected $orderPaymentExpanderPlugins;

    /**
     * @var \Spryker\Zed\SalesPayment\Persistence\SalesPaymentRepositoryInterface
     */
    protected $salesPaymentRepository;

    /**
     * @param \Spryker\Zed\SalesPayment\Persistence\SalesPaymentRepositoryInterface $salesPaymentRepository
     * @param array<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\OrderPaymentExpanderPluginInterface> $orderPaymentExpanderPlugins
     */
    public function __construct(
        SalesPaymentRepositoryInterface $salesPaymentRepository,
        array $orderPaymentExpanderPlugins
    ) {
        $this->salesPaymentRepository = $salesPaymentRepository;
        $this->orderPaymentExpanderPlugins = $orderPaymentExpanderPlugins;
    }

    public function expandOrderWithPayments(OrderTransfer $orderTransfer): OrderTransfer
    {
        $salesPaymentTransfers = $this->salesPaymentRepository->getSalesPaymentsByIdSalesOrder($orderTransfer->getIdSalesOrderOrFail());

        foreach ($salesPaymentTransfers as $salesPaymentTransfer) {
            $paymentTransfer = (new PaymentTransfer())->fromArray($salesPaymentTransfer->toArray(), true);
            $paymentTransfer = $this->executeOrderPaymentExpanderPlugins($paymentTransfer, $orderTransfer);

            $orderTransfer->addPayment($paymentTransfer);
        }

        $orderTransfer->getTotalsOrFail()->setPriceToPay(
            $this->calculatePriceToPay($orderTransfer),
        );

        return $orderTransfer;
    }

    protected function calculatePriceToPay(OrderTransfer $orderTransfer): int
    {
        $priceToPay = $orderTransfer->getTotalsOrFail()->getGrandTotal() ?? 0;

        foreach ($orderTransfer->getPayments() as $paymentTransfer) {
            if (!$paymentTransfer->getIsLimitedAmount()) {
                continue;
            }

            if ($paymentTransfer->getAvailableAmount() >= $priceToPay) {
                return 0;
            }

            $priceToPay -= $paymentTransfer->getAvailableAmount();
        }

        return $priceToPay;
    }

    protected function executeOrderPaymentExpanderPlugins(
        PaymentTransfer $paymentTransfer,
        OrderTransfer $orderTransfer
    ): PaymentTransfer {
        foreach ($this->orderPaymentExpanderPlugins as $orderPaymentExpanderPlugin) {
            $paymentTransfer = $orderPaymentExpanderPlugin->expand($orderTransfer, $paymentTransfer);
        }

        return $paymentTransfer;
    }
}
