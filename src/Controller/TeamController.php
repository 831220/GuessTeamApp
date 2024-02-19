<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TeamController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    public function index(): Response
    {
        $em = $this->doctrine->getManager();
        $teamsList = $em->getRepository(Team::class)->findBy([], ['name' => 'ASC']);

        return $this->render('team/team_list.html.twig', [
            'teamsList' => $teamsList,
        ]);
    }

    public function createTeam(Request $request): Response
    {
        $em = $this->doctrine->getManager();

        $team = new Team();
        $form_teams = $this->createForm(TeamType::class, $team);

        $form_teams->handleRequest($request);
        if ($form_teams->isSubmitted() && $form_teams->isValid()){
            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('team_index');
        };

        return $this->render('team/create_team.html.twig', [
            'form_teams'=>$form_teams->createView()
        ]);
    }

    public function deleteTeam($id)
    {
        $em = $this->doctrine->getManager();

        $team = $em->getRepository(Team::class)->find($id);

        if (!$team) {
            throw $this->createNotFoundException('Team was not found');
        }
        $em->remove($team);
        $em->flush();

        return $this->redirectToRoute('team_index');
    }

    public function updateTeam(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $team = $em->getRepository(Team::class)->find($id);

        if (!$team) {
            throw $this->createNotFoundException('Team was not found');
        }
        $form_teams = $this->createForm(TeamType::class, $team, ['attr' => ['is_edit'=>true]]);

        $form_teams->handleRequest($request);
        if ($form_teams->isSubmitted() && $form_teams->isValid()){
            // Aplicar los datos del formulario al objeto $team
            //$team = $form_teams->getData();
            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('team_index');
        };

        return $this->render('team/update_team.html.twig', [
            'form_teams'=>$form_teams->createView()
        ]);
    }



}
