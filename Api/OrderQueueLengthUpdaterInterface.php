<?php

namespace Creativestyle\ProductPositiveIndicatorsExtension\Api;

/**
 * Interface for updating fast shipping order queue
 * @api
 */
interface OrderQueueLengthUpdaterInterface
{
    /**
     * Update order queue value
     *
     * @api
     * @param int $orderQueueLength
     * @return boolean
     */
    public function updateOrderQueueLength($orderQueueLength);
}