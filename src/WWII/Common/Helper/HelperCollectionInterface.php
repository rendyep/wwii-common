<?php

namespace WWII\Common\Helper;

interface HelperCollectionInterface
{
    public function __construct(\WWII\Service\ServiceManagerInterface $serviceManager, \Doctrine\ORM\EntityManager $entityManager);
}
