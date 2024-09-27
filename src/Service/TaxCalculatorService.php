<?php

namespace App\Service;

use App\Repository\TaxBandRepository;

class TaxCalculatorService
{
    private $taxBandRepository;

    public function __construct(TaxBandRepository $taxBandRepository)
    {
        $this->taxBandRepository = $taxBandRepository;
    }

    public function calculateTax(float $salary): float
    {
        $taxBands = $this->taxBandRepository->findAll();
        $totalTax = 0;
    
        foreach ($taxBands as $band) {
            $lowerLimit = $band->getLowerLimit();
            $upperLimit = $band->getUpperLimit() ?? $salary;  // entire salary is taxed if upper limit is null
            $rate = $band->getRate();
    
            // if salary > lower limit, calculate the value after teax for the current band
            if ($salary > $lowerLimit) {
                $taxableIncome = min($salary, $upperLimit) - $lowerLimit;
                $totalTax += $taxableIncome * ($rate / 100);
            }
        }
    
        return $totalTax;
    }
    

    public function calculateNetSalary(float $salary): float
    {
        return $salary - $this->calculateTax($salary);
    }
}
