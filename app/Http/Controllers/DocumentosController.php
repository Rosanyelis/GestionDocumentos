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

                // Envío de correo
                $mailable = new SendDocumentoNotify();
                try {
                    Mail::to($request->users)->send($mailable);
                } catch (\Throwable $th) {
                    return redirect('documentos')->with('error', 'Problemas con el servicio de Correos, por favor intente nuevamente');
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
                $mailable = new SendDocumentoNotify();
                try {
                    Mail::to($request->destinatarios)->send($mailable);
                } catch (\Throwable $th) {
                    return redirect('documentos')->with('error', 'Problemas con el servicio de Correos, por favor intente nuevamente');
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
