<?php

namespace App\Http\Controllers;

use App\Models\AsignaDocumento;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;

class FirmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = AsignaDocumento::where('user_id', Auth::user()->id)
                                ->orWhere('email_destinatario', Auth::user()->email)->get();
        return view('documentosOperador.index', compact('data'));
    }


    public function firmar($id)
    {
        $count = Documento::where('id', $id)->count();
        if ($count>0) {
            $data = Documento::where('id', $id)->first();
            return view('documentosOperador.firmar', compact('data'));
        } else {
            return redirect('/mis-documentos')->with('danger', 'Problemas para Mostrar el Registro.');
        }
    }

    public function completarFirma(Request $request, $id)
    {
        $request->validate([
            'firma' => ['required'],
        ],
        [
            'firma.required' => 'El campo Firma es obligatorio',
        ]);

        if ($request->posicion == 'Seleccione') {
            return redirect('/mis-documentos/'.$id.'/firmar-documento')->with('danger', 'Por favor indique la posición de la firma.');
        }

        // decodificamos la imagen
        $Base64Img = $request->firmaimg;
		list(, $Base64Img) = explode(';', $Base64Img);
		list(, $Base64Img) = explode(',', $Base64Img);
		$Base64Img = base64_decode($Base64Img);

        // renombramos la imagen
        $uuid = Str::uuid(4);
        $fileName = $uuid . '- firma - ' . Auth::user()->name . '-' .date("Ymdhms") .'.png';
        $uploadPath = public_path('/storage/Firmas/');
        $urlArchivo = $uploadPath.$fileName;
        // la guardamos
        file_put_contents($urlArchivo, $Base64Img);

        $image_information = getimagesize($urlArchivo);
        // Ahora firmamos el pdf
        $urlFile = public_path().$request->urlArchivo;

        if ($request->posicion == 'ESI') { $y = 5;$x = 5;}
        if ($request->posicion == 'CS') { $y = 5;$x = 100;}
        if ($request->posicion == 'ESD') { $y = 5;$x = 125;}
        if ($request->posicion == 'CIM') { $y = 120;$x = 5;}
        if ($request->posicion == 'C') { $y = 120;$x = 100;}
        if ($request->posicion == 'CD') { $y = 120;$x = 125;}
        if ($request->posicion == 'EII') { $y = 250;$x = 5;}
        if ($request->posicion == 'CI') { $y = 250;$x = 100;}
        if ($request->posicion == 'EID') { $y = 250;$x = 125;}


        $pdf = new Fpdi();
		$pagecount = $pdf->setSourceFile($urlFile);// cuenta la cantidad de paginas
		$w = $pdf->GetPageWidth(); //obtengo el ancho y alto de hoja
		$h = $pdf->GetPageHeight();
		// iteramos las paginas del documento pdf
        for ($n = 1; $n <= $pagecount; $n++) {
			// COnfiguramos de antemano el tipo de letra y tamaño
			$pdf->SetFont('Helvetica');
			$pdf->SetFontSize('8'); // set font size
			// añadimos una pagina del mismo documento al sumar 1
			$pdf->AddPage();
			// importa la pagina en la posicion actual al pdf para no perder la informacion
			// del mismo
		    $tplId = $pdf->importPage($n);
		    // le indicamos que use la template
		    $pdf->useTemplate($tplId);
			// validamos que la variable del for $n
			// sea igual a la ultima pagina del documento pdf
            // $pdf->SetXY(50,$h-100);
		    if($n == $pagecount){

		    	// $pdf->SetXY(5,$h-250-($image_information[1]));//esquina inferior izquierda
		    	$pdf->Image($urlArchivo, $x, $y, $image_information[0]/4);
		    }
		}
        // guardo el pdf firmado en la siguiente url
        $uploadPath = public_path('/storage/DocumentosFirmados/');
        $urlArchivo = $uploadPath.'firmado-'.date("Ymdhms").'-'.$request->nameArchivo;
        $urlFilename = '/storage/DocumentosFirmados/'.'firmado-'.date("Ymdhms").'-'.$request->nameArchivo;
		$pdf->Output('F', $urlArchivo);

        $registro = Documento::where('id', $id)->first();
        $registro->archivo_firmado = $urlFilename;
        $registro->save();

        $registro = AsignaDocumento::where('documento_id', $id)->first();
        $registro->firmado = 'Si';
        $registro->save();

        return redirect('mis-documentos')->with('success', 'Documento Firmado exitósamente');
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
