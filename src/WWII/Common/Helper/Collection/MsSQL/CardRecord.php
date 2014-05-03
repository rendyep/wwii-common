<?php

namespace WWII\Common\Helper\Collection\MsSQL;

class CardRecord implements \WWII\Common\Helper\HelperCollectionInterface
{
    const SHIFT_1 = 1;

    const SHIFT_2 = 2;

    const SHIFT_3 = 3;

    const EVENT_IN = 1;

    const EVENT_BREAK_OUT = 2;

    const EVENT_BREAK_IN = 3;

    const EVENT_OUT = 4;

    const EVENT_OVERTIME_START = 5;

    const EVENT_OVERTIME_END = 6;

    protected $serviceManager;

    protected $entityManager;

    protected $databaseManager;

    protected $options;

    public function __construct(\WWII\Service\ServiceManagerInterface $serviceManager, \Doctrine\ORM\EntityManager $entityManager)
    {
        $this->serviceManager = $serviceManager;
        $this->entityManager = $entityManager;
        $this->databaseManager = $serviceManager->get('DatabaseManager');
    }

    protected function parseTime(\DateTime $date, $shift, $event)
    {
        $time = array();

        switch ($date->format('N')) {
            //senin
            case 1:
                switch ($shift) {
                    case self::SHIFT_1:
                        switch ($event) {
                            case self::EVENT_IN:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 06:00:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 08:00:00');
                                break;
                            case self::EVENT_OUT:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 16:00:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 18:00:00');
                                break;
                            case self::EVENT_BREAK_OUT:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 11:00:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 12:00:00');
                                break;
                            case self::EVENT_BREAK_IN:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 12:30:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 13:30:00');
                                break;
                        }
                        break;
                    case self::SHIFT_2:
                        break;
                    case self::SHIFT_3:
                        break;
                }
                break;
            //selasa
            case 2:
            //rabu
            case 3:
            //kamis
            case 4:
                switch ($shift) {
                    case self::SHIFT_1:
                        switch ($event) {
                            case self::EVENT_IN:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 06:30:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 08:30:00');
                                break;
                            case self::EVENT_OUT:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 15:00:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 17:00:00');
                                break;
                            case self::EVENT_BREAK_OUT:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 11:00:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 12:00:00');
                                break;
                            case self::EVENT_BREAK_IN:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 12:30:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 13:30:00');
                                break;
                        }
                        break;
                    case self::SHIFT_2:
                        break;
                    case self::SHIFT_3:
                        break;
                }
                break;
            //jum'at
            case 5:
                switch ($shift) {
                    case self::SHIFT_1:
                        switch ($event) {
                            case self:: EVENT_IN:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 06:30:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 08:30:00');
                                break;
                            case self::EVENT_OUT:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 16:00:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 18:00:00');
                                break;
                            case self::EVENT_BREAK_OUT:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 11:00:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 12:00:00');
                                break;
                            case self::EVENT_BREAK_IN:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 12:30:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 13:30:00');
                                break;
                        }
                        break;
                    case self::SHIFT_2:
                        break;
                    case self::SHIFT_3:
                        break;
                }
                break;
            //sabtu
            case 6:
                switch ($shift) {
                    case self::SHIFT_1:
                        switch ($event) {
                            case self::EVENT_IN:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 06:30:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 08:30:00');
                                break;
                            case self::EVENT_OUT:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 10:30:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 12:30:00');
                                break;
                            case self::EVENT_BREAK_OUT:
                            case self::EVENT_BREAK_IN:
                                throw new \Exception('No break on Saturday');
                                break;
                        }
                        break;
                    case self::SHIFT_2:
                        break;
                    case self::SHIFT_3:
                        break;
                }
                break;
            //minggu
            case 7:
                switch ($shift) {
                    case self::SHIFT_1:
                        switch ($event) {
                            case self::EVENT_IN:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 06:30:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 08:30:00');
                                break;
                            case self::EVENT_OUT:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 15:30:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 17:30:00');
                                break;
                            case self::EVENT_BREAK_OUT:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 11:00:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 12:00:00');
                                break;
                            case self::EVENT_BREAK_IN:
                                $time['start'] = new \DateTime($date->format('Y-m-d') . ' 12:30:00');
                                $time['end'] = new \DateTime($date->format('Y-m-d') . ' 13:30:00');
                                break;
                        }
                        break;
                    case self::SHIFT_2:
                        break;
                    case self::SHIFT_3:
                        break;
                }
                break;

        }

        return $time;
    }

    public function setOptions(array $options)
    {
        if (empty($options)) {
            return $this->options = $this->getOptions();
        }

        $this->options = array(
            'date' => isset($options['date']) ? $options['date'] : null,
            'shift' => isset($options['shift']) ? $options['shift'] : null,
            'event' => isset($options['event']) ? $options['event'] : null,
            'location' => isset($options['location']) ? $options['location'] : null,
        );
    }

    public function getOptions()
    {
        if ($this->options === null) {
            $this->options = array(
                'date' => new \DateTime(),
                'shift' => self::SHIFT_1,
                'event' => self::EVENT_IN,
                'location' => null
            );
        }

        return $this->options;
    }

    public function getLogCountByShift()
    {
        $options = $this->getOptions();
        $time = $this->parseTime($options['date'], $options['shift'], $options['event']);

        $rs = $this->databaseManager->prepare("
            SELECT COUNT(uniqueUserLog.nik) as count
            FROM (
                SELECT t_PALM_PersonnelFileMst.fCode as nik
                FROM t_AMSD_CardRecord
                INNER JOIN t_PALM_PersonnelFileMst ON t_AMSD_CardRecord.fCode = t_PALM_PersonnelFileMst.fCode
                WHERE t_AMSD_CardRecord.fDateTime >= :timeStart
                    AND t_AMSD_CardRecord.fDateTime <= :timeEnd
                GROUP BY t_PALM_PersonnelFileMst.fCode
            ) uniqueUserLog
        ");

        $rs->bindParam(':timeStart', $time['start']->format('Y-m-d H:i:s'));
        $rs->bindParam(':timeEnd', $time['end']->format('Y-m-d H:i:s'));
        $rs->execute();

        return $rs->fetch(\PDO::FETCH_OBJ)->count;
    }
}
