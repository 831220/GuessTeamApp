<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlayerController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function index(): Response
    {
        $em = $this->doctrine->getManager();
        $playerList = $em->getRepository(Player::class)->findBy([], ['name' => 'ASC']);
        return $this->render('player/player_list.html.twig', [
            'playerList'=>$playerList
        ]); //in the array goes the data

    }

    public function createPlayer(Request $request): Response
    {
        $em = $this->doctrine->getManager();

        $player = new Player();
        $form_players = $this->createForm(PlayerType::class, $player);

        $form_players->handleRequest($request);
        if ($form_players->isSubmitted() && $form_players->isValid()){
            $em->persist($player);
            $em->flush();

            return $this->redirectToRoute('player_index');
        };

        return $this->render('player/create_player.html.twig', [
            'form_players'=>$form_players->createView()
        ]);


    }

    public function updatePlayer(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $player = $em->getRepository(Player::class)->find($id);

        if (!$player) {
            throw $this->createNotFoundException('PLayer not found');
        }
        $form_players = $this->createForm(PlayerType::class, $player, ['attr' => ['is_edit'=>true]]);

        $form_players->handleRequest($request);
        if ($form_players->isSubmitted() && $form_players->isValid()){

            $em->persist($player);
            $em->flush();

            return $this->redirectToRoute('player_index');
        };

        return $this->render('player/update_player.html.twig', [
            'form_players'=>$form_players->createView()
        ]);
    }

    public function deletePlayer($id)
    {
        $em = $this->doctrine->getManager();

        $player = $em->getRepository(Player::class)->find($id);

        if (!$player) {
            throw $this->createNotFoundException('Player not found');
        }
        $em->remove($player);
        $em->flush();

        return $this->redirectToRoute('player_index');
    }


    public function randomPlayers(): Response
    {
        $em = $this->doctrine->getManager();


        $playerIds = $em->getRepository(Player::class)->createQueryBuilder('p')
            ->select('p.id')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();


        $minId = $playerIds[0]['id'];
        $maxId = end($playerIds)['id'];

        $randomPlayerIds = [];
        for ($i = 0; $i < 12; $i++) {
            $randomPlayerIds[] = mt_rand($minId, $maxId);
        }


        $randomPlayers = $em->getRepository(Player::class)->findBy(['id' => $randomPlayerIds]);


        $playersWithTeams = [];
        //now I need the players with their team, to validate the game against that
        foreach ($randomPlayers as $player) {
            $playersWithTeams[$player->getName()] = $player->getTeam()->getName();
        }

        return $this->render('game/game.html.twig', [
            'playerList'=>json_encode($playersWithTeams)
        ]);

    }


}
