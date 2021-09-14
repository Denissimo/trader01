<?php

namespace App\Controller;

use App\DTO\AccuralReport;
use App\Entity\Accural;
use App\Entity\User;
use App\Entity\UserTree;
use App\Service\DealGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Service\RewardCounter;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Security\Core\User\UserInterface;

class ContentController extends AbstractController
{
    /**
     * @var  TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var  DealGenerator
     */
    private $gealGenerator;

    /**
     * ContentController constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param DealGenerator $gealGenerator
     */
    public function __construct(TokenStorageInterface $tokenStorage, DealGenerator $gealGenerator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->gealGenerator = $gealGenerator;
    }


    public function buildMain(Request $request)
    {
        return $this->render('main.html.twig', []);
    }

    public function buildAccount(string $urlSelf)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $accurals = $this->buildAccurals($user);

        return $this->render('account.html.twig', [
            'url_self' => $urlSelf,
            'user' => $user,
            'accurals' => $accurals
        ]);
    }

    public function buildXlsxReport(KernelInterface $kernel)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $accurals = $this->buildAccurals($user);

        $spreadsheet = new Spreadsheet();

        $children = 0;
        $awards = 0;
        $amountUsd = 0;
        $amountBtc = 0;
        $amountEth = 0;

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Level');
        $sheet->setCellValue('B1', 'Children');
        $sheet->setCellValue('C1', 'Awards');
        $sheet->setCellValue('D1', 'Amount USD');
        $sheet->setCellValue('E1', 'Amount BTC');
        $sheet->setCellValue('F1', 'Amount ETH');


        foreach ($accurals as $num => $accural) {
            $children += $accural->getChildren();
            $awards += $accural->getAwards();
            $amountUsd += $accural->getAmountUsd();
            $amountBtc += $accural->getAmountBtc();
            $amountEth += $accural->getAmountEth();

            $sheet->setCellValue('A' . ($num + 1), $num);
            $sheet->setCellValue('B' . ($num + 1), $accural->getChildren());
            $sheet->setCellValue('C' . ($num + 1), $accural->getAwards());
            $sheet->setCellValue('D' . ($num + 1), $accural->getAmountUsd());
            $sheet->setCellValue('E' . ($num + 1), $accural->getAmountBtc());
            $sheet->setCellValue('F' . ($num + 1), $accural->getAmountEth());
        }

        $sheet->setCellValue('A' . ($num + 1), 'Total:');
        $sheet->setCellValue('B' . ($num + 1), $children);
        $sheet->setCellValue('C' . ($num + 1), $awards);
        $sheet->setCellValue('D' . ($num + 1), $amountUsd);
        $sheet->setCellValue('E' . ($num + 1), $amountBtc);
        $sheet->setCellValue('F' . ($num + 1), $amountEth);

        $sheet->setTitle("XLSX REPORT");

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

// Create a Temporary file in the system
        $fileName = 'xlsx_report.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    public function buildDeal()
    {
        $deals = $this->gealGenerator->generate(50);

        return new JsonResponse($deals);
    }

    public function buildReward(RewardCounter $rewardCounter)
    {
        $rewards = $rewardCounter->getDeals();
        $rewardStat = $rewardCounter->buildTree($rewards);

        return new JsonResponse($rewardStat);
    }

    /**
     * @param UserInterface $user
     *
     * @return AccuralReport[]|array
     */
    private function buildAccurals(UserInterface $user)
    {
        $accuralReports = [];
        $accurals = $this->getDoctrine()
            ->getManager()
            ->getRepository(Accural::class)
            ->findByUserGroupByLevel($user);

        $accuralLevels = array_column($accurals, 'level');

        $accuralsCombine = array_combine($accuralLevels, $accurals);

        $childrenGrouped = $this->getDoctrine()
            ->getManager()
            ->getRepository(UserTree::class)
            ->findChildrenGroupByLevel($user);


        $childrenLevels = array_column($childrenGrouped, 'level');

        $childrenCombine = array_combine($childrenLevels, $childrenGrouped);

        foreach ($childrenCombine as $key => $child) {
            $accuralReports[$key] = new AccuralReport(
                $child['level'],
                $child['count'],
                $accuralsCombine[$key]['count'] ?? 0,
                $accuralsCombine[$key]['amountUsd'] ?? 0,
                $accuralsCombine[$key]['amountBtc'] ?? 0,
                $accuralsCombine[$key]['amountEth'] ?? 0
            );
        }

        return $accuralReports;
    }
}