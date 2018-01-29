<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Http\Services\ReportService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel;
use PHPExcel_IOFactory;

class ReportController extends Controller
{

    function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    function getUsers()
    {
        $data = [];
        $dataUsers = DB::select("SELECT id,CONCAT(users.apellido_paterno,' ',users.apellido_materno,', ',users.primer_nombre) AS 'nameComplete',username FROM users;");
        foreach ($dataUsers as $v) {
            array_push($data, ["id" => $v->id, "value" => $v->nameComplete]);
        }
        $rpta["load"] = true;
        $rpta["data"] = $data;
        return response()->json($rpta);
    }

    function selectReport(ReportRequest $request)
    {
        $rpta = null;
        if ($request->puser_id > 0) {
            $rpta = $this->byUser($request);
        } else {
            $rpta = $this->byUserAll($request);
        }
        return response()->json($rpta);
    }

    function byUser($request)
    {
        return $this->service->reportToJsonService($request);
    }

    function byUserAll($request)
    {
        ini_set('max_execution_time', 300);
        $users = DB::select("select id,primer_nombre,segundo_nombre,apellido_paterno,apellido_materno from users");
        $data = [];
        foreach ($users as $key => $user) {
            $newrpta = $this->service->reportToJsonService($request, ["user" => $user]);
            array_push($data, [$user->primer_nombre . ' ' . $user->apellido_paterno . ' ' . $user->apellido_materno => $newrpta["data"]]);
        }
        $this->fnSuccess($data);
        return $this->rpta;
    }

    public function export(ReportRequest $request)
    {
        try {
            //Ejemplos:
            //Usando Maatwebsite Excel Laravel
            $filename = "byAll";
            if ($request->puser_id > 0) {
                $filename = "byUser";
                $rpta = $this->byUser($request);
            } else {
                $rpta = $this->byUserAll($request);
            }
            /*
                    return Excel::create($filename, function ($writer) use ($filename,$rpta) {
                        $writer->sheet($filename, function ($sheet)use($rpta) {
                            $sheet->setOrientation('portrait');
                            $headers = [
                                "A1" => "ID USUARIO",
                                "B1" => "NOMBRE COMPLETO",
                                "C1" => "NICK",
                            ];
                            //Get query
            //                $rpta=
            //                $newdata = DB::table("users")->select(["id", DB::raw("CONCAT(users.apellido_paterno,' ',users.apellido_materno,', ',users.primer_nombre) AS 'nameComplete'"), "username"])->get();
                            //Process query fomated
                            $data = collect($rpta["data"])->map(function ($x) {
                                return (array)$x;
                            })->toArray();
                            dd($data);
                            //Generate rows
                            $sheet->fromArray($rpta["data"]);
                            //Generate columns
                            foreach ($headers as $k => $v) {
                                $sheet->cell($k, function ($cell) use ($v) {
                                    //Manipulate the cell
                                    $cell->setValue($v);
                                });
                            }
                        });
                    })->export(request("ext"));
            */

            //Usando PHPExcel library


            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet(1)->setTitle($filename);

            if ($request->puser_id > 0) {
                $objPHPExcel->getActiveSheet(1)->getStyle('A1:AD1')->applyFromArray($this->colorFillNoneSolid);
                $objPHPExcel->getActiveSheet(1)->getStyle('B1')->applyFromArray($this->colorFillYellowSolid);
                $objPHPExcel->getActiveSheet(1)->getStyle('AC1')->applyFromArray($this->colorFillRedSolid);
                $objPHPExcel->getActiveSheet(1)->getStyle('A1:AD1')->getAlignment()->setTextRotation(90);
                $columns = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD"];
                $headers = ["HORA", "DIFERENCIA_INICIAL", "ACD", "BREAK", "SSHH", "REFRIGERIO", "FEEDBACK", "CAPACITACION", "BACKOFFICE", "INBOUND", "OUTBOUND",
                    "LOGIN", "RING_INBOUND", "RING_OUTBOUND", "HOLD_INBOUND", "HOLD_OUTBOUND", "RING_INBOUND_INTERNO", "INBOUND_INTERNO",
                    "OUTBOUND_INTERNO", "RING_OUTBOUND_INTERNO", "HOLD_INBOUND_INTERNO", "HOLD_OUTBOUND_INTERNO", "RING_INBOUND_TRANSFER",
                    "INBOUND_TRANSFER", "HOLD_INBOUND_TRANSFER", "RING_OUTBOUND_TRANSFER", "HOLD_OUTBOUND_TRANSFER", "DESCONECTADO", "DIFERENCIA_FINAL", "TOTAL"];
            } else {
                $objPHPExcel->getActiveSheet(1)->getStyle('A1:AE1')->applyFromArray($this->colorFillNoneSolid);
                $objPHPExcel->getActiveSheet(1)->getStyle('C1')->applyFromArray($this->colorFillYellowSolid);
                $objPHPExcel->getActiveSheet(1)->getStyle('AD1')->applyFromArray($this->colorFillRedSolid);
                $objPHPExcel->getActiveSheet(1)->getStyle('C1:AE1')->getAlignment()->setTextRotation(90);
                $columns = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE"];
                $headers = ["USUARIO", "HORA", "DIFERENCIA_INICIAL", "ACD", "BREAK", "SSHH", "REFRIGERIO", "FEEDBACK", "CAPACITACION", "BACKOFFICE", "INBOUND", "OUTBOUND",
                    "LOGIN", "RING_INBOUND", "RING_OUTBOUND", "HOLD_INBOUND", "HOLD_OUTBOUND", "RING_INBOUND_INTERNO", "INBOUND_INTERNO",
                    "OUTBOUND_INTERNO", "RING_OUTBOUND_INTERNO", "HOLD_INBOUND_INTERNO", "HOLD_OUTBOUND_INTERNO", "RING_INBOUND_TRANSFER",
                    "INBOUND_TRANSFER", "HOLD_INBOUND_TRANSFER", "RING_OUTBOUND_TRANSFER", "HOLD_OUTBOUND_TRANSFER", "DESCONECTADO", "DIFERENCIA_FINAL", "TOTAL"];
            }

            $worksheet = $this->fnCreateExcel($objPHPExcel, $headers, $columns);
            $i = 2;

            if ($request->puser_id > 0) {
                //First
                foreach ($rpta["data"] as $k => $v) {
                    $v = (object)$v;
                    $worksheet->setCellValue($columns[0] . $i, $k);
                    $worksheet->setCellValue($columns[1] . $i, $v->diff_inicial);
                    $worksheet->setCellValue($columns[2] . $i, $v->acd);
                    $worksheet->setCellValue($columns[3] . $i, $v->break);
                    $worksheet->setCellValue($columns[4] . $i, $v->sshh);
                    $worksheet->setCellValue($columns[5] . $i, $v->refrigerio);
                    $worksheet->setCellValue($columns[6] . $i, $v->feedback);
                    $worksheet->setCellValue($columns[7] . $i, $v->capacitacion);
                    $worksheet->setCellValue($columns[8] . $i, $v->backoffice);
                    $worksheet->setCellValue($columns[9] . $i, $v->inbound);
                    $worksheet->setCellValue($columns[10] . $i, $v->outbound);
                    $worksheet->setCellValue($columns[11] . $i, $v->login);
                    $worksheet->setCellValue($columns[12] . $i, $v->ring_inbound);
                    $worksheet->setCellValue($columns[13] . $i, $v->ring_outbound);
                    $worksheet->setCellValue($columns[14] . $i, $v->hold_inbound);
                    $worksheet->setCellValue($columns[15] . $i, $v->hold_outbound);
                    $worksheet->setCellValue($columns[16] . $i, $v->ring_inbound_interno);
                    $worksheet->setCellValue($columns[17] . $i, $v->inbound_interno);
                    $worksheet->setCellValue($columns[18] . $i, $v->outbound_interno);
                    $worksheet->setCellValue($columns[19] . $i, $v->ring_outbound_interno);
                    $worksheet->setCellValue($columns[20] . $i, $v->hold_inbound_interno);
                    $worksheet->setCellValue($columns[21] . $i, $v->hold_outbound_interno);
                    $worksheet->setCellValue($columns[22] . $i, $v->ring_inbound_transfer);
                    $worksheet->setCellValue($columns[23] . $i, $v->inbound_transfer);
                    $worksheet->setCellValue($columns[24] . $i, $v->hold_inbound_transfer);
                    $worksheet->setCellValue($columns[25] . $i, $v->ring_outbound_transfer);
                    $worksheet->setCellValue($columns[26] . $i, $v->hold_outbound_transfer);
                    $worksheet->setCellValue($columns[27] . $i, $v->desconectado);
                    $worksheet->setCellValue($columns[28] . $i, $v->diff_final);
                    $worksheet->setCellValue($columns[29] . $i, $v->total);
                    $i++;
                }
            } else {
                //Second
                foreach ($rpta["data"] as $k => $v) {
                    foreach ($v as $kk => $vv) {
                        foreach ($vv as $kkk => $vvv) {
                            $vvv = (object)$vvv;
                            $worksheet->setCellValue($columns[0] . $i, $kk);
                            $worksheet->setCellValue($columns[1] . $i, $kkk);
                            $worksheet->setCellValue($columns[2] . $i, $vvv->diff_inicial);
                            $worksheet->setCellValue($columns[3] . $i, $vvv->acd);
                            $worksheet->setCellValue($columns[4] . $i, $vvv->break);
                            $worksheet->setCellValue($columns[5] . $i, $vvv->sshh);
                            $worksheet->setCellValue($columns[6] . $i, $vvv->refrigerio);
                            $worksheet->setCellValue($columns[7] . $i, $vvv->feedback);
                            $worksheet->setCellValue($columns[8] . $i, $vvv->capacitacion);
                            $worksheet->setCellValue($columns[9] . $i, $vvv->backoffice);
                            $worksheet->setCellValue($columns[10] . $i, $vvv->inbound);
                            $worksheet->setCellValue($columns[11] . $i, $vvv->outbound);
                            $worksheet->setCellValue($columns[12] . $i, $vvv->login);
                            $worksheet->setCellValue($columns[13] . $i, $vvv->ring_inbound);
                            $worksheet->setCellValue($columns[14] . $i, $vvv->ring_outbound);
                            $worksheet->setCellValue($columns[15] . $i, $vvv->hold_inbound);
                            $worksheet->setCellValue($columns[16] . $i, $vvv->hold_outbound);
                            $worksheet->setCellValue($columns[17] . $i, $vvv->ring_inbound_interno);
                            $worksheet->setCellValue($columns[18] . $i, $vvv->inbound_interno);
                            $worksheet->setCellValue($columns[19] . $i, $vvv->outbound_interno);
                            $worksheet->setCellValue($columns[20] . $i, $vvv->ring_outbound_interno);
                            $worksheet->setCellValue($columns[21] . $i, $vvv->hold_inbound_interno);
                            $worksheet->setCellValue($columns[22] . $i, $vvv->hold_outbound_interno);
                            $worksheet->setCellValue($columns[23] . $i, $vvv->ring_inbound_transfer);
                            $worksheet->setCellValue($columns[24] . $i, $vvv->inbound_transfer);
                            $worksheet->setCellValue($columns[25] . $i, $vvv->hold_inbound_transfer);
                            $worksheet->setCellValue($columns[26] . $i, $vvv->ring_outbound_transfer);
                            $worksheet->setCellValue($columns[27] . $i, $vvv->hold_outbound_transfer);
                            $worksheet->setCellValue($columns[28] . $i, $vvv->desconectado);
                            $worksheet->setCellValue($columns[29] . $i, $vvv->diff_final);
                            $worksheet->setCellValue($columns[30] . $i, $vvv->total);
                            $i++;
                        }
                    }
                }
            }

            $this->downloadFileExport($objPHPExcel, $filename, $request->ext); // Por defecto es '.xlsx'
        } catch (\PHPExcel_Exception $e) {
            echo $e->getMessage();
        }

    }
}
