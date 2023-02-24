<?php

namespace App\Http\Controllers;

use App\Mail\SendDocumentoNotify;
use App\Models\AsignaDocumento;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class DocumentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Documento::all();
        return view('documentos.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('documentos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'archivo' => ['required'],
            'firmado' => ['required'],
        ],
        [
            'archivo.required' => 'El campo Archivo es obligatorio',
            'firmado.required' => 'El campo Firmado es obligatorio',
        ]);

        $registro = new Documento();
        if ($request->hasFile('archivo')) {
            $uploadPath = public_path('/storage/DocumentosSubidos/');
            $file = $request->file('archivo');

            $name = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tamano = $file->getSize();

            $nameOriginalArchivo = $name;
            $uuid = Str::uuid(4);
            $fileName = $uuid . '-' . $name;
            $file->move($uploadPath, $fileName);
            $url = '/storage/DocumentosSubidos/'.$fileName;

            $registro->archivo = $nameOriginalArchivo;
            $registro->url_archivo = $url;
            $registro->tamano = $tamano;
        }
        $registro->firmado = $request->firmado;
        $registro->save();

        return redirect('documentos')->with('success', 'Registro Guardado exitósamente');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Documentos  $documentos
     * @return \Illuminate\Http\Response
     */
    public function firmar($id)
    {
        $count = Documento::where('id', $id)->count();
        if ($count>0) {
            $data = Documento::where('id', $id)->first();
            return view('documentos.firmar', compact('data'));
        } else {
            return redirect('documentos')->with('danger', 'Problemas para Mostrar el Registro.');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Documentos  $documentos
     * @return \Illuminate\Http\Response
     */
    public function completarFirma(Request $request, $id)
    {
        $request->validate([
            'firma' => ['required'],
        ],
        [
            'firma.required' => 'El campo Firma es obligatorio',
        ]);

        if ($request->posicion == 'Seleccione') {
            return redirect('documentos/'.$id.'/firmar-documento')->with('danger', 'Por favor indique la posición de la firma.');
        }

        // dd($request);
        // decodificamos la imagen de Nombre Completo
        $Base64Img = $request->firmaimg;
		list(, $Base64Img) = explode(';', $Base64Img);
		list(, $Base64Img) = explode(',', $Base64Img);
		$Base64Img = base64_decode($Base64Img);

        // decodificamos la imagen de las iniciales
        $Base64ImgIniciales = $request->firmainicialesimg;
		list(, $Base64ImgIniciales) = explode(';', $Base64ImgIniciales);
		list(, $Base64ImgIniciales) = explode(',', $Base64ImgIniciales);
		$Base64ImgIniciales = base64_decode($Base64ImgIniciales);

        // renombramos la imagen
        $uuid = Str::uuid(4);
        $fileName = $uuid . '- firma - ' . Auth::user()->name . '-' .date("Ymdhms") .'.png';
        $uploadPath = public_path('/storage/Firmas/');
        if (!file_exists($uploadPath)) {
            mkdir(public_path('/storage/Firmas'), 0777);
        }

        // renombramos la imagen
        $uuid = Str::uuid(4);
        $fileNameIniciales = $uuid . '- firma Iniciales- ' . Auth::user()->name . '-' .date("Ymdhms") .'.png';

        $urlArchivo = $uploadPath.$fileName;
        $urlArchivoIniciales = $uploadPath.$fileNameIniciales;
        // la guardamos el nombre
        file_put_contents($urlArchivo, $Base64Img);
        // la guardamos las iniciales
        file_put_contents($urlArchivoIniciales, $Base64ImgIniciales);

        $image_information = getimagesize($urlArchivo);
        $imageRotar = imagecreatefrompng($urlArchivoIniciales);
        $gradosIzq = 90;
        $gradosDer = 270;
        $rotar1 = imagerotate($imageRotar, $gradosIzq, 0);
        $fileNameInicialesIzq = $uuid . '- firma Iniciales Rotada Izq - ' . Auth::user()->name . '-' .date("Ymdhms") .'.png';
        $imgRotadaInicialesIzq = $uploadPath.$fileNameInicialesIzq;
        $imgRotada1 = imagepng($rotar1, $imgRotadaInicialesIzq);
        $imageInfo2 = getimagesize($imgRotadaInicialesIzq);
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
            if($n != $pagecount){
                $pdf->Image($imgRotadaInicialesIzq, 5, 125, $imageInfo2[0]/4);
                $pdf->Image($imgRotadaInicialesIzq, 195, 125, $imageInfo2[0]/4);
            }
		    if($n == $pagecount){
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
        $registro->firmado = 'Si';
        $registro->archivo_firmado = $urlFilename;
        $registro->save();

        return redirect('documentos')->with('success', 'Documento Firmado exitósamente');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Documentos  $documentos
     * @return \Illuminate\Http\Response
     */
    public function asignar($id)
    {
        $count = Documento::where('id', $id)->count();
        if ($count>0) {
            $data = Documento::where('id', $id)->first();
            $users = User::where('email', '!=', Auth::user()->email)->get();
            return view('documentos.asignar', compact('data', 'users'));
        } else {
            return redirect('documentos')->with('danger', 'Problemas para Mostrar el Registro.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Documentos  $documentos
     * @return \Illuminate\Http\Response
     */
    public function enviarNotificacion(Request $request, $id)
    {
            if ($request->registrado == 'Si') {
                $request->validate([
                    'users' => ['required', 'array'],
                ],
                [
                    'users.required' => 'El campo Usuarios es obligatorio',
                    'users.array' => 'El campo Usuarios es obligatorio',
                ]);

                require base_path("vendor/autoload.php");
                $mail = new PHPMailer(true);// Passing `true` enables exceptions

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
                    foreach($request->users as $key => $email){
                        $mail->addAddress($email);
                    }
                    $mail->Body = '<!DOCTYPE html><html style="font-family: sans-serif; line-height: 1.15; -webkit-text-size-adjust: 100%; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body style="margin: 0; font-family: "DM Sans", sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif; font-size: 0.875rem; font-weight: 400; line-height: 1.65; color: #526484; text-align: left; background-color: #f5f6fa;"> <table style="background: #f5f6fa; font-size: 14px; line-height: 22px; font-weight: 400; color: #8094ae; width: 100%;"> <tbody> <tr> <td style="padding-top: 2.75rem !important;"> <table style="width: 100%; max-width: 620px; margin: 0 auto;"> <tbody> <tr> <td style="text-align: center; padding-bottom:10px;"> <p style="font-size: 13px; color: #854fff; padding-top: 12px;"></p></td></tr></tbody> </table> <table style="width: 96%; max-width: 620px; margin: 0 auto; background: #ffffff;"> <tbody> <tr> <td style="padding-left: 1rem !important;padding-left: 2.75rem !important;padding-right: 1rem !important;padding-right: 2.75rem !important;padding-bottom: 1rem !important;padding-bottom: 2.75rem !important;"> <p style="text-align: justify; ">Buen día estimado(a).</p><p>Se le informa que tiene un documento por firmar.</p><p>Ingrese a la plataforma <strong>Firma de Documentos ALPASA</strong> para que pueda acceder a él, desde su panel de control, en caso de no estar registrado, le dejamos el enlace de acceso para su registro o inicio de sesion.</p><a href="https://firma-de-documentos.alpasamx.com/">Ingresar al Sistema</a> <p><strong>Nota:</strong> Si no está registrado, recomendamos hacerlo con el correo en el que recibió la información, para que al ingresar observe el documento asignado.</p></td></tr></tbody> </table> <table style="width: 100%; max-width: 620px; margin: 0 auto;"> <tbody> <tr> <td style="text-align: center; padding-top: 1.5rem !important;"> <p style="font-size: 13px;">Copyright © 2023 Firma de Documentos ALPASA. Todos los Derechos Reservados.</p></td></tr></tbody> </table> </td></tr></tbody> </table></body></html>';
                    if(!$mail->send()) {
                        return redirect('documentos')->with('error', 'Problemas con el servicio de Correos, por favor intente nuevamente.'.$e.'');
                    }
                } catch (Exception $e) {

                    return redirect('documentos')->with('error', 'Problemas con el servicio de Correos, por favor intente nuevamente.'.$e.'');
                }

                foreach ($request->users as $key => $email) {
                    $count = User::where('email', $email)->count();
                    if ($count > 0) {
                        $dato = User::where('email', $email)->first();
                        $registro = new AsignaDocumento;
                        $registro->user_id = $dato->id;
                        $registro->documento_id = $id;
                        $registro->name_user = $dato->name;
                        $registro->email_destinatario = $email;
                        $registro->notificado = 'Si';
                        $registro->firmado = 'No';
                        $registro->save();
                    }
                }
            }

            if ($request->registrado == 'No') {
                $request->validate([
                    'destinatarios' => ['required', 'array'],
                ],
                [
                    'destinatarios.required' => 'El campo Destinatarios es obligatorio',
                    'destinatarios.array' => 'El campo Destinatarios es obligatorio',
                ]);

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
                    // $mail->addAddress($receptores);
                    foreach($request->destinatarios as $key => $email){
                        $mail->addAddress($email);
                    }
                    $mail->Body = '<!DOCTYPE html><html style="font-family: sans-serif; line-height: 1.15; -webkit-text-size-adjust: 100%; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body style="margin: 0; font-family: "DM Sans", sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif; font-size: 0.875rem; font-weight: 400; line-height: 1.65; color: #526484; text-align: left; background-color: #f5f6fa;"> <table style="background: #f5f6fa; font-size: 14px; line-height: 22px; font-weight: 400; color: #8094ae; width: 100%;"> <tbody> <tr> <td style="padding-top: 2.75rem !important;"> <table style="width: 100%; max-width: 620px; margin: 0 auto;"> <tbody> <tr> <td style="text-align: center; padding-bottom:10px;"> <p style="font-size: 13px; color: #854fff; padding-top: 12px;"></p></td></tr></tbody> </table> <table style="width: 96%; max-width: 620px; margin: 0 auto; background: #ffffff;"> <tbody> <tr> <td style="padding-left: 1rem !important;padding-left: 2.75rem !important;padding-right: 1rem !important;padding-right: 2.75rem !important;padding-bottom: 1rem !important;padding-bottom: 2.75rem !important;"> <p style="text-align: justify; ">Buen día estimado(a).</p><p>Se le informa que tiene un documento por firmar.</p><p>Ingrese a la plataforma <strong>Firma de Documentos ALPASA</strong> para que pueda acceder a él, desde su panel de control, en caso de no estar registrado, le dejamos el enlace de acceso para su registro o inicio de sesion.</p><a href="https://firma-de-documentos.alpasamx.com/">Ingresar al Sistema</a> <p><strong>Nota:</strong> Si no está registrado, recomendamos hacerlo con el correo en el que recibió la información, para que al ingresar observe el documento asignado.</p></td></tr></tbody> </table> <table style="width: 100%; max-width: 620px; margin: 0 auto;"> <tbody> <tr> <td style="text-align: center; padding-top: 1.5rem !important;"> <p style="font-size: 13px;">Copyright © 2023 Firma de Documentos ALPASA. Todos los Derechos Reservados.</p></td></tr></tbody> </table> </td></tr></tbody> </table></body></html>';

                    if(!$mail->send()) {
                        return redirect('documentos')->with('error', 'Problemas con el servicio de Correos, por favor intente nuevamente.'.$e.'');
                    }
                } catch (Exception $e) {

                    return redirect('documentos')->with('error', 'Problemas con el servicio de Correos, por favor intente nuevamente.'.$e.'');
                }

                foreach ($request->destinatarios as $key => $email) {

                    $registro = new AsignaDocumento;
                    $registro->documento_id = $id;
                    $registro->email_destinatario = $email;
                    $registro->notificado = 'Si';
                    $registro->firmado = 'No';
                    $registro->save();
                }

            }

        return redirect('documentos')->with('success', 'Usuarios Asignados y Notificados exitósamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Documentos  $documentos
     * @return \Illuminate\Http\Response
     */
    public function eliminar($id)
    {
        $count = Documento::where('id', $id)->count();
        if ($count>0) {
            AsignaDocumento::where('documento_id', $id)->delete();
            Documento::where('id', $id)->delete();
            return redirect('documentos')->with('success', 'Documento eliminado exitósamente');
        } else {
            return redirect('documentos')->with('error', 'Problemas para encontrar el archivo.');
        }
    }
}
