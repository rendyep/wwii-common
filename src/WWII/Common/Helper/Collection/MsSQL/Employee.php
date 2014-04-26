<?php

namespace WWII\Common\Helper\Collection\MsSQL;

class Employee implements \WWII\Common\Helper\HelperCollectionInterface
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
                $rsEmployee = $this->databaseManager->prepare('SELECT TOP 1 * FROM t_PALM_PersonnelFileMst WHERE fCode LIKE :nik');
                $rsEmployee->bindParam(":nik", '0%');
                $rsEmployee->execute();
                return $rsEmployee->fetch(\PDO::FETCH_ASSOC);
                break;
            case EMPLOYEE_LOCATION_PRODUCTION:
                $rsEmployee = $this->databaseManager->prepare('SELECT TOP 1 * FROM t_PALM_PersonnelFileMst WHERE fCode NOT LIKE :nik');
                $rsEmployee->bindParam(":nik", '0%');
                $rsEmployee->execute();
                return $rsEmployee->fetch(\PDO::FETCH_ASSOC);
                break;
            default:
                return;
        }
    }

    public function findOneByNik($nik)
    {
        $rsEmployee = $this->databaseManager->prepare('SELECT TOP 1 * FROM t_PALM_PersonnelFileMst WHERE fCode = :nik');
        $rsEmployee->bindParam(':nik', $nik);
        $rsEmployee->execute();

        return $rsEmployee->fetch(\PDO::FETCH_ASSOC);
    }

    public function findOneByNama($nama)
    {
        $rsEmployee = $this->databaseManager->prepare("SELECT TOP 1 * FROM t_PALM_PersonnelFileMst WHERE fName = :nama");
        $rsEmployee->bindValue(':nama', $nama);
        $rsEmployee->execute();

        return $rsEmployee->fetch(\PDO::FETCH_ASSOC);
    }

    public function findByStatus($status)
    {
        $flag = 0;
        if ($status === false || strtoupper($status) !== 'ACTIVE' || strtoupper($status) !== 'AKTIF') {
            $flag = 1;
        }

        $rsEmployee = $this->databaseManager->query("SELECT t_PALM_PersonnelFileMst.fCode,"
            . " t_PALM_PersonnelFileMst.fName, t_PALM_PersonnelFileMst.fInDate,"
            . " t_BMSM_DeptMst.fDeptName"
            . " FROM t_PALM_PersonnelFileMst"
            . " LEFT JOIN t_BMSM_DeptMst ON t_PALM_PersonnelFileMst.fDeptCode = t_BMSM_DeptMst.fDeptCode"
            . " AND fDFlag = {$flag}"
            . " ORDER BY fInDate ASC, fCode ASC");

        $employeeList = $rsEmployee->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function isActive($nik)
    {
        $rsEmployee = $this->databaseManager->prepare('SELECT TOP 1 * FROM t_PALM_PersonnelFileMst
            WHERE fCode = :nik AND fDFlag = :flag');
        $rsEmployee->bindParam(':nik', $nik);
        $rsEmployee->bindParam(':flag', $flag);
        $rsEmployee->execute();

        $result = $rsEmployee->fetch(\PDO::FETCH_ASSOC);

        return $result === null || empty($result);
    }
}
