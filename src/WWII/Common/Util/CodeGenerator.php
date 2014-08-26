<?php

use WWII\Service\Provider\Database\DatabaseManager;

namespace WWII\Common\Util;

class CodeGenerator
{
    protected $databaseManager;

    protected $transactionCode;

    protected $transactionName;

    protected $year;

    protected $month;

    protected $lastNumber;

    protected $dateLastUpdated;

    public function construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function setTransactionCode($transactionCode)
    {
        $this->transactionCode = strtoupper($transactionCode);
    }

    public function getTransactionCode()
    {
        return $this->transactionCode;
    }

    public function setTransactionName($transactionName)
    {
        $this->transactionName = strtoupper($transactionName);
    }

    public function getTransactionName()
    {
        return $this->transactionName;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setMonth($month)
    {
        $this->month = $month;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function setLastNumber($lastNumber)
    {
        $this->lastNumber = $lastNumber;
    }

    public function getLastNumber()
    {
        return $this->lastNumber;
    }

    public function setDateLastUpdated(\DateTime $dateLastUpdated)
    {
        $this->dateLastUpdated = $dateLastUpdated;
    }

    public function getDateLastUpdated()
    {
        return $this->dateLastUpdated;
    }

    public function getValue()
    {
        $this->validate();

        $month = $this->month;
        if ($month < 10) {
            $month = '0' . $month;
        }

        $lastNumber = $this->lastNumber;
        if ($lastNumber < 10) {
            $lastNumber = '00' . $lastNumber;
        } elseif ($lastNumber >= 10 && $lastNumber < 100) {
            $lastNumber = '0' . $lastNumber;
        }

        return "{$this->transactionCode}/{$this->year}/{$this->month}/{$lastNumber}";
    }

    public function generate($transactionCode, $transactionName)
    {
        $now = new \DateTime();

        $this->transactionCode = strtoupper($transactionCode);
        $this->transactionName = strtoupper($transactionName);

        $this->year = $now->format('Y');
        $this->month = $now->format('n');
        $this->lastNumber = 0;
        $this->dateLastUpdated = clone($now);

        $this->validate(false);

        $query = $this->databaseManager->prepare("
            INSERT INTO
                a_System_TransactionCode (
                    fTransactionCode,
                    fTransactionName,
                    fYear,
                    fMonth,
                    fLastNumber,
                    fDateLastUpdated
                )
            VALUES (
                '{$this->transactionCode}',
                '{$this->transactionName}',
                {$this->year},
                {$this->month},
                {$this->lastNumber},
                '{$this->dateLastUpdated->format('Y-m-d')}'
            )
        ");
        $query->execute();
    }

    public function increment()
    {
        $now = new \DateTime();

        if ($this->lastNumber == null) {
            $this->lastNumber = 1;
        } else {
            $this->lastNumber++;
        }

        if ($this->month == null || $this->month != $now->format('n')) {
            $this->lastNumber = 1;
        }
        $this->month = $now->format('n');

        if ($this->year == null || $this->year != $now->format('Y')) {
            $this->lastNumber = 1;
        }
        $this->year = $now->format('Y');

        $this->validate();

        $query = $this->databaseManager->prepare("
            UPDATE
                a_System_TransactionCode
            SET
                fYear = {$this->year},
                fMonth = {$this->month},
                fLastNumber = {$this->lastNumber},
                fDateLastUpdated = '{$this->dateLastUpdated->format('Y-m-d')}'
            WHERE
                fTransactionCode = '{$this->transactionCode}'
                AND fTransactionName = '{$this->transactionName}'
        ");
        $query->execute();
    }

    protected function validate($update = true)
    {
        if (empty($this->transactionCode)) {
            throw new \Exception($this->getError(1));
        }

        if (empty($this->transactionName)) {
            throw new \Exception($this->getError(3));
        }

        if (! $update && $this->isTransactionCodeExists($this->transactionCode)) {
            throw new \Exception($this->getError(2));
        }

        if (! $update && $this->isTransactionNameExists($this->transactionName)) {
            throw new \Exception($this->getError(4));
        }
    }

    protected function isTransactionCodeExists($transactionCode)
    {
        $transactionCode = strtoupper($transactionCode);

        $query = $this->databaseManager->prepare("
            SELECT
                *
            FROM
                a_System_TransactionCode
            WHERE
                UPPER(fTransactionCode) = '{$transactionCode}'
        ");
        $query->execute();
        $result = $query->fetch(\PDO::FETCH_ASSOC);

        return (! empty($result));
    }

    protected function isTransactionNameExists($transactionName)
    {
        $transactionName = strtoupper($transactionName);

        $query = $this->databaseManager->prepare("
            SELECT
                *
            FROM
                a_System_TransactionCode
            WHERE
                UPPER(fTransactionName) = '{$transactionName}'
        ");
        $query->execute();
        $result = $query->fetch(\PDO::FETCH_ASSOC);

        return (! empty($result));
    }

    public function getError($errNo)
    {
        switch ($errNo) {
            case 1:
                return "Transaction Code must not be empty.";
            case 2:
                return "Transaction Code must be unique (transaction code \"{$this->transactionCode}\" exists).";
            case 3:
                return "Transaction Name must not be empty.";
            case 4:
                return "Transaction Name must be unique (transaction code \"{$this->transactionName}\" exists).";
        }
    }
