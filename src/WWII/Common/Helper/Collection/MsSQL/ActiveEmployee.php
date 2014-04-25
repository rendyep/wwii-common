<?php

namespace WWII\Common\Helper\Collection\MsSQL;

class ActiveEmployee implements \WWII\Common\Helper\HelperCollectionInterface
{
    protected $serviceManager;

    protected $entityManager;

    protected $databaseManager;

    const EMPLOYEE_LOCATION_OFFICE = 0;

    const EMPLOYEE_LOCATION_PRODUCTION = 1;

    public function __construct(\WWII\Service\ServiceManagerInterface $serviceManager, \Doctrine\ORM\EntityManager $entityManager)
    {
        $this->serviceManager = $serviceManager;
        $this->entityManager = $entityManager;
        $this->databaseManager = $serviceManager->get('DatabaseManager');
    }

    public function findByLocation($location)
    {
        switch ($location) {
            case EMPLOYEE_LOCATION_OFFICE:
                $result = $this->databaseManager->prepare('SELECT TOP 1 * FROM t_PALM_PersonnelFileMst WHERE fCode LIKE :nik');
                $result->bindParam(":nik", '0%');
                $result->execute();
                return $result->fetch(\PDO::FETCH_ASSOC);
                break;
            case EMPLOYEE_LOCATION_PRODUCTION:
                $result = $this->databaseManager->prepare('SELECT TOP 1 * FROM t_PALM_PersonnelFileMst WHERE fCode NOT LIKE :nik');
                $result->bindParam(":nik", '0%');
                $result->execute();
                return $result->fetch(\PDO::FETCH_ASSOC);
                break;
            default:
                return;
        }
    }

    public function findOneByNik($nik)
    {
        $result = $this->databaseManager->prepare('SELECT TOP 1 * FROM t_PALM_PersonnelFileMst WHERE fCode = :nik');
        $result->bindParam(':nik', $nik);
        $result->execute();

        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    public function findOneByNama($nama)
    {
        $result = $this->databaseManager->prepare("SELECT TOP 1 * FROM t_PALM_PersonnelFileMst WHERE fName = :nama");
        $result->bindValue(':nama', $nama);
        $result->execute();

        return $result->fetch(\PDO::FETCH_ASSOC);
    }
}
