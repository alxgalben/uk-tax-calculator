<?php

namespace App\Controller;

use App\Service\TaxCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class TaxCalculatorController extends AbstractController
{
    #[Route('/tax-calculator', name: 'tax_calculator')]
    public function calculate(Request $request, TaxCalculatorService $taxCalculator): Response
    {
        $form = $this->createFormBuilder(null, ['csrf_protection' => true])
        ->add('salary', NumberType::class, [
            'label' => 'Gross Annual Salary',
            'attr' => ['class' => 'form-control'],
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Salary is required.',
                ]),
                new Assert\Regex([
                    'pattern' => '/^[0-9]+(\.[0-9]{1,2})?$/',
                    'message' => 'Please enter a valid positive number. Letters are not allowed.',
                ]),
            ],
        ])
        ->add('calculate', SubmitType::class, [
            'label' => 'Calculate',
            'attr' => ['class' => 'btn btn-primary mt-3']
        ])
        ->getForm();

        $form->handleRequest($request);

        $result = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $salary = $form->get('salary')->getData();

            $taxPaid = $taxCalculator->calculateTax($salary);
            $netSalary = $taxCalculator->calculateNetSalary($salary);

            $grossMonthlySalary = $salary / 12;
            $netMonthlySalary = $netSalary / 12;
            $monthlyTaxPaid = $taxPaid / 12;

            $result = [
                'grossSalary' => $salary,
                'grossMonthlySalary' => $grossMonthlySalary,
                'netSalary' => $netSalary,
                'netMonthlySalary' => $netMonthlySalary,
                'taxPaid' => $taxPaid,
                'monthlyTaxPaid' => $monthlyTaxPaid,
            ];
        }

        return $this->render('tax_calculator/index.html.twig', [
            'form' => $form->createView(),
            'result' => $result,
        ]);
    }
}
