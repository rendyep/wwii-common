<?php

namespace WWII\Common\Helper\Collection\MsSQL;

class Karyawan implements \WWII\Common\Helper\HelperCollectionInterface
{
    protected $serviceManager;

    protected $entityManager;

    protected $databaseManager;

    public function __construct(\WWII\Service\ServiceManagerInterface $serviceManager, \Doctrine\ORM\EntityManager $entityManager)
    {
        $this->serviceManager = $serviceManager;
        $this->entityManager = $entityManager;
        $this->databaseManager = $serviceManager->get('DatabaseManager');
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
