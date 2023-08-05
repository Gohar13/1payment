<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Form\ClientInputFormType;
use App\Model\ClientInput;
use App\Repository\IntervalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IntervalController extends AbstractController
{

    /**
     * @var IntervalRepository
     */
    protected IntervalRepository $intervalRepository;

    public function __construct(IntervalRepository  $intervalRepository)
    {
        $this->intervalRepository  = $intervalRepository;
    }

    #[Route('/find_range', name: 'find_range',methods: ['GET','POST'])]
    public function index(Request $request): Response
    {
        $records = [];
        $recordsNotFound = false;
        $input = new ClientInput();

        $form = $this->createForm(ClientInputFormType::class, $input);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $records = $this->intervalRepository->getByClientInput($form->getData());

            } catch (NotFoundException $e) {
                $recordsNotFound = true;
            }
        }

        return $this->render('form.html.twig', [
            'interval_form' => $form->createView(),
            'records' => $records,
            'recordsNotFound' => $recordsNotFound
        ]);
    }

}
