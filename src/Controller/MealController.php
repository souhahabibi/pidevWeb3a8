<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\Ingredients;
use App\Entity\IngredientMeal;
use App\Entity\Reviewmeal;
use App\Form\ReviewmealType;
use App\Repository\ReviewmealRepository;
use App\Form\MealType;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IngredientsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\IngredientMealRepository; 
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/meal')]
class MealController extends AbstractController
{
    #[Route('/', name: 'app_meal_index', methods: ['GET','POST'])]
    public function index(Request $request,PaginatorInterface $paginator, MealRepository $mealRepository): Response
    {
        $meals = $mealRepository->findAll();
        $meals = $paginator->paginate(
            $meals, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
        5 /*limit per page*/
        );
        return $this->render('meal/index.html.twig', [
            'meals' => $meals,
        ]);
    }

    #[Route('/new', name: 'app_meal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
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
     //maiiil
     // Récupérer les détails du meal
$nomMeal = $meal->getName();
$recipeMeal = $meal->getRecipe();
$caloriesMeal= $meal->getCalories();

// Construire le corps de l'e-mail avec les informations du meal
$emailBody = "<p>Un nouveau meal a été ajouté :</p>";
$emailBody .= "<p><strong>Nom du repas :</strong> $nomMeal</p>";
$emailBody .= "<p><strong>Recipe :</strong> $recipeMeal</p>";
$emailBody .= "<p><strong>Calories :</strong> $caloriesMeal</p>";
            $email = (new Email())
            ->from('selmi_yosri@outlook.fr')
            ->to('yosri.selmi369@gmail.com') // Primary recipient
            ->subject('new meal ')
            ->html($emailBody );

            
            $mailer->send($email);
                
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

    #[Route('/client/menu/show', name: 'app_meal_client', methods: ['GET','POST'])]
    public function indexmealclient(Request $request, MealRepository $mealRepository,PaginatorInterface $paginator): Response
    {
        $meals = $mealRepository->findAll();
        $meals = $paginator->paginate(
            $meals, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
        4 /*limit per page*/
        );
      
        return $this->render('meal/menu.html.twig', [
            'meals' => $meals,
        ]);
    }

    #[Route('/client/ingredients/show/{id}', name: 'show_ingredients_client', methods: ['GET'])]
public function showClientIngredients($id, MealRepository $mealRepository, IngredientMealRepository $ingredientMealRepository): Response
{
    $meal = $mealRepository->find($id);

    if (!$meal) {
        throw $this->createNotFoundException('Meal not found');
    }

    // Fetch the ingredients associated with the meal
    $ingredientMeals = $ingredientMealRepository->findByMealId($id);

    return $this->render('ingredients/showingredientclient.html.twig', [
        'meal' => $meal,
        'ingredientMeals' => $ingredientMeals,
    ]);
}

    #[Route('/client/pdf/show/{id}', name: 'pdf')]
    public function pdfgenrator(MealRepository $mealRepository, $id, IngredientMealRepository $ingredientMealRepository): Response
    {
        
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $meal=$mealRepository->find($id);
        // Define the path to the image
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/image/' . $meal->getImageUrl();
        $ingredientBase64Images = [];

        $ingredientMeals = $ingredientMealRepository->findByMealId($id);
        $imageContent = file_get_contents($imagePath);
        $base64Image = base64_encode($imageContent);

        foreach ($ingredientMeals as $ingredientMeal) {
            $imageUrl = $ingredientMeal->getIngredient()->getImgUrl();
            $imagePathIngre = $this->getParameter('kernel.project_dir') . '/public/uploads/image/' . $imageUrl;

            if (file_exists($imagePath)) {
                // Read the image and encode it to base64
                $imageContentIng = file_get_contents($imagePathIngre);
                $base64ImageIng = base64_encode($imageContentIng);

                // Store base64 data along with the ingredient
                $ingredientBase64Images[] = [
                    'ingredient' => $ingredientMeal,
                    'base64Image' => $base64ImageIng,
                ];
            } else {
                throw new FileNotFoundException("File not found: " . $imagePathIngre);
            }
        }

        
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('meal/pdf.html.twig', [
            'meal' => $meal,
            'base64Image' => $base64Image,
            'ingredientBase64Images' => $ingredientBase64Images,
            
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A3', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        return new Response (
            $dompdf->stream('mealPdf', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
        // Output the generated PDF to Browser (force download)
      
        
    }

} 
    

    

