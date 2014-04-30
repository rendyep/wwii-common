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

        $rsDepartment = $this->databaseManager
            ->prepare('SELECT fDeptCode, fDeptName FROM t_BMSM_DeptMst');
        $rsDepartment->execute();

        if (count($rsDepartment->fetch(\PDO::FETCH_ASSOC)) === 0) {
            return null;
        }

        while ($row = $rsDepartment->fetch(\PDO::FETCH_ASSOC)) {
            $departmentList[$row['fDeptCode']] = $row['fDeptName'];
        }

        return $departmentList;
    }

    public function findOneByKode($kode)
    {
        $rsDepartment = $this->databaseManager->prepare('SELECT TOP 1 * FROM t_BMSM_DeptMst WHERE fDeptCode = :kode');
        $rsDepartment->bindParam(':kode', $kode);
        $rsDepartment->execute();

        if (count($rsDepartment->fetch(\PDO::FETCH_ASSOC)) === 0) {
            return null;
        }

        return $rsDepartment->fetch(\PDO::FETCH_ASSOC);
    }

    public function findOneByNama($nama)
    {
        $rsDepartment = $this->databaseManager->prepare("SELECT TOP 1 * FROM t_BMSM_DeptMst WHERE fDeptName = :nama");
        $rsDepartment->bindValue(':nama', $nama);
        $rsDepartment->execute();

        if (count($rsDepartment->fetch(\PDO::FETCH_ASSOC)) === 0) {
            return null;
        }

        return $rsDepartment->fetch(\PDO::FETCH_ASSOC);
    }
}
