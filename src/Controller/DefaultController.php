<?php

namespace App\Controller;

use App\Entity\TodoLists;
use App\Entity\Tasks;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends AbstractController
{
    private $managerRegistry;
    private $serializer;
    private $entityManager;
    private $todoListsRepository;
    private $tasksRepository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $this->entityManager = $this->managerRegistry->getManager();
        $this->todoListsRepository = $this->entityManager->getRepository(TodoLists::class);
        $this->tasksRepository = $this->entityManager->getRepository(Tasks::class);
    }

    // Gestion de la route / : todo-lists
    public function todos(Request $request) : Response
    {
        // Rendu de la page en fonction des datas dans la BdD
        if ($request->isMethod("GET"))
        {
            $items = $this->todoListsRepository->findAll();
            $items = $this->serializer->serialize($items, "json");
            return $this->render("todos.html.twig", ["items" => $items, "listName" => null]);
        }

        // Mise à jour BdD
        if ($request->isMethod("POST"))
        {
            $data = $request->request->all();

            // Création d'une nouvelle todo-list en BdD
            if (isset($data["name"]) && $data["name"] != "")
            {
                $item = new TodoLists;
                $item->setName($data["name"]);
                $item->setNbOfTasks(0);
                $item->setNbOfValidatedTasks(0);
                $this->entityManager->persist($item);
                $this->entityManager->flush();
            }

            // Suppression d'une todo-list en BdD
            if (isset($data["delete"]))
            {
                $item = $this->todoListsRepository->findOneBy(["id" => $data["delete"]]);
                $items = $this->tasksRepository->findBy(["parentId" => $data["delete"]]);
                $this->entityManager->remove($item);
                foreach ($items as $item)
                    $this->entityManager->remove($item);
                $this->entityManager->flush();
            }
        }
        return $this->redirect("/");
    }

    // Gestion de la route /tasks/{id} : todo-list
    public function tasks(Request $request, $id) : Response
    {
        // Rendu de la page en fonction des datas dans la BdD
        if ($request->isMethod("GET"))
        {
            // Erreur 404 si il n'y a pas de todo-list pour cet id
            $item = $this->todoListsRepository->findOneBy(["id" => $id]);
            if ($item == null)
                throw $this->createNotFoundException("Err404 : pas de liste définie pour cette adresse");
            $listName = $item->getName();
            $items = $this->tasksRepository->findBy(["parentId" => $id]);
            $listName = $this->serializer->serialize($listName, "json");
            $items = $this->serializer->serialize($items, "json");
            return $this->render("tasks.html.twig", ["items" => $items, "listName" => $listName]);
        }

        // Mise à jour BdD
        if ($request->isMethod("POST")) {
            $data = $request->request->all();

            // Création d'une nouvelle tâche
            if (isset($data["name"]) && !isset($data["modify"]))
            {
                if ($data["name"] != "")
                {
                    $item = new Tasks;
                    $item->setParentId($id);
                    $item->setName($data["name"]);
                    $item->setChecked(false);
                    $this->entityManager->persist($item);
                    $updateItem = $this->todoListsRepository->findOneBy(["id" => $id]);
                    $updateItem->setNbOfTasks($updateItem->getNbOfTasks() + 1);
                    $this->entityManager->flush();
                }
            }

            // Suppression d'une tâche
            if (isset($data["delete"]))
            {
                $item = $this->tasksRepository->findOneBy(["id" => $data["delete"]]);
                $this->entityManager->remove($item);
                $updateItem = $this->todoListsRepository->findOneBy(["id" => $id]);
                $updateItem->setNbOfTasks($updateItem->getNbOfTasks() - 1);
                if ($item->getChecked())
                    $updateItem->setNbOfValidatedTasks($updateItem->getNbOfValidatedTasks() - 1);
                $this->entityManager->flush();
            }

            // Modification d'une tâche
            if (isset($data["name"]) && isset($data["modify"]))
            {
                if ($data["name"] != "")
                {
                    $updateItem = $this->tasksRepository->findOneBy(["id" => $data["modify"]]);
                    $updateItem->setName($data["name"]);
                    $this->entityManager->flush();
                }
            }

            // Cocher, décocher une tâche
            if (isset($data["check"]))
            {
                $updateItem = $this->todoListsRepository->findOneBy(["id" => $id]);
                $checkItem = $this->tasksRepository->findOneBy(["id" => $data["check"]]);
                if ($checkItem->getChecked() == false)
                {
                    $checkItem->setChecked(true);
                    $updateItem->setNbOfValidatedTasks($updateItem->getNbOfValidatedTasks() + 1);
                }
                else
                {
                    $checkItem->setChecked(false);
                    $updateItem->setNbOfValidatedTasks($updateItem->getNbOfValidatedTasks() - 1);
                }
                $this->entityManager->flush();
            }
        }

        return $this->redirect("/tasks/$id");
    }
}