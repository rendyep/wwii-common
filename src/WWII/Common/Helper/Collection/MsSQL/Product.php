<?php

namespace WWII\Common\Helper\Collection\MsSQL;

class Product implements \WWII\Common\Helper\HelperCollectionInterface
{
    protected $serviceManager;

    protected $entityManager;

    protected $databaseManager;

    const EMPLOYEE_LOCATION_OFFICE = 0;

    const EMPLOYEE_LOCATION_PRODUCTION = 1;

    public function __construct(
        \WWII\Service\ServiceManagerInterface $serviceManager,
        \Doctrine\ORM\EntityManager $entityManager
    ) {
        $this->serviceManager = $serviceManager;
        $this->entityManager = $entityManager;
        $this->databaseManager = $serviceManager->get('DatabaseManager');
    }

    public function find($key)
    {
        $key = strtoupper($key) . '%';

        $rsProduct = $this->databaseManager
            ->prepare('SELECT TOP 10 * FROM t_COPM_Fgmst'
                .   ' WHERE UPPER(fFgCode) LIKE :key1 OR fFgName LIKE :key2 OR fFgEName LIKE :key3');

        $rsProduct->bindParam(':key1', $key);
        $rsProduct->bindParam(':key2', $key);
        $rsProduct->bindParam(':key3', $key);
        $rsProduct->execute();

        $productList = $rsProduct->fetchAll(\PDO::FETCH_ASSOC);

        return $productList;
    }
}
