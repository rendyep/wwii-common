<?php

namespace WWII\Common\Helper\Collection\MsSQL;

class Department implements \WWII\Common\Helper\HelperCollectionInterface
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

    public function getDepartmentList()
    {
        $departmentList = array();

        $result = $this->databaseManager
            ->prepare('SELECT fDeptCode, fDeptName FROM t_BMSM_DeptMst');
        $result->execute();

        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $departmentList[$row['fDeptCode']] = $row['fDeptName'];
        }

        return $departmentList;
    }

    public function findOneByKode($kode)
    {
        $result = $this->databaseManager->prepare('SELECT TOP 1 * FROM t_BMSM_DeptMst WHERE fDeptCode = :kode');
        $result->bindParam(':kode', $kode);
        $result->execute();

        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    public function findOneByNama($nama)
    {
        $result = $this->databaseManager->prepare("SELECT TOP 1 * FROM t_BMSM_DeptMst WHERE fDeptName = :nama");
        $result->bindValue(':nama', $nama);
        $result->execute();

        return $result->fetch(\PDO::FETCH_ASSOC);
    }
}
