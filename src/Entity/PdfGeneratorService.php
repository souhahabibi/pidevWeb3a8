<?php
// src/Service/PdfGeneratorService.php
namespace App\Entity;

use TCPDF;

class PdfGeneratorService
{
    public function generatePdf($html)
    {
        // Initialise un nouvel objet TCPDF pour la génération du PDF
        $pdf = new TCPDF();
        // Ajoute une nouvelle page au PDF
        $pdf->AddPage();
        // Add logo to the PDF
       $image_file = 'public/clientSalle/img/logo.png';
       //image,Position horizontale,Position vertical, Largeur,hauteur,type,URL ...
      $pdf->Image($image_file, 10, 10, '', '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
       // Écrit le HTML dans le PDF en respectant les paramètres spécifiés.
        $pdf->writeHTML($html, true, false, true, false, '');
        //Renvoie le PDF généré sous forme de chaîne binaire
        //'S' signifie que la méthode renvoie le contenu du PDF en tant que chaîne binaire
        return $pdf->Output('document.pdf', 'S');
    }
}
?>