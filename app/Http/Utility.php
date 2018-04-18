<?php
/**
 * Created by PhpStorm.
 * User: aquispe
 * Date: 04/08/2017
 * Time: 05:45 PM
 */

namespace App\Http;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

$carbon = (new Carbon());
define('FORMAT_DEFAULT', 'Y-m-d');
define('FORMAT_DATE', $carbon->format(FORMAT_DEFAULT));
define('FORMAT_HOUR', $carbon->format('H:i:s'));
define('FORMAT_TIMESTAMP', $carbon->now()->format('Y-m-d H:i:s'));
define('FORMAT_DETAIL', $carbon->now()->format('YmdHis'));
define('PATH_USERS', public_path() . '/images/users/');
define('ASSET_USERS', asset('/images/users') . '/');
define('ASSET_APP', asset('/images/app') . '/');

trait Utility
{

    //variables Respuesta y de uso para los estilos de Excel.
    public $rpta = [], $service, $APP_KEY,
        $textAlignHCenter = ['alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER]],
        $textAlignVCenter = ['alignment' => ['horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER]],
        $textAlignHRight = ['alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT]],
        $textAlignHLeft = ['alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT]],
        $borderAllBordersThin = ['borders' => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]]],
        $borderOutlineThin = ['borders' => ['outline' => ['style' => PHPExcel_Style_Border::BORDER_THIN]]],
        $colorFillBlueSolid = ['fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '2196F3']]],
        $colorFillGreenSolid = ['fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '4caf50']]],
        $colorFillTealSolid = ['fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '009688']]],
        $colorFillGreySolid = ['fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '9e9e9e']]],
        $colorFillBlueGreySolid = ['fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '607d8b']]],
        $colorFillYellowSolid = ['fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'ffeb3b']]],
        $colorFillAmberSolid = ['fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'ffc107']]],
        $colorFillOrangeSolid = ['fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'ff9800']]],
        $colorFillIndigoSolid = ['fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '3f51b5']]],
        $colorFillRedSolid = ['fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'f44336']]],
        $colorFillNoneSolid = ['fill' => ['type' => PHPExcel_Style_Fill::FILL_NONE]],
        $textDefaultBold = ['font' => ['bold' => true, 'color' => ['rgb' => '000000']]],
        $textWhiteBold = ['font' => ['bold' => true, 'color' => ['rgb' => 'ffffff']]],
        $textWhite = ['font' => ['bold' => false, 'color' => ['rgb' => 'ffffff']]],
        $textBlack = ['font' => ['bold' => false, 'color' => ['rgb' => '000000']]],
        $textGrey = ['font' => ['bold' => false, 'color' => ['rgb' => '9e9e9e']]];

    //metodo generico que intercede para el catch y prepara la respuesta generica.
    function fnException($exception = null, $title = 'my exception', $level = 'danger')
    {
        if (!empty($exception)) {
            if ($exception->getCode() > 0) {//is PDOException
                $this->rpta = ['load' => false, 'title' => $title, 'message' => $exception->getMessage(), 'level' => $level];
            } else {//is Exception
                $this->rpta = ['load' => false, 'title' => $title, 'message' => $exception->getMessage(), 'level' => $level];
            }
            $this->fnDoLog('E', $this->rpta['message']);
        }
    }

    //metodo generico que ingresa en la respuesta generica para notificar mensaje personalizado de Error.
    function fnError($message = null, $title = 'my error', $level = 'danger')
    {
        if (!is_null($message)) {//is Error
            $this->rpta = ['load' => false, 'title' => $title, 'message' => $message, 'level' => $level];
            $this->fnDoLog('E', $this->rpta['message']);
        }
    }

    function fnErrorException($e = null, $title = 'Error Exception', $level = 'danger')
    {
        if (!empty($e)) {
            if ($e->getCode() > 0) {//is PDOException
                $this->rpta = ['load' => false, 'title' => $title, 'message' => $e->getMessage(), 'level' => $level];
                $this->fnDoLog('E', "MENSAJE: " . $e->getMessage() . " | LINEA: " . $e->getLine() . " | ARCHIVO: " . $e->getFile());
            } else {//is Exception
                $this->rpta = ['load' => false, 'title' => $title, 'message' => $e->getMessage(), 'level' => $level];
                $this->fnDoLog('E', "MENSAJE: " . $this->rpta['message']);
            }
        }
    }

    //metodo generico que realiza la respuesta generica de satisfaccion.
    function fnSuccess($data = [], $message = 'ejecutado correctamente', $title = 'Very Good', $level = 'success')
    {
        if (auth()->check()) {
            $auth = [
                "remember_token" => auth()->user()->getRememberToken(),
                "token" => csrf_token(),
                "id" => auth()->user()->getAuthIdentifier(),
                "nombres_apellidos" => auth()->user()->nombres." ".auth()->user()->apellido_paterno." ".auth()->user()->apellido_materno
            ];
            $this->rpta = ['load' => true, 'data' => $data, "auth" => $auth, 'title' => $title, 'message' => $message, 'level' => $level];
        } else {
            $this->rpta = ['load' => true, 'data' => $data, 'title' => $title, 'message' => $message, 'level' => $level];
        }
        $this->fnDoLog('I', $message);
    }

    //metodo generico que realiza la respuesta.
    public function fnFlashMessage($title = 'very good', $message = 'execute successfully', $level = 'success')
    {
        $arr_message = ['title' => $title, 'message' => $message];
        $notifier = app('flash');
        if (!is_null($message)) {
            $notifier->message($arr_message, $level);
        }
        return $notifier;
    }

    //metodo generico para crear y exportar EXCEL.
    protected function fnCreateExcel($objPHPExcel, $headers, $columns, $row = 1, $merge = false)
    {
        $objPHPExcel
            ->getProperties()
            ->setCreator('aquisper@sapia.com.pe')
            ->setTitle("Corporación Sapia");

        $objPHPExcel->setActiveSheetIndex(0);
        $worksheet = $objPHPExcel->getActiveSheet();
        $total = count($columns);

        for ($i = 0; $i < $total; $i++) {
            if ($merge) {
                if (is_array($columns[$i])) {
                    $foo = $columns[$i][0] . $row . ':' . $columns[$i][1] . $row;
                    $worksheet->mergeCells($foo);
                    $worksheet->setCellValue($columns[$i][0] . $row, $headers[$i]);
                } else {
                    $worksheet->getColumnDimension($columns[$i])->setAutoSize(true);
                    $worksheet->setCellValue($columns[$i] . $row, $headers[$i]);
                }
            } else {
                $worksheet->getColumnDimension($columns[$i])->setAutoSize(true);
                $worksheet->setCellValue($columns[$i] . $row, $headers[$i]);
            }
        }

        if (is_array($columns[$total - 1])) {
            $oneColumn = $columns[$total - 1][1];
        } else {
            $oneColumn = $columns[$total - 1];
        }

        // Dejar estatico solo la primera fila
        $worksheet->freezePane('A' . ($row + 1));
        $styleArray = array(
            'font' => array(
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );
        $worksheet->getStyle($columns[0] . $row . ':' . $oneColumn . $row)->applyFromArray($styleArray);
        return $worksheet;
    }

    function downloadFileExport($objPHPExcel,$filename, $ext = 'xlsx',$type = 'Excel2007')
    {
        $filename = $filename . '_' . FORMAT_DETAIL;
        if ($type == 'Excel5' || $type == 'Excel2007') {
            if ($type == 'Excel5') {
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
            } else {
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename=' . $filename .'.'. $ext);
            }
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
        }

        $excelWriter = null;
        try {
            $excelWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } catch (\PHPExcel_Reader_Exception $e) {
            echo $e->getMessage();
        }
        try {
            $excelWriter->save('php://output');
        } catch (\PHPExcel_Writer_Exception $e) {
            echo $e->getMessage();
        }
        //End Load Export
        exit();

    }

    //metodo generico para crear y exportar PDF.
    protected function fnGeneratePDF($dompdf, $viewHtml, $config = null)
    {
        if (is_null($config)) {
            $config = [
                'attachment' => '0',
                'hoja' => 'A4',
                'filename' => 'test.pdf',
                'orientation' => 'P',
            ];
        }
        $dompdf->loadHtml($viewHtml->render());
        $dompdf->setPaper($config['hoja'], $config['orientation'] == 'P' ? 'portrait' : 'landscape');
        $dompdf->render();
        $dompdf->stream($config['filename'], ['Attachment' => $config['attachment']]);
    }

    //metodo generico para guardar una imagen en el servidor.
    protected function fnSaveImage($path, $request, $filename)
    {
        $image = Image::make($request);
        $image->save(public_path() . $path . FECHA_DETALLE . '_' . $filename);
        return FECHA_DETALLE . '_' . $filename;
    }

    function fnImage($type, $request, $filename)
    {
        $image = Image::make($request);
        $path = public_path() . '/images/app/';
        if ($type == 'save') {
            $image->save($path . FECHA_DETALLE . '_' . $filename);
            return FECHA_DETALLE . '_' . $filename;
        }
        if ($type == 'delete') {
            return File::delete(public_path() . $path . $request);
        }


    }

    //metodo generico para crear un archivo log y para trazar la informacion.
    private static function fnDoLog($type, $message)
    {
        // Stream Handlers
        $bubble = false;
        $monolog = new Logger('LOG');//titulo de level log

        $logFormat = "%level_name%.%channel% [%datetime%] => %message%\n";
        $formatter = new LineFormatter($logFormat);

        switch ($type) {
            case 'E'://Exception/Error
                $errorStreamHandler = new StreamHandler(storage_path() . "/logs/laravel_error.log", Logger::ERROR, $bubble);
                $errorStreamHandler->setFormatter($formatter);
                $monolog->pushHandler($errorStreamHandler);
                $monolog->addError(is_string($message) ? $message : $message->getMessage() . ' | ' . $message->getFile() . ' | ' . $message->getLine());
                break;
            default://Info
                $infoStreamHandler = new StreamHandler(storage_path() . "/logs/laravel_info.log", Logger::INFO, $bubble);
                $infoStreamHandler->setFormatter($formatter);
                $monolog->pushHandler($infoStreamHandler);
                $monolog->addInfo($message);
                break;
        }
    }

    //metodo generico que devuelve el Max Id Autoincrementado de una tabla de la base de datos.
    function fnGetAutoIncrement($table, $field = 'id')
    {
        $maxID = DB::table($table)->max($field);
        return (int)$maxID + 1;
    }

    //metodo generico para obtener datos de una tabla
    function fnGetTable($table, $where = null,$colums=["*"])
    {
        try {
            if (!empty($where)) {
                $data = DB::table($table)->select($colums)->where($where[0], $where[1])->get();
            } else {
                $data = DB::table($table)->select($colums)->get();
            }
            $this->fnSuccess($data);
        } catch (\Exception $e) {
            $this->fnErrorException($e);
        }
        return $this->rpta;
    }

}