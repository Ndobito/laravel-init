<?php

namespace App\Http\Controllers\pages;

use FPDF;
use Illuminate\Http\Request;

class Page3 extends FPDF
{

  public function index()
  {
    return view('content.pages.pages-page3');
  }

  // ! Recibe parametros por el GET
  public function f_generatePdf(Request $oRequest)
  {
    $cNameUser = $oRequest->input('nameUser');
    $cEmailUser = $oRequest->input('emailUser');
    $cPassUser = $this->f_MaskPassword($oRequest->input('passUser'));

    $this->SetAutoPageBreak(true, 10);
    $this->SetFont('Arial', '', 12);
    $this->SetMargins(10, 10, 10);
    $this->AddPage();

    $this->SetFont('Arial', 'B', 16);
    $this->Cell(0 , 10, 'Registro de Datos Personales', 1, 0, 'C');
    $this->Ln(20);

    $this->SetFont('Arial', '', 12);
    $this->f_CellWithLabel('Nombre', $this->f_VerifyExist($cNameUser));
    $this->f_CellWithLabel('Email', $this->f_VerifyExist($cEmailUser));
    $this->f_CellWithLabel('Contraseña', $this->f_MaskPassword($cPassUser));  // Considera aplicar la máscara a la contraseña

    $cFilename = 'registro_datos_personales.pdf';
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $cFilename . '"');

    $this->Output('D'); // 'D' descarga directa

    exit;
  }

  // ? Simplicacion de creacion de celdas
  public function f_CellWithLabel($cLabel, $cValue){
    $this->Cell(40, 10, $cLabel . ':', 0, 0);
    $this->Cell(0, 10, $cValue, 0, 1);
  }

  // ? Retorna el valor si este contiene uno, sino restona valor por defecto
  public function f_VerifyExist($cValue, $cDefault = 'No especificado'){
    return empty($cValue) ? $cDefault : $cValue;
  }

  // ? Retorna la contraseña mostranso solo los ultimos 3 caracteres
  public function f_MaskPassword($cPassword){
    $nLength = strlen($cPassword);
    return str_repeat('*', $nLength - 3) . substr($cPassword, -3);
  }
}
