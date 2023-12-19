<?php

namespace App\Http\Controllers\pages;
use App\Http\Controllers\Controller;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Http\Request;

class Page2 extends Controller
{

  public function index()
  {
    return view('content.pages.pages-page2');
  }

  public function f_generateExcel(Request $oRequest)
  {
    $oWriter = WriterEntityFactory::createXLSXWriter();
    $tempFile = tempnam(sys_get_temp_dir(), 'spout_');
    $oWriter->openToFile($tempFile);

    $cells = [
      WriterEntityFactory::createCell('Nombre'),
      WriterEntityFactory::createCell('Email'),
      WriterEntityFactory::createCell('Password'),
    ];

    $singleRow = WriterEntityFactory::createRow($cells);
    $oWriter->addRow($singleRow);
    $oValues = [$oRequest->input('nameUser'), $oRequest->input('emailUser'), $this->f_maskPassword($oRequest->input('passUser'))];
    $rowFromValues = WriterEntityFactory::createRowFromArray($oValues);
    $oWriter->addRow($rowFromValues);

    $oWriter->close();

    $oFileContent = file_get_contents($tempFile);

    return response($oFileContent, 200, [
      'Content-Type' => 'application/octet-stream',
      'Content-Disposition' => 'attachment; filename="archivo.xlsx"',
    ]);
  }

  // ? Retorna la contraseña mostranso solo los ultimos 3 caracteres
  public function f_maskPassword($cPassword){
    $nLength = strlen($cPassword);
    return str_repeat('*', $nLength - 3) . substr($cPassword, -3);
  }
}
