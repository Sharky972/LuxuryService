<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Candidates;
use App\Entity\JobCategory;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CandidatesController extends AbstractController
{
    #[Route('/candidates', name: 'app_candidates_save')]
    public function save(Request $request, EntityManagerInterface $entityManager, Security $security, UserRepository $userRepository): Response
    {
        $user = $security->getUser();

        // Get the existing candidate for the user, if it exists
        $candidate = $userRepository->findOneBy(['user' => $user]);

        // If the candidate doesn't exist, create a new one
        if (!$candidate) {
            $candidates = new Candidates();
        }


        $data = $request->request->all();
        $jobcategory = $entityManager->getRepository(JobCategory::class)->find($data["job_sector"]);




        $candidates->setFirstname($data["first_name"])
            ->setLastname($data["last_name"])
            ->setCurrentLocation($data["current_location"])
            ->setAddress($data["address"])
            ->setCountry($data["country"])
            ->setNationality($data["nationality"])
            ->setBirthLocation($data["birth_place"])
            ->setExperience($data["experience"])
            ->setShortDescription($data["description"])
            ->setGender($data["gender"])
            ->setUser($user)
            ->setJobCategory($jobcategory);
        // ->setBirthdate($data["birth_date"]);

        $entityManager->persist($candidates);
        $entityManager->flush();

        return $this->redirectToRoute('app_profile');
    }
}
