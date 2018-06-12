<?php
namespace Creativestyle\ProductPositiveIndicatorsExtension\Cron;

class RecentlyBought
{
    /**
     * @var \Creativestyle\ProductPositiveIndicatorsExtension\Model\RecentlyBoughtProducts
     */
    protected $recentlyBoughtProducts;

    public function __construct(
        \Creativestyle\ProductPositiveIndicatorsExtension\Model\RecentlyBoughtProducts $recentlyBoughtProducts
    )
    {
        $this->recentlyBoughtProducts = $recentlyBoughtProducts;
    }

    public function execute()
    {
        $this->recentlyBoughtProducts->execute();
    }
}