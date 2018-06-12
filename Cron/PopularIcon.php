<?php
namespace Creativestyle\ProductPositiveIndicatorsExtension\Cron;

class PopularIcon
{
    /**
     * @var \Creativestyle\ProductPositiveIndicatorsExtension\Model\PopularIconProducts
     */
    protected $popularIconProducts;

    public function __construct(
        \Creativestyle\ProductPositiveIndicatorsExtension\Model\PopularIconProducts $popularIconProducts
    )
    {
        $this->popularIconProducts = $popularIconProducts;
    }

    public function execute()
    {
        $this->popularIconProducts->execute();
    }
}