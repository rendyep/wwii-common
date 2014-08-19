<?php

namespace WWII\Common\Helper\Collection\MsSQL;

class Karyawan implements \WWII\Common\Helper\HelperCollectionInterface
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

        $rsKaryawan = $this->databaseManager
            ->prepare("
                SELECT
                    TOP 10 *
                FROM
                    t_PALM_PersonnelFileMst
                LEFT JOIN
                    t_BMSM_DeptMst ON t_BMSM_DeptMst.fDeptCode LIKE t_PALM_PersonnelFileMst.fDeptCode
                WHERE
                    UPPER(fCode) LIKE :key1
                    OR fName LIKE :key2
        ");

        $rsKaryawan->bindParam(':key1', $key);
        $rsKaryawan->bindParam(':key2', $key);
        $rsKaryawan->execute();

        $karyawanList = $rsKaryawan->fetchAll(\PDO::FETCH_ASSOC);

        return $karyawanList;
    }
}
