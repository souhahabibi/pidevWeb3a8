<?php

namespace App\Controller;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Doctrine\Persistence\ManagerRegistry;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;





class QrCodeController extends AbstractController

{
    //GET  Utilisé pour récupérer des données
    #[Route('/qr-codes/{id}', name: 'app_qr_codes', methods: ['GET'])]
public function index(int $id,UrlGeneratorInterface $urlGenerator): Response
{
    //nouvelle instance de PngWriter qui est utilisée pour générer des images PNG.
    $writer = new PngWriter();
    
    //  récupère un objet en fonction de l'$id fourni
    $produit = $this->getDoctrine()->getRepository(produit::class)->find($id);

    // Create the QR code 
    $qrCode = QrCode::create(
        //sprintf() pour formater une chaîne de caractères avec des valeurs dynamiques
        sprintf(
            "ID: %d\nNom: %s\nDate: %s\nQuantite: %s\nPrix:  %s",
            $produit->getIdProduit(),
            $produit->getNom(),
            $produit->getDateExpiration()->format('Y-m-d H:i:s'),
            $produit->getQuantite(),
            $produit->getCout(),
            
        )
        //encodage du texte utilisé dans le code QR
    )->setEncoding(new Encoding('UTF-8'))
    // ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
     ->setSize(120)
     ->setMargin(0)
     ->setForegroundColor(new Color(0, 0, 0)) //noir
     ->setBackgroundColor(new Color(255, 255, 255)); //blanc
    
    // Add logo and label to the QR code 
    //image qui est utilisé comme logo dans le code QR
    $logo = Logo::create('img.png')->setResizeToWidth(60); 
    //Le label  pour ajouter du texte supplémentaire au code QR généré
    $label = Label::create('')->setFont(new NotoSans(8));
 
    // Generate QR codes with different styles
    // Initialise un tableau pour stocker les différents codes QR générés
    $qrCodes = [];
    // Génère code QR avec un logo et stocke l'URI des données dans le tableau avec la clé 'img'
    $qrCodes['img'] = $writer->write($qrCode, $logo)->getDataUri();
    // Génère code QR simple avec un label 'Simple' et stocke l'URI des données dans le tableau 
    $qrCodes['simple'] = $writer->write($qrCode, null, $label->setText('Simple'))->getDataUri();
    //apparence visuelle du code QR
    //premier plan du code QR correspond aux modules qui codent les données réelles, et changer sa couleur peut influencer l'apparence visuelle du code QR.
    $qrCode->setForegroundColor(new Color(255, 0, 0));
    //génère un code QR avec un label "Color Change" et stocke l'URI des données de ce code QR dans le tableau
    $qrCodes['changeColor'] = $writer->write($qrCode, null, $label->setText('Color Change'))->getDataUri();
    //background
    $qrCode->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 0, 0));
    $qrCodes['changeBgColor'] = $writer->write($qrCode, null, $label->setText('Background Color Change'))->getDataUri();
    //taille
    $qrCode->setSize(200)->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 255, 255));
    $qrCodes['withImage'] = $writer->write($qrCode, $logo, $label->setText('With Image')->setFont(new NotoSans(20)))->getDataUri();
    // $reservationUrl = $urlGenerator->generate('app_reservation_new', ['id' => $produit->getId()]);
    return $this->render('qr_code/index.html.twig', [
        'qrCodes' => $qrCodes,
    ]);
}

}