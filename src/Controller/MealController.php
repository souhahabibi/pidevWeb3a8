<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\Ingredients;
use App\Entity\IngredientMeal;
use App\Form\MealType;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IngredientsRepository;

#[Route('/meal')]
class MealController extends AbstractController
{
    #[Route('/', name: 'app_meal_index', methods: ['GET','POST'])]
    public function index(MealRepository $mealRepository): Response
    {
        return $this->render('meal/index.html.twig', [
            'meals' => $mealRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_meal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $meal = new Meal();
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageUrl')->getData();


            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $filename);
            $meal->setImageUrl($filename);

            $entityManager->persist($meal);
            $entityManager->flush();

            return $this->redirectToRoute('app_meal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('meal/new.html.twig', [
            'meal' => $meal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_meal_show', methods: ['GET'])]
    public function show(Meal $meal): Response
    {
        return $this->render('meal/show.html.twig', [
            'meal' => $meal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_meal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Meal $meal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_meal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('meal/edit.html.twig', [
            'meal' => $meal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_meal_delete', methods: ['POST'])]
    public function delete(Request $request, Meal $meal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$meal->getId(), $request->request->get('_token'))) {
            $entityManager->remove($meal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_meal_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/ingredients/{id}', name: 'app_meal_show_ingredient_ingredient', methods: ['GET'])]
    public function showIngredients($id, IngredientsRepository $ingredientsRepository,EntityManagerInterface $entityManager): Response
    {
        $meal = $entityManager->getRepository(Meal::class)->find($id);
        $Addedingredients =  $entityManager->getRepository(IngredientMeal::class)->findByMealId($id);
        
        return $this->render('meal/showIngredients.html.twig', [
            'meal' => $meal,
            'ingredients' => $ingredientsRepository->findAll(),
            'addedIng' => $Addedingredients
        ]);
    }

    #[Route('/ingredients/add/{id}/{ingredintId}', name: 'app_meal_add_ingredient_ingredient')]
    public function addIngredientsToMeal(Request $request,$id, $ingredintId ,IngredientsRepository $ingredientsRepository,EntityManagerInterface $entityManager): Response
    {
        $Addedingredients =  $entityManager->getRepository(IngredientMeal::class)->findByMealId($id);
        $ingredients = $ingredientsRepository->findAll();
        $meal = $entityManager->getRepository(Meal::class)->find($id);
        $ingredientDetail = $entityManager->getRepository(Ingredients::class)->find($ingredintId);
        $existingIngredient = $entityManager->getRepository(IngredientMeal::class)->findByMealAndIngredient($id, $ingredintId);

        if (!empty($Addedingredients)) {
            foreach ($Addedingredients as $Addingredient) {
                // Check if the current ingredient matches the ingredient to be added
                if ($existingIngredient) {
                    // If the ingredient already exists for the meal, remove it and redirect
                    $entityManager->remove($existingIngredient);
                 $entityManager->flush();
                    return $this->redirectToRoute('app_meal_show_ingredient_ingredient', ['id' => $id], Response::HTTP_SEE_OTHER);
                }
            }
        }
    
        // If no ingredients are added yet or the ingredient to be added is not found, add it
        $ingredientToAdd = new IngredientMeal();
        $ingredientToAdd->setMeal($meal);
        $ingredientToAdd->setIngredients($ingredientDetail);
        $entityManager->persist($ingredientToAdd);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_meal_show_ingredient_ingredient', ['id' => $id], Response::HTTP_SEE_OTHER);
    }
}
