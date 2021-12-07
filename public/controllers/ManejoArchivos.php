<?php
require_once './models/Changelog.php';
require_once './controllers/ChangelogController.php';
require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './models/Encuesta.php';
require_once './models/Producto.php';
require_once './models/Usuario.php';
use Fpdf\Fpdf;

class ManejoArchivos
{
    public function DescargaUsuarios($request, $response, $args)
    {
        $dato = Usuario::obtenerTodos();
        if ($dato)
        {
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Helvetica', 'B', 25);
            $pdf->Cell(160, 30, 'Usuarios Comanda', 1, 3, 'B');
            $pdf->Image('archivos/logo.jpg', 140, 14, 22);
            $pdf->SetDrawColor(80, 66, 31);
            $pdf->SetFillColor(231, 204, 137);
            $pdf->Ln(2);
            
            $header = array('ID', 'NOMBRE', 'APELLIDO', 'MAIL', 'PUESTO', 'ESTADO', 'IDPUESTO', 'IDESTADO');
            $pdf->SetFont('Helvetica', 'B', 8);
            $w = array(10, 20, 20, 40, 20, 20, 20, 20);
            for ($i = 0; $i < count($header); $i++)
            {
                $pdf->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
            }
            $pdf->Ln();

            $fill = false;

            foreach ($dato as $v)
            {
                $pdf->Cell($w[0], 8, $v->idUser, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[1], 8, $v->nombre, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[2], 8, $v->apellido, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[3], 8, $v->mail, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[4], 8, $v->puesto, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[5], 8, $v->estado, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[6], 8, $v->idPuesto, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[7], 8, $v->idEstado, 'LR', 0, 'C', $fill);
                $pdf->Ln();
                $fill = !$fill;
            }
            $pdf->Cell(array_sum($w), 0, '', 'T');

            $pdf->Output('F', './archivos/' . 'usuarios' .'.pdf', 'I');
            $payload = json_encode(array("mensaje" => 'archivo generado ./archivos/' . 'usuarios' .'.pdf'));
            ChangelogController::CargarUno("pdf",0,0,"Guardar Usuarios","Se descargaron datos de usuarios en pdf");
        }
        else
        {
            $payload = json_encode(array("error" => 'Producto no encontrado'));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function DescargaProductos($request, $response, $args)
    {
        $dato = Producto::obtenerTodos();
        if ($dato)
        {
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Helvetica', 'B', 25);
            $pdf->Cell(160, 30, 'Productos Comanda', 1, 3, 'B');
            $pdf->Image('archivos/logo.jpg', 140, 14, 22);
            $pdf->SetDrawColor(44, 126, 56);
            $pdf->SetFillColor(122, 221, 137);
            $pdf->Ln(2);
            
            $header = array('ID', 'NOMBRE', 'PRECIO', 'TIPO', 'PERFIL', 'IDPUESTO', 'PUESTO');
            $pdf->SetFont('Helvetica', 'B', 8);
            $w = array(10, 40, 20, 30, 20, 20, 30);
            for ($i = 0; $i < count($header); $i++)
            {
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
            }
            $pdf->Ln();

            $fill = false;

            foreach ($dato as $v)
            {
                $pdf->Cell($w[0], 7, $v->idProduc, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[1], 7, $v->nombre, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[2], 7, $v->precio, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[3], 7, $v->tipo, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[4], 7, $v->perfilEmpleado, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[5], 7, $v->idPuesto, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[6], 7, $v->puesto, 'LR', 0, 'C', $fill);
                $pdf->Ln();
                $fill = !$fill;
            }
            $pdf->Cell(array_sum($w), 0, '', 'T');

            $pdf->Output('F', './archivos/' . 'productos' .'.pdf', 'I');
            $payload = json_encode(array("mensaje" => 'archivo generado ./archivos/' . 'productos' .'.pdf'));
            ChangelogController::CargarUno("pdf",0,0,"Guardar Productos","Se descargaron datos de productos en pdf");
        }
        else
        {
            $payload = json_encode(array("error" => 'Producto no encontrado'));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function DescargaEncuesta($request, $response, $args)
    {
        $dato = Encuesta::obtenerTodos();
        if ($dato)
        {
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Helvetica', 'B', 25);
            $pdf->Cell(160, 30, 'Encuesta Comanda', 1, 3, 'B');
            $pdf->Image('archivos/logo.jpg', 140, 14, 22);
            $pdf->SetDrawColor(33, 55, 97);
            $pdf->SetFillColor(158, 187, 242);
            $pdf->Ln(2);
            
            $header = array('ID', 'CODIGO', 'MESA', 'RESTAURANTE', 'MOZO', 'COCINERO', 'EXPERIENCIA');
            $pdf->SetFont('Helvetica', 'B', 8);
            $w = array(10, 30, 10, 30, 10, 20, 60);
            for ($i = 0; $i < count($header); $i++)
            {
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
            }
            $pdf->Ln();

            $fill = false;

            foreach ($dato as $v)
            {
                $pdf->Cell($w[0], 7, $v->id, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[1], 7, $v->codigo, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[2], 7, $v->mesa, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[3], 7, $v->restaurante, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[4], 7, $v->mozo, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[5], 7, $v->cocinero, 'LR', 0, 'C', $fill);
                $pdf->Cell($w[6], 7, $v->experiencia, 'LR', 0, 'C', $fill);
                $pdf->Ln();
                $fill = !$fill;
            }
            $pdf->Cell(array_sum($w), 0, '', 'T');

            $pdf->Output('F', './archivos/' . 'encuesta' .'.pdf', 'I');
            $payload = json_encode(array("mensaje" => 'archivo generado ./archivos/' . 'encuesta' .'.pdf'));
            ChangelogController::CargarUno("pdf",0,0,"Guardar Encuestas","Se descargaron datos de encuestas en pdf");
        }
        else
        {
            $payload = json_encode(array("error" => 'Producto no encontrado'));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function GuardarEnBd($request, $response, $args)
    {
        $lista = Encuesta::GuardarCsvEnBd();

        if($lista != false)
        {
          $payload = json_encode(array("mensaje" =>"Archivo guardado"));
        }
        else
        {
          $payload = json_encode(array("mensaje" =>"mensaje el archivo no existe"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function LeerEncuesta($request, $response, $args)
    {
        $lista = Encuesta::LeerEncuestasCSV('./archivos/encuestaBD.csv');

        if($lista != false)
        {
          $payload = json_encode(array("mensaje" => $lista));
        }
        else
        {
          $payload = json_encode(array("mensaje" =>"mensaje el archivo no existe"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function GuardarCSVEncuesta($request,$response,$next)
    {
        $lista = Encuesta::obtenerTodos();
        $e = json_encode(array("listaCompleta" => $lista));
        $archivo = fopen("./archivos/encuestaBD.csv","a");
        $bool = fwrite($archivo, $this->DatosToCSVEnc($e));
        $payload = json_encode(array("mensaje" => "Se guardo el CSV"));
        ChangelogController::CargarUno("csv",0,0,"Guardar Encuesta","Se descargaron datos de encuestas en csv");
        fclose($archivo);
        if($bool == false)
        {
            $payload = json_encode(array("mensaje" => "No se guardo el archivo"));
        }

        $response->getBody()->write($payload);
        return $response;
    }

    public function DatosToCSVEnc($datos)
    {
        $lista = json_decode($datos);
        $cadena = "";
        foreach($lista->listaCompleta as $dato)
        {
            $cadena .= "{" . $dato->id . "," . $dato->codigo . "," . $dato->mesa . "," . $dato->restaurante . ',' . $dato->mozo . ',' . $dato->cocinero . ',' . $dato->experiencia . "}" . ",\n";
        }
        return $cadena;  
    }

    public function GuardarCSV($request,$response,$next)
    {
        $lista = Usuario::obtenerTodos();
        $usuarios = json_encode(array("listaCompleta" => $lista));
        $archivo = fopen("./archivos/usuarios.csv","a");
        $bool = fwrite($archivo, $this->DatosToCSV($usuarios));
        $payload = json_encode(array("mensaje" => "Se guardo el CSV"));
        ChangelogController::CargarUno("csv",0,0,"Guardar Usuarios","Se descargaron datos de usuarios en csv");
        fclose($archivo);
        if($bool == false)
        {
            $payload = json_encode(array("mensaje" => "No se guardo el archivo"));
        }

        $response->getBody()->write($payload);
        return $response;
    }

    public function DatosToCSV($datos)
    {
        $lista = json_decode($datos);
        $cadena = "";
        foreach($lista->listaCompleta as $dato)
        {
            $cadena .= "{" . $dato->idUser . "," . $dato->nombre . "," . $dato->apellido . "," . $dato->mail . ',' . $dato->clave . ',' . $dato->puesto . ',' . $dato->estado . ',' . $dato->idPuesto . ','. $dato->idEstado . "}" . ",\n";
        }
        return $cadena;  
    }
}
?>