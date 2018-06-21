<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Http\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPExcel;
use Exception;

class ReportController extends Controller
{

    function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    function getUsers()
    {
        try {
            $data = [];
            $dataUsers = DB::select("SELECT id,CONCAT(users.apellido_paterno,' ',users.apellido_materno,', ',users.primer_nombre) AS 'nameComplete',username FROM users;");
            foreach ($dataUsers as $v) {
                array_push($data, ["id" => $v->id, "value" => $v->nameComplete]);
            }
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 412);
        }
    }

    function selectReport(ReportRequest $request)
    {
        $data = null;
        try {
            if ($request->user_id > 0) {
                $data = $this->reportByUser($request);
            } else {
                $data = $this->reportByUserAll($request);
            }
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 412);
        }

    }

    function reportByUser($request)
    {
        return $this->service->reportToJsonService($request);
    }

    function reportByUserAll($request)
    {
        $users = DB::select("select id,primer_nombre,segundo_nombre,apellido_paterno,apellido_materno from users");
        $data = [];
        foreach ($users as $key => $user) {
            $newdata = $this->service->reportToJsonService($request, ["user" => $user]);
            array_push($data, [$user->primer_nombre . ' ' . $user->apellido_paterno . ' ' . $user->apellido_materno => $newdata]);
        }
        return $data;
    }

  /**
   * @param ReportRequest $request
   */
  public function export(ReportRequest $request)
    {
        try {
            //Request:
            $filename = "byUserAll";
            if ($request->user_id > 0) {
                $sheetname = $request->username;
                $filename = "byUser";
                $data = $this->reportByUser($request);
            } else {
                $sheetname = $filename;
                $data = $this->reportByUserAll($request);
            }
            //Usando PHPExcel library
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet(1)->setTitle($sheetname);

            if ($request->user_id > 0) {
                $objPHPExcel->getActiveSheet(1)->getStyle('A1:AF1')->applyFromArray($this->colorFillNoneSolid);
                $objPHPExcel->getActiveSheet(1)->getStyle('B1')->applyFromArray($this->colorFillYellowSolid);
                $objPHPExcel->getActiveSheet(1)->getStyle('AC1')->applyFromArray($this->colorFillRedSolid);
                $objPHPExcel->getActiveSheet(1)->getStyle('B1:AD1')->getAlignment()->setTextRotation(90);

                $columns = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF"];
                $headers = ["HORA", "DIFERENCIA_INICIAL", "ACD", "BREAK", "SSHH", "REFRIGERIO", "FEEDBACK", "CAPACITACION", "BACKOFFICE", "INBOUND", "OUTBOUND",
                    "LOGIN", "RING_INBOUND", "RING_OUTBOUND", "HOLD_INBOUND", "HOLD_OUTBOUND", "RING_INBOUND_INTERNO", "INBOUND_INTERNO",
                    "OUTBOUND_INTERNO", "RING_OUTBOUND_INTERNO", "HOLD_INBOUND_INTERNO", "HOLD_OUTBOUND_INTERNO", "RING_INBOUND_TRANSFER",
                    "INBOUND_TRANSFER", "HOLD_INBOUND_TRANSFER", "RING_OUTBOUND_TRANSFER", "HOLD_OUTBOUND_TRANSFER", "DESCONECTADO", "DIFERENCIA_FINAL", "TOTAL", "NIVEL OCUPACION", "NIVEL OCUPACION BACKOFFICE"];
            } else {
                $objPHPExcel->getActiveSheet(1)->getStyle('A1:AG1')->applyFromArray($this->colorFillNoneSolid);
                $objPHPExcel->getActiveSheet(1)->getStyle('C1')->applyFromArray($this->colorFillYellowSolid);
                $objPHPExcel->getActiveSheet(1)->getStyle('AD1')->applyFromArray($this->colorFillRedSolid);
                $objPHPExcel->getActiveSheet(1)->getStyle('C1:AG1')->getAlignment()->setTextRotation(90);

                $columns = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG"];
                $headers = ["USUARIO", "HORA", "DIFERENCIA_INICIAL", "ACD", "BREAK", "SSHH", "REFRIGERIO", "FEEDBACK", "CAPACITACION", "BACKOFFICE", "INBOUND", "OUTBOUND",
                    "LOGIN", "RING_INBOUND", "RING_OUTBOUND", "HOLD_INBOUND", "HOLD_OUTBOUND", "RING_INBOUND_INTERNO", "INBOUND_INTERNO",
                    "OUTBOUND_INTERNO", "RING_OUTBOUND_INTERNO", "HOLD_INBOUND_INTERNO", "HOLD_OUTBOUND_INTERNO", "RING_INBOUND_TRANSFER",
                    "INBOUND_TRANSFER", "HOLD_INBOUND_TRANSFER", "RING_OUTBOUND_TRANSFER", "HOLD_OUTBOUND_TRANSFER", "DESCONECTADO", "DIFERENCIA_FINAL", "TOTAL", "NIVEL OCUPACION", "NIVEL OCUPACION BACKOFFICE"];
            }

            $worksheet = $this->fnCreateExcel($objPHPExcel, $headers, $columns);
            $i = 2;

            if ($request->user_id > 0) {
                //First
                foreach ($data as $k => $v) {
                    $v = (object)$v;
                    $worksheet->setCellValue($columns[0] . $i, $k);
                    $worksheet->setCellValue($columns[1] . $i, $v->diff_inicial / 86400);
                    $worksheet->setCellValue($columns[2] . $i, $v->acd / 86400);
                    $worksheet->setCellValue($columns[3] . $i, $v->break / 86400);
                    $worksheet->setCellValue($columns[4] . $i, $v->sshh / 86400);
                    $worksheet->setCellValue($columns[5] . $i, $v->refrigerio / 86400);
                    $worksheet->setCellValue($columns[6] . $i, $v->feedback / 86400);
                    $worksheet->setCellValue($columns[7] . $i, $v->capacitacion / 86400);
                    $worksheet->setCellValue($columns[8] . $i, $v->backoffice / 86400);
                    $worksheet->setCellValue($columns[9] . $i, $v->inbound / 86400);
                    $worksheet->setCellValue($columns[10] . $i, $v->outbound / 86400);
                    $worksheet->setCellValue($columns[11] . $i, $v->login / 86400);
                    $worksheet->setCellValue($columns[12] . $i, $v->ring_inbound / 86400);
                    $worksheet->setCellValue($columns[13] . $i, $v->ring_outbound / 86400);
                    $worksheet->setCellValue($columns[14] . $i, $v->hold_inbound / 86400);
                    $worksheet->setCellValue($columns[15] . $i, $v->hold_outbound / 86400);
                    $worksheet->setCellValue($columns[16] . $i, $v->ring_inbound_interno / 86400);
                    $worksheet->setCellValue($columns[17] . $i, $v->inbound_interno / 86400);
                    $worksheet->setCellValue($columns[18] . $i, $v->outbound_interno / 86400);
                    $worksheet->setCellValue($columns[19] . $i, $v->ring_outbound_interno / 86400);
                    $worksheet->setCellValue($columns[20] . $i, $v->hold_inbound_interno / 86400);
                    $worksheet->setCellValue($columns[21] . $i, $v->hold_outbound_interno / 86400);
                    $worksheet->setCellValue($columns[22] . $i, $v->ring_inbound_transfer / 86400);
                    $worksheet->setCellValue($columns[23] . $i, $v->inbound_transfer / 86400);
                    $worksheet->setCellValue($columns[24] . $i, $v->hold_inbound_transfer / 86400);
                    $worksheet->setCellValue($columns[25] . $i, $v->ring_outbound_transfer / 86400);
                    $worksheet->setCellValue($columns[26] . $i, $v->hold_outbound_transfer / 86400);
                    $worksheet->setCellValue($columns[27] . $i, $v->desconectado / 86400);
                    $worksheet->setCellValue($columns[28] . $i, $v->diff_final / 86400);
                    $worksheet->setCellValue($columns[29] . $i, $v->total / 86400);
                    $objPHPExcel->getActiveSheet(1)->getStyle('B' . $i . ':AD' . $i)->getNumberFormat()->setFormatCode("HH:mm:ss");
                    $worksheet->setCellValue($columns[30] . $i, $v->nivel_ocupacion . " %");
                    $worksheet->setCellValue($columns[31] . $i, $v->nivel_ocupacion_backoffice . " %");
                    $i++;
                }
            } else {
                //Second
                foreach ($data as $k => $v) {
                    foreach ($v as $kk => $vv) {
                        foreach ($vv as $kkk => $vvv) {
                            $vvv = (object)$vvv;
                            $worksheet->setCellValue($columns[0] . $i, $kk);
                            $worksheet->setCellValue($columns[1] . $i, $kkk);
                            $worksheet->setCellValue($columns[2] . $i, $vvv->diff_inicial / 86400);
                            $worksheet->setCellValue($columns[3] . $i, $vvv->acd / 86400);
                            $worksheet->setCellValue($columns[4] . $i, $vvv->break / 86400);
                            $worksheet->setCellValue($columns[5] . $i, $vvv->sshh / 86400);
                            $worksheet->setCellValue($columns[6] . $i, $vvv->refrigerio / 86400);
                            $worksheet->setCellValue($columns[7] . $i, $vvv->feedback / 86400);
                            $worksheet->setCellValue($columns[8] . $i, $vvv->capacitacion / 86400);
                            $worksheet->setCellValue($columns[9] . $i, $vvv->backoffice / 86400);
                            $worksheet->setCellValue($columns[10] . $i, $vvv->inbound / 86400);
                            $worksheet->setCellValue($columns[11] . $i, $vvv->outbound / 86400);
                            $worksheet->setCellValue($columns[12] . $i, $vvv->login / 86400);
                            $worksheet->setCellValue($columns[13] . $i, $vvv->ring_inbound / 86400);
                            $worksheet->setCellValue($columns[14] . $i, $vvv->ring_outbound / 86400);
                            $worksheet->setCellValue($columns[15] . $i, $vvv->hold_inbound / 86400);
                            $worksheet->setCellValue($columns[16] . $i, $vvv->hold_outbound / 86400);
                            $worksheet->setCellValue($columns[17] . $i, $vvv->ring_inbound_interno / 86400);
                            $worksheet->setCellValue($columns[18] . $i, $vvv->inbound_interno / 86400);
                            $worksheet->setCellValue($columns[19] . $i, $vvv->outbound_interno / 86400);
                            $worksheet->setCellValue($columns[20] . $i, $vvv->ring_outbound_interno / 86400);
                            $worksheet->setCellValue($columns[21] . $i, $vvv->hold_inbound_interno / 86400);
                            $worksheet->setCellValue($columns[22] . $i, $vvv->hold_outbound_interno / 86400);
                            $worksheet->setCellValue($columns[23] . $i, $vvv->ring_inbound_transfer / 86400);
                            $worksheet->setCellValue($columns[24] . $i, $vvv->inbound_transfer / 86400);
                            $worksheet->setCellValue($columns[25] . $i, $vvv->hold_inbound_transfer / 86400);
                            $worksheet->setCellValue($columns[26] . $i, $vvv->ring_outbound_transfer / 86400);
                            $worksheet->setCellValue($columns[27] . $i, $vvv->hold_outbound_transfer / 86400);
                            $worksheet->setCellValue($columns[28] . $i, $vvv->desconectado / 86400);
                            $worksheet->setCellValue($columns[29] . $i, $vvv->diff_final / 86400);
                            $worksheet->setCellValue($columns[30] . $i, $vvv->total / 86400);
                            $objPHPExcel->getActiveSheet(1)->getStyle('C' . $i . ':AE' . $i)->getNumberFormat()->setFormatCode("HH:mm:ss");
                            $worksheet->setCellValue($columns[31] . $i, $vvv->nivel_ocupacion . " %");
                            $worksheet->setCellValue($columns[32] . $i, $vvv->nivel_ocupacion_backoffice . " %");
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

    function getReportByDate(Request $request)
    {
        $this->service->getReportByDate(60, ["user" => (object)['id'=>13]],$request);
    }

}
