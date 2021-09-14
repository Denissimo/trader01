<?php

namespace App\Controller;

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
        $childrenCombile = $this->loadChildren($user);
        $accuralCombile = $this->loadAccurals($user);

        return $this->render('account.html.twig', [
            'url_self' => $urlSelf,
            'user' => $user,
            'children' => $childrenCombile,
            'accurals' => $accuralCombile
        ]);
    }

    public function buildXlsxReport(KernelInterface $kernel)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $children = $this->loadChildren($user);
        $accurals = $this->loadAccurals($user);

        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Level');
        $sheet->setCellValue('B1', 'Children');
        $sheet->setCellValue('C1', 'Awards');
        $sheet->setCellValue('D1', 'Amount USD');
        $sheet->setCellValue('E1', 'Amount BTC');
        $sheet->setCellValue('F1', 'Amount ETH');

        foreach ($children as $num => $child) {
            $sheet->setCellValue('A' . ($num + 1), $num);
            $sheet->setCellValue('B' . ($num + 1), $child['count']);
            $sheet->setCellValue('C' . ($num + 1), $accurals[$num]['count'] ?? 0);
            $sheet->setCellValue('D' . ($num + 1), $accurals[$num]['amountUsd'] ?? 0);
            $sheet->setCellValue('E' . ($num + 1), $accurals[$num]['amountBtc'] ?? 0);
            $sheet->setCellValue('F' . ($num + 1), $accurals[$num]['amountEth'] ?? 0);
        }

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

    private function loadChildren(UserInterface $user)
    {
        $childrenGrouped = $this->getDoctrine()
            ->getManager()
            ->getRepository(UserTree::class)
            ->findChildrenGroupByLevel($user);


        $childrenLevels = array_column($childrenGrouped, 'level');

        return array_combine($childrenLevels, $childrenGrouped);
    }

    private function loadAccurals(UserInterface $user)
    {
        $accurals = $this->getDoctrine()
            ->getManager()
            ->getRepository(Accural::class)
            ->findByUserGroupByLevel($user);

        $accuralLevels = array_column($accurals, 'level');

        return array_combine($accuralLevels, $accurals);

    }
}