<?php

namespace WWII\Common\Helper\Collection\QualityControl;

class InspectionStatus implements \WWII\Common\Helper\HelperCollectionInterface
{
    protected $serviceManager;

    protected $entityManager;

    public function __construct(
        \WWII\Service\ServiceManagerInterface $serviceManager,
        \Doctrine\ORM\EntityManager $entityManager
    ) {
        $this->serviceManager = $serviceManager;
        $this->entityManager = $entityManager;
        $this->databaseManager = $serviceManager->get('DatabaseManager');
    }

    public function getInspectionStatus(
        \WWII\Domain\Erp\QualityControl\GeneralInspection\DailyInspectionItem $dailyInspectionItem
    ) {
        $acceptanceLimit = $this->entityManager->createQueryBuilder()
            ->select('acceptanceLimit')
            ->from('WWII\Domain\Erp\QualityControl\GeneralInspection\AcceptanceLimit', 'acceptanceLimit')
            ->leftJoin('acceptanceLimit.acceptanceIndex', 'acceptanceIndex')
            ->where('acceptanceIndex.code = :acceptanceIndex')
                ->setParameter('acceptanceIndex', $dailyInspectionItem->getAcceptanceIndex())
            ->andWHere('acceptanceLimit.sampleSize = :sampleSize')
                ->setParameter('sampleSize', $dailyInspectionItem->getJumlahInspeksi())
            ->getQuery()
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getOneOrNullResult();

        $jumlahItemBuruk = $dailyInspectionItem->getJumlahTotalItemBuruk();
        $minJumlahItemBuruk = $acceptanceLimit->getLimit();

        return $jumlahItemBuruk <= $minJumlahItemBuruk;
    }
}
