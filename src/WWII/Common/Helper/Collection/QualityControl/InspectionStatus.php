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

    public function getInspectionStatus($inspectionItem) {
        if (! $inspectionItem instanceOf \WWII\Domain\Erp\QualityControl\GeneralInspection\AssemblingInspectionItem
            && ! $inspectionItem instanceOf \WWII\Domain\Erp\QualityControl\GeneralInspection\FinishingInspectionItem
            && ! $inspectionItem instanceOf \WWII\Domain\Erp\QualityControl\GeneralInspection\PackagingInspectionItem
            && ! $inspectionItem instanceOf \WWII\Domain\Erp\QualityControl\GeneralInspection\WhitewoodInspectionItem
            && ! $inspectionItem instanceOf \WWII\Domain\Erp\QualityControl\GeneralInspection\PembahananPanelInspectionItem
        ) {
            throw new \Exception('InspectionItem must be instance of one on the list:'
                . PHP_EOL . '\WWII\Domain\Erp\QualityControl\GeneralInspection\AssemblingInspectionItem'
                . PHP_EOL . '\WWII\Domain\Erp\QualityControl\GeneralInspection\FinishingInspectionItem'
                . PHP_EOL . '\WWII\Domain\Erp\QualityControl\GeneralInspection\PackagingInspectionItem'
                . PHP_EOL . '\WWII\Domain\Erp\QualityControl\GeneralInspection\WhitewoodInspectionItem'
                . PHP_EOL . '\WWII\Domain\Erp\QualityControl\GeneralInspection\PembahananPanelInspectionItem'
                );
        }

        $acceptanceLimit = $this->entityManager->createQueryBuilder()
            ->select('acceptanceLimit')
            ->from('WWII\Domain\Erp\QualityControl\GeneralInspection\AcceptanceLimit', 'acceptanceLimit')
            ->leftJoin('acceptanceLimit.acceptanceIndex', 'acceptanceIndex')
            ->where('acceptanceIndex.code = :acceptanceIndex')
                ->setParameter('acceptanceIndex', $inspectionItem->getAcceptanceIndex())
            ->andWHere('acceptanceLimit.sampleSize = :sampleSize')
                ->setParameter('sampleSize', $inspectionItem->getJumlahInspeksi())
            ->getQuery()
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getOneOrNullResult();

        $jumlahItemBuruk = $inspectionItem->getJumlahTotalItemBuruk();
        $minJumlahItemBuruk = $acceptanceLimit->getLimit();

        return $jumlahItemBuruk <= $minJumlahItemBuruk;
    }
}
