<?php

/*copyright@2018 Radical Enlighten Co.,ltd.*/

namespace App\Models;

use Illuminate\Support\Facades\DB;

use TCPDF;

class PdfGenerator extends TCPDF
{

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    /* public function __construct( UnionMembership $union_membership )
    {
		$this->union_membership = $union_membership;
    } */

    public function generatePdf($content)
    {
        // Set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Your Name');
        $this->SetTitle('Sample PDF');
        $this->SetSubject('Sample PDF using TCPDF');
        $this->SetKeywords('TCPDF, PDF, example, test, guide');

        // Add a page
        $this->AddPage();

        // Set font
        $this->SetFont('helvetica', '', 12);

        // Add content
        $this->MultiCell(0, 10, $content, 0, 'L');

        // Output the PDF
        $this->Output('sample.pdf', 'I');
    }

    public function SaveSinglePdf( $file , $filename )
    {
        // Set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('VasDocs');
        $this->SetTitle('VasDocs PDF');
        //$this->SetSubject('Sample PDF using TCPDF');
        //$this->SetKeywords('TCPDF, PDF, example, test, guide');

        // Add a page
        $this->AddPage();

        // Define the image file path
        $imageFile = $file;

        // Read the EXIF data of the image
        $exif = exif_read_data($imageFile);
        //echo '<pre>';print_r($exif);die();

        // Check the orientation tag in the EXIF data
        if (isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];

            // Create an image resource from the original image
            $image = imagecreatefromjpeg($imageFile);

            // Apply a rotation transformation based on the orientation tag
            switch ($orientation) {
                case 3:
                    $image = imagerotate($image, 180, 0);
                    break;
                case 6:
                    $image = imagerotate($image, -90, 0);
                    break;
                case 8:
                    $image = imagerotate($image, 90, 0);
                    break;
            }

            // Save the rotated image back to the original file path
            imagejpeg($image, $imageFile, 100);

            // Free up the image resource
            imagedestroy($image);
        }

        // Determine image dimensions
        list($width, $height) = getimagesize($imageFile);

        // Calculate the width and height for the PDF page (adjust as needed)
        /* $pdfWidth = 210; // Width in millimeters (A4 size)
        $pdfHeight = ($pdfWidth * $height) / $width; // Maintain aspect ratio

        // Embed the image into the PDF
        $this->Image($imageFile, 10, 10, $pdfWidth, $pdfHeight); */

        // Calculate the width and height for the PDF page (adjust as needed)
        $margin = 10; // You can change this value

        // Calculate the width and height for the PDF page (adjust as needed)
        $pdfWidth = 210; // Width in millimeters (A4 size)
        $pdfHeight = ($pdfWidth * $height) / $width; // Maintain aspect ratio

        // Calculate the X and Y positions to center the image with margins
        $x = ($pdfWidth - ($pdfWidth - 2 * $margin)) / 2; // Center horizontally with margins
        $y = ($pdfHeight - ($pdfHeight - 2 * $margin)) / 2; // Center vertically with margins

        // Embed the image into the PDF with the calculated positions
        $this->Image($imageFile,  $margin,  $margin, $pdfWidth - 2 * $margin, $pdfHeight - 2 * $margin);

        // Define the file path where the PDF will be saved
        $pdfFilePath = public_path('documents').'/'.$filename;

        // Output the PDF to the specified file path
        $this->Output($pdfFilePath, 'F');

    }

    
}
