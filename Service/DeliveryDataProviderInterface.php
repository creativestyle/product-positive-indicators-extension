<?php

namespace Creativestyle\ProductPositiveIndicatorsExtension\Service;

interface DeliveryDataProviderInterface
{
    /**
     * Get working days
     *
     * @param [] $config
     * @return array
     */
    public function prepareDeliveryData($config);
}