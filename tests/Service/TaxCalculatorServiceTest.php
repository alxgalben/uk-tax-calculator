<?php

namespace App\Tests\Service;

use App\Service\TaxCalculatorService;
use App\Entity\TaxBand;
use App\Repository\TaxBandRepository;
use PHPUnit\Framework\TestCase;

class TaxCalculatorServiceTest extends TestCase
{
    public function testCalculateTax()
    {
        // mocking the repository
        $taxBandRepository = $this->createMock(TaxBandRepository::class);

        $taxBand1 = (new TaxBand())->setLowerLimit(0)->setUpperLimit(5000)->setRate(0);
        $taxBand2 = (new TaxBand())->setLowerLimit(5000)->setUpperLimit(20000)->setRate(20);
        $taxBand3 = (new TaxBand())->setLowerLimit(20000)->setUpperLimit(null)->setRate(40);

        $taxBandRepository->method('findAll')->willReturn([$taxBand1, $taxBand2, $taxBand3]);

        $taxCalculator = new TaxCalculatorService($taxBandRepository);

        $this->assertEquals(0, $taxCalculator->calculateTax(4000));
        $this->assertEquals(3000, $taxCalculator->calculateTax(15000));
        $this->assertEquals(11000, $taxCalculator->calculateTax(40000));
    }

    public function testCalculateNetSalary()
    {
        $taxBandRepository = $this->createMock(TaxBandRepository::class);

        $taxBand1 = (new TaxBand())->setLowerLimit(0)->setUpperLimit(5000)->setRate(0);
        $taxBand2 = (new TaxBand())->setLowerLimit(5000)->setUpperLimit(20000)->setRate(20);
        $taxBand3 = (new TaxBand())->setLowerLimit(20000)->setUpperLimit(null)->setRate(40);

        $taxBandRepository->method('findAll')->willReturn([$taxBand1, $taxBand2, $taxBand3]);

        $taxCalculator = new TaxCalculatorService($taxBandRepository);

        $this->assertEquals(40000 - 11000, $taxCalculator->calculateNetSalary(40000));
    }

    public function testCalculateTaxForNegativeSalary()
    {
        $taxBandRepository = $this->createMock(TaxBandRepository::class);
        $taxCalculator = new TaxCalculatorService($taxBandRepository);
        $this->assertEquals(0, $taxCalculator->calculateTax(-1000));
    }

    public function testCalculateTaxForBoundaryCondition()
    {
        $taxBandRepository = $this->createMock(TaxBandRepository::class);
        $taxBand1 = (new TaxBand())->setLowerLimit(0)->setUpperLimit(5000)->setRate(0);
        $taxBandRepository->method('findAll')->willReturn([$taxBand1]);

        $taxCalculator = new TaxCalculatorService($taxBandRepository);
        
        $this->assertEquals(0, $taxCalculator->calculateTax(5000));
    }
}
