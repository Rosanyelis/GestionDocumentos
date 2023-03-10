<?php

namespace App\Http\Controllers;

use App\Mail\SendNotifyAsignado;
use App\Models\AsignaDocumento;
use App\Models\Documento;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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


        $archivo = $request->nameArchivo;
        // Envío de correo
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true); // Passing `true` enables exceptions
        try {
            // Email server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp-mail.outlook.com';//  smtp host
            $mail->SMTPAuth = true;
            $mail->Username = 'soporteweb@alpasa.com.mx';//  sender username
            $mail->Password = 'Tat16037';// sender password
            $mail->SMTPSecure = 'tls';// encryption - ssl/tls
            $mail->Port = 587;
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('soporteweb@alpasa.com.mx', 'ALPASA');
            $mail->Subject = 'Firma de Documentos ALPASA - Documento Firmado';
            $mail->addAddress('soporteweb@alpasa.com.mx');
            $mail->Body = '<!DOCTYPE html><html style="font-family: sans-serif; line-height: 1.15; -webkit-text-size-adjust: 100%; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body style="margin: 0; font-family: "DM Sans", sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif; font-size: 0.875rem; font-weight: 400; line-height: 1.65; color: #526484; text-align: left; background-color: #f5f6fa;"> <table style="background: #f5f6fa; font-size: 14px; line-height: 22px; font-weight: 400; color: #8094ae; width: 100%;"> <tbody> <tr> <td style="padding-top: 2.75rem !important;"> <table style="width: 100%; max-width: 620px; margin: 0 auto;"> <tbody> <tr> <td style="text-align: center; padding-bottom:10px;"> <p style="font-size: 13px; color: #854fff; padding-top: 12px;"></p></td></tr></tbody> </table> <table style="width: 96%; max-width: 620px; margin: 0 auto; background: #ffffff;"> <tbody> <tr> <td style="padding-left: 1rem !important;padding-left: 2.75rem !important;padding-right: 1rem !important;padding-right: 2.75rem !important;padding-bottom: 1rem !important;padding-bottom: 2.75rem !important;"> <p style="text-align: justify; ">Buen día estimado(a).</p><p>Se notifica que el documento <strong>'.$archivo.'</strong> fué firmado por uno de los usuarios asignados.</p></td></tr></tbody> </table> <table style="width: 100%; max-width: 620px; margin: 0 auto;"> <tbody> <tr> <td style="text-align: center; padding-top: 1.5rem !important;"> <p style="font-size: 13px;">Copyright © 2023 Firma de Documentos ALPASA. Todos los Derechos Reservados.</p></td></tr></tbody> </table> </td></tr></tbody> </table></body></html>';
            if(!$mail->send()) {
                return redirect('mis-documentos')->with('error', 'Problemas con el servicio de Correos, por favor intente nuevamente.'.$e.'');
            }
        } catch (Exception $e) {

            return redirect('mis-documentos')->with('error', 'Problemas con el servicio de Correos, por favor intente nuevamente.'.$e.'');
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
        if (!file_exists($uploadPath)) {
            mkdir(public_path('/storage/Firmas'), 0777);
        }
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
        if (!file_exists($uploadPath)) {
            mkdir(public_path('storage/DocumentosFirmados'), 0777);
        }
        $urlArchivo = $uploadPath.'firmado-'.date("Ymdhms").'-'.$request->nameArchivo;
        $urlFilename = '/storage/DocumentosFirmados/'.'firmado-'.date("Ymdhms").'-'.$request->nameArchivo;
		$pdf->Output('F', $urlArchivo);



        $registro = Documento::where('id', $id)->first();
        $registro->archivo_firmado = $urlFilename;
        $registro->save();

        $registro = AsignaDocumento::where('documento_id', $id)->first();
        $registro->firmado = 'Si';
        $registro->save();

        $registro = new Notificacion;
        $registro->user_id = Auth::user()->id;
        $registro->descripcion = 'Documento ***'.$request->nameArchivo. '*** fué firmado por el usuario: ' .Auth::user()->name. ' exitósamente';
        $registro->estado = 1;
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
