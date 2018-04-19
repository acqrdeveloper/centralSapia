<?php
/**
 * Created by PhpStorm.
 * User: aquispe
 * Date: 27/01/2018
 * Time: 12:59 PM
 */

namespace App\Http\Services;


use App\Http\Utility;
use App\User;
use Carbon\Carbon;
use Exception;
use function foo\func;
use Illuminate\Support\Facades\DB;

class ReportService
{
    use Utility;

    function reportToJsonService($request = null, $option = null)
    {
        ini_set('max_execution_time', 300);
        $hours = $this->generateTimeRange('00:00:00', '23:30:00', (int)$request->time, false);
        $data = [];
        $login = 0;
        $acd = 0;
        $break = 0;
        $sshh = 0;
        $refrigerio = 0;
        $feedback = 0;
        $capacitacion = 0;
        $backoffice = 0;
        $inbound = 0;
        $outbound = 0;
        $ring_inbound = 0;
        $ring_outbound = 0;
        $hold_inbound = 0;
        $hold_outbound = 0;
        $ring_inbound_interno = 0;
        $inbound_interno = 0;
        $outbound_interno = 0;
        $ring_outbound_interno = 0;
        $hold_inbound_interno = 0;
        $hold_outbound_interno = 0;
        $ring_inbound_transfer = 0;
        $ring_outbound_transfer = 0;
        $inbound_transfer = 0;
        $hold_inbound_transfer = 0;
        $hold_outbound_transfer = 0;
        $outbound_transfer = 0;
        $desconectado = 0;
        $events = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28];
        $current_range_hour = "";
        $current_user_id = "";
        //
        //Recorrer array horas
        foreach ($hours as $k => $v) {
            //Variables
            $i = $k;
            $j = $k + 1;
            $total = 0;

            $temp_diff_ini = 0;
            $temp_diff_fin = 0;
            $set = false;
            $last_data = null;

            //Params procedure
            $temp_fecha = explode('/', $request->fecha);
            $param_fecha_ini = $temp_fecha[0];
            $param_fecha_fin = $temp_fecha[1];
            $param_user_id = $option["user"] != null ? $option["user"]->id : $request->user_id;
            $param_rol = $request->rol;

            //Validar posicion para el rango de horario
            if (isset($hours[$k + 1])) {
                $query = DB::select("call SP_REPORT_1800(?,?,?,?,?,?) ", [$param_fecha_ini, $param_fecha_fin, $hours[$k], $hours[$k + 1], $param_user_id, $param_rol]);
            } else {
                $query = DB::select("call SP_REPORT_1800(?,?,?,?,?,?) ", [$param_fecha_ini, $param_fecha_fin, $hours[$k], $hours[0], $param_user_id, $param_rol]);
            }

            //Si hay registros
            if (count($query)) {
                if (isset($hours[$k + 1])) {
                    $range_hour = $hours[$k] . " - " . $hours[$k + 1];
                } else {
                    $range_hour = $hours[$k] . " - " . $hours[0];
                }
                //Ultimo indice
                $index_final = count($query) - 1;
                //Recorrer registros
                foreach ($query as $kk => $vv) {

                    //Validar si estamos tratando el mismo usuario
                    if ($option["user"] != null) {
                        if ($vv->user_id != $current_user_id) {
                            $data = [];
                        }
                        if ($vv->user_id == $option["user"]->id) {
                            $current_user_id = $vv->user_id;
                        }
                    }
//##
                    if (isset($query[$kk + 1])) {
                        $diff_total = $this->getDiffDatetime($vv->date_event, $query[$kk + 1]->date_event, true);
                    } else {
                        $diff_total = $this->getDiffDatetime($vv->date_event, $query[0]->date_event, true);
                    }
                    //Primera regla
                    //Si es el primer indice
                    if ($kk == 0) {
                        $h = (new \DateTime($query[$kk]->date_event))->format("H:i:s");
                        if ($h != $hours[$i]) {
                            $temp_diff_ini = $this->getDiffDatetime($query[$kk]->date_event, $hours[$i], true);
                        }
                    }
                    //Segunda regla
                    //Si es el ultimo indice
                    if ($kk == $index_final) {
                        $h = (new \DateTime($query[$index_final]->date_event))->format("H:i:s");
                        if ($h != $hours[$j]) {
                            $temp_diff_fin = $this->getDiffDatetime($query[$index_final]->date_event, $hours[$j], true);
                        }
                    }
                    //Recorrer array rango por hora armado
                    for ($g = 0; $g <= count($hours); $g++) {
                        //Si es diferente al rango de hora
                        if ($range_hour != $current_range_hour) {
                            //Reinicializar estados
                            $login = 0;
                            $acd = 0;
                            $break = 0;
                            $sshh = 0;
                            $refrigerio = 0;
                            $feedback = 0;
                            $capacitacion = 0;
                            $backoffice = 0;
                            $inbound = 0;
                            $outbound = 0;
                            $ring_inbound = 0;
                            $ring_outbound = 0;
                            $hold_inbound = 0;
                            $hold_outbound = 0;
                            $ring_inbound_interno = 0;
                            $inbound_interno = 0;
                            $outbound_interno = 0;
                            $ring_outbound_interno = 0;
                            $hold_inbound_interno = 0;
                            $hold_outbound_interno = 0;
                            $ring_inbound_transfer = 0;
                            $ring_outbound_transfer = 0;
                            $inbound_transfer = 0;
                            $hold_inbound_transfer = 0;
                            $hold_outbound_transfer = 0;
                            $outbound_transfer = 0;
                            $desconectado = 0;
                            //Reinicializar total
                            $total = 0;
                        }
                        //Validar si seguimos en el rango de hora
                        if (isset($hours[$g + 1])) {
                            if ($range_hour == $hours[$g] . " - " . $hours[$g + 1]) {
                                //Set variable con rango de hora actual
                                $current_range_hour = $range_hour;
                            }
                        }
                    }
                    //Validar evento existente
                    $do = false;
                    for ($x = 0; $x <= count($events); $x++) {
                        if ($x == $vv->evento_id) {
                            $do = true;
                            break;
                        } else {
                            $do = false;
                        }
                    }
                    //Set ultimo indice para recalcular
                    if ($kk == $index_final) {
                        $diff_total = 0;
                        $set = true;
                        $last_data = array_merge(["data" => $vv], ["range" => $hours[$i] . " - " . $hours[$j]]);
                    } else {
                        $set = false;
                        $last_id = null;
                    }
                    //Validar y Cargar por evento
                    if ($do) {
                        switch ($vv->evento_id) {
                            case 1:
                                $acd += $diff_total;
                                break;
                            case 2:
                                $break += $diff_total;
                                break;
                            case 3:
                                $sshh += $diff_total;
                                break;
                            case 4:
                                $refrigerio += $diff_total;
                                break;
                            case 5:
                                $feedback += $diff_total;
                                break;
                            case 6:
                                $capacitacion += $diff_total;
                                break;
                            case 7:
                                $backoffice += $diff_total;
                                break;
                            case 8:
                                $inbound += $diff_total;
                                break;
                            case 9:
                                $outbound += $diff_total;
                                break;
                            case 11:
                                $login += $diff_total;
                                break;
                            case 12:
                                $ring_inbound += $diff_total;
                                break;
                            case 13:
                                $ring_outbound += $diff_total;
                                break;
                            case 15:
                                $desconectado += $diff_total;
                                break;
                            case 16:
                                $hold_inbound += $diff_total;
                                break;
                            case 17:
                                $hold_outbound += $diff_total;
                                break;
                            case 18:
                                $ring_inbound_interno += $diff_total;
                                break;
                            case 19:
                                $inbound_interno += $diff_total;
                                break;
                            case 20:
                                $outbound_interno += $diff_total;
                                break;
                            case 21:
                                $ring_outbound_interno += $diff_total;
                                break;
                            case 22:
                                $hold_inbound_interno += $diff_total;
                                break;
                            case 23:
                                $hold_outbound_interno += $diff_total;
                                break;
                            case 24:
                                $ring_inbound_transfer += $diff_total;
                                break;
                            case 25:
                                $inbound_transfer += $diff_total;
                                break;
                            case 26:
                                $hold_inbound_transfer += $diff_total;
                                break;
                            case 27:
                                $ring_outbound_transfer += $diff_total;
                                break;
                            case 28:
                                $hold_outbound_transfer += $diff_total;
                                break;
                            case 29:
                                $outbound_transfer += $diff_total;
                                break;
                        }
                    } else {
                        switch ($vv->evento_id) {
                            case 1:
                                $acd = $diff_total;
                                break;
                            case 2:
                                $break = $diff_total;
                                break;
                            case 3:
                                $sshh = $diff_total;
                                break;
                            case 4:
                                $refrigerio = $diff_total;
                                break;
                            case 5:
                                $feedback = $diff_total;
                                break;
                            case 6:
                                $capacitacion = $diff_total;
                                break;
                            case 7:
                                $backoffice = $diff_total;
                                break;
                            case 8:
                                $inbound = $diff_total;
                                break;
                            case 9:
                                $outbound = $diff_total;
                                break;
                            case 11:
                                $login = $diff_total;
                                break;
                            case 12:
                                $ring_inbound = $diff_total;
                                break;
                            case 13:
                                $ring_outbound = $diff_total;
                                break;
                            case 15:
                                $desconectado = $diff_total;
                                break;
                            case 16:
                                $hold_inbound = $diff_total;
                                break;
                            case 17:
                                $hold_outbound = $diff_total;
                                break;
                            case 18:
                                $ring_inbound_interno = $diff_total;
                                break;
                            case 19:
                                $inbound_interno = $diff_total;
                                break;
                            case 20:
                                $outbound_interno = $diff_total;
                                break;
                            case 21:
                                $ring_outbound_interno = $diff_total;
                                break;
                            case 22:
                                $hold_inbound_interno = $diff_total;
                                break;
                            case 23:
                                $hold_outbound_interno = $diff_total;
                                break;
                            case 24:
                                $ring_inbound_transfer = $diff_total;
                                break;
                            case 25:
                                $inbound_transfer = $diff_total;
                                break;
                            case 26:
                                $hold_inbound_transfer = $diff_total;
                                break;
                            case 27:
                                $ring_outbound_transfer = $diff_total;
                                break;
                            case 28:
                                $hold_outbound_transfer = $diff_total;
                                break;
                            case 29:
                                $outbound_transfer = $diff_total;
                                break;
                        }
                    }
                    //Calcular total, no sumar los ultimos registros para estabilizar los 30 min
                    if ($kk != $index_final) {
                        $total += $diff_total;
                    }
                    //##

                }//Fin ciclo $query
                //Calcular diferencias temporales
                //Si tiene temporal inicial Ej: [00:00:00 - 00:30:00] -> 00:10:00 = 10 min
                if ($temp_diff_ini > 0) {
                    $total = $total + $temp_diff_ini;
                }
                //Si tiene temporal fin Ej: [00:00:00 - 00:30:00] -> 00:25:00 = 5 min
                if ($temp_diff_fin > 0) {
                    $total = $total + $temp_diff_fin;
                }
                //Acondicionar los resultados de diferencia por estado
                if ($set) {
                    if ($last_data != null) {
                        if ($hours[$i] . " - " . $hours[$j] == $last_data["range"]) {
                            switch ($last_data["data"]->evento_id) {
                                case 1:
                                    $acd += $temp_diff_fin;
                                    break;
                                case 2:
                                    $break += $temp_diff_fin;
                                    break;
                                case 3:
                                    $sshh += $temp_diff_fin;
                                    break;
                                case 4:
                                    $refrigerio += $temp_diff_fin;
                                    break;
                                case 5:
                                    $feedback += $temp_diff_fin;
                                    break;
                                case 6:
                                    $capacitacion += $temp_diff_fin;
                                    break;
                                case 7:
                                    $backoffice += $temp_diff_fin;
                                    break;
                                case 8:
                                    $inbound += $temp_diff_fin;
                                    break;
                                case 9:
                                    $outbound += $temp_diff_fin;
                                    break;
                                case 11:
                                    $login += $temp_diff_fin;
                                    break;
                                case 12:
                                    $ring_inbound += $temp_diff_fin;
                                    break;
                                case 13:
                                    $ring_outbound += $temp_diff_fin;
                                    break;
                                case 15:
                                    $desconectado += $temp_diff_fin;
                                    break;
                                case 16:
                                    $hold_inbound += $temp_diff_fin;
                                    break;
                                case 17:
                                    $hold_outbound += $temp_diff_fin;
                                    break;
                                case 18:
                                    $ring_inbound_interno += $temp_diff_fin;
                                    break;
                                case 19:
                                    $inbound_interno += $temp_diff_fin;
                                    break;
                                case 20:
                                    $outbound_interno += $temp_diff_fin;
                                    break;
                                case 21:
                                    $ring_outbound_interno += $temp_diff_fin;
                                    break;
                                case 22:
                                    $hold_inbound_interno += $temp_diff_fin;
                                    break;
                                case 23:
                                    $hold_outbound_interno += $temp_diff_fin;
                                    break;
                                case 24:
                                    $ring_inbound_transfer += $temp_diff_fin;
                                    break;
                                case 25:
                                    $inbound_transfer += $temp_diff_fin;
                                    break;
                                case 26:
                                    $hold_inbound_transfer += $temp_diff_fin;
                                    break;
                                case 27:
                                    $ring_outbound_transfer += $temp_diff_fin;
                                    break;
                                case 28:
                                    $hold_outbound_transfer += $temp_diff_fin;
                                    break;
                                case 29:
                                    $outbound_transfer += $temp_diff_fin;
                                    break;
                            }
                        }
                    }
                }
            } else {
                $login = 0;
                $acd = 0;
                $break = 0;
                $sshh = 0;
                $refrigerio = 0;
                $feedback = 0;
                $capacitacion = 0;
                $backoffice = 0;
                $inbound = 0;
                $outbound = 0;
                $ring_inbound = 0;
                $ring_outbound = 0;
                $hold_inbound = 0;
                $hold_outbound = 0;
                $ring_inbound_interno = 0;
                $inbound_interno = 0;
                $outbound_interno = 0;
                $ring_outbound_interno = 0;
                $hold_inbound_interno = 0;
                $hold_outbound_interno = 0;
                $ring_inbound_transfer = 0;
                $ring_outbound_transfer = 0;
                $inbound_transfer = 0;
                $hold_inbound_transfer = 0;
                $hold_outbound_transfer = 0;
                $desconectado = 0;
                $temp_diff_ini = 0;
                $temp_diff_fin = 0;
                $total = 0;
            }

            //Calcular Porcentajes
            $totalACD = $inbound + $hold_inbound;
            $totalOutbound = $outbound + $ring_outbound + $hold_outbound;
            $totalBackoffice = $backoffice +
                $inbound_interno +
                $ring_inbound_interno +
                $hold_inbound_interno +
                $outbound_interno +
                $ring_outbound_interno +
                $hold_outbound_interno;
            $totalAuxiliares = $break + $sshh + $refrigerio + $feedback + $capacitacion;
            $totalAuxiliaresBack = $totalAuxiliares + $totalBackoffice;
            $totalSuma = $acd + $break + $sshh + $refrigerio + $feedback + $capacitacion + $backoffice + $inbound + $outbound +
                $ring_inbound + $ring_outbound + $hold_inbound + $hold_outbound + $ring_inbound_interno + $inbound_interno +
                $outbound_interno + $ring_outbound_interno + $hold_inbound_interno + $hold_outbound_interno + $ring_inbound_transfer + $inbound_transfer +
                $hold_inbound_transfer + $ring_outbound_transfer + $hold_outbound_transfer + $outbound_transfer + $desconectado;
            $tiempoLogeo = $totalSuma - $desconectado;
            $n1 = ($totalACD + $totalOutbound);
            $n2 = ($tiempoLogeo - $totalAuxiliaresBack);

            if ($n1 > 0 && $n2 > 0) {
                $total_ocupacion = (float)(($totalACD + $totalOutbound) / ($tiempoLogeo - $totalAuxiliaresBack));
            } else {
                $total_ocupacion = 0;
            }
            $n3 = ($totalACD + $totalOutbound + $totalBackoffice);
            $n4 = ($tiempoLogeo - $totalAuxiliares);
            if ($n3 > 0 && $n4 > 0) {
                $total_ocupacion_backoffice = (float)(($totalACD + $totalOutbound + $totalBackoffice) / ($tiempoLogeo - $totalAuxiliares));
            } else {
                $total_ocupacion_backoffice = 0;
            }

            //Set por rango de hora y estado
            if (isset($hours[$j])) {
                $data[$hours[$i] . " - " . $hours[$j]]["acd"] = $acd;
                $data[$hours[$i] . " - " . $hours[$j]]["break"] = $break;
                $data[$hours[$i] . " - " . $hours[$j]]["sshh"] = $sshh;
                $data[$hours[$i] . " - " . $hours[$j]]["refrigerio"] = $refrigerio;
                $data[$hours[$i] . " - " . $hours[$j]]["feedback"] = $feedback;
                $data[$hours[$i] . " - " . $hours[$j]]["capacitacion"] = $capacitacion;
                $data[$hours[$i] . " - " . $hours[$j]]["backoffice"] = $backoffice;
                $data[$hours[$i] . " - " . $hours[$j]]["inbound"] = $inbound;
                $data[$hours[$i] . " - " . $hours[$j]]["outbound"] = $outbound;
                $data[$hours[$i] . " - " . $hours[$j]]["login"] = $login;
                $data[$hours[$i] . " - " . $hours[$j]]["ring_inbound"] = $ring_inbound;
                $data[$hours[$i] . " - " . $hours[$j]]["ring_outbound"] = $ring_outbound;
                $data[$hours[$i] . " - " . $hours[$j]]["hold_inbound"] = $hold_inbound;
                $data[$hours[$i] . " - " . $hours[$j]]["hold_outbound"] = $hold_outbound;
                $data[$hours[$i] . " - " . $hours[$j]]["ring_inbound_interno"] = $ring_inbound_interno;
                $data[$hours[$i] . " - " . $hours[$j]]["inbound_interno"] = $inbound_interno;
                $data[$hours[$i] . " - " . $hours[$j]]["outbound_interno"] = $outbound_interno;
                $data[$hours[$i] . " - " . $hours[$j]]["ring_outbound_interno"] = $ring_outbound_interno;
                $data[$hours[$i] . " - " . $hours[$j]]["hold_inbound_interno"] = $hold_inbound_interno;
                $data[$hours[$i] . " - " . $hours[$j]]["hold_outbound_interno"] = $hold_outbound_interno;
                $data[$hours[$i] . " - " . $hours[$j]]["ring_inbound_transfer"] = $ring_inbound_transfer;
                $data[$hours[$i] . " - " . $hours[$j]]["inbound_transfer"] = $inbound_transfer;
                $data[$hours[$i] . " - " . $hours[$j]]["hold_inbound_transfer"] = $hold_inbound_transfer;
                $data[$hours[$i] . " - " . $hours[$j]]["ring_outbound_transfer"] = $ring_outbound_transfer;
                $data[$hours[$i] . " - " . $hours[$j]]["hold_outbound_transfer"] = $hold_outbound_transfer;
                $data[$hours[$i] . " - " . $hours[$j]]["desconectado"] = $desconectado;

                $data[$hours[$i] . " - " . $hours[$j]]["diff_inicial"] = $temp_diff_ini;
                $data[$hours[$i] . " - " . $hours[$j]]["diff_final"] = $temp_diff_fin;
                $data[$hours[$i] . " - " . $hours[$j]]["total"] = $total;
                $data[$hours[$i] . " - " . $hours[$j]]["nivel_ocupacion"] = round($total_ocupacion, 2);;
                $data[$hours[$i] . " - " . $hours[$j]]["nivel_ocupacion_backoffice"] = round($total_ocupacion_backoffice, 2);
            } else {
                $data[$hours[$i] . " - " . $hours[0]]["acd"] = $acd;
                $data[$hours[$i] . " - " . $hours[0]]["break"] = $break;
                $data[$hours[$i] . " - " . $hours[0]]["sshh"] = $sshh;
                $data[$hours[$i] . " - " . $hours[0]]["refrigerio"] = $refrigerio;
                $data[$hours[$i] . " - " . $hours[0]]["feedback"] = $feedback;
                $data[$hours[$i] . " - " . $hours[0]]["capacitacion"] = $capacitacion;
                $data[$hours[$i] . " - " . $hours[0]]["backoffice"] = $backoffice;
                $data[$hours[$i] . " - " . $hours[0]]["inbound"] = $inbound;
                $data[$hours[$i] . " - " . $hours[0]]["outbound"] = $outbound;
                $data[$hours[$i] . " - " . $hours[0]]["login"] = $login;
                $data[$hours[$i] . " - " . $hours[0]]["ring_inbound"] = $ring_inbound;
                $data[$hours[$i] . " - " . $hours[0]]["ring_outbound"] = $ring_outbound;
                $data[$hours[$i] . " - " . $hours[0]]["hold_inbound"] = $hold_inbound;
                $data[$hours[$i] . " - " . $hours[0]]["hold_outbound"] = $hold_outbound;
                $data[$hours[$i] . " - " . $hours[0]]["ring_inbound_interno"] = $ring_inbound_interno;
                $data[$hours[$i] . " - " . $hours[0]]["inbound_interno"] = $inbound_interno;
                $data[$hours[$i] . " - " . $hours[0]]["outbound_interno"] = $outbound_interno;
                $data[$hours[$i] . " - " . $hours[0]]["ring_outbound_interno"] = $ring_outbound_interno;
                $data[$hours[$i] . " - " . $hours[0]]["hold_inbound_interno"] = $hold_inbound_interno;
                $data[$hours[$i] . " - " . $hours[0]]["hold_outbound_interno"] = $hold_outbound_interno;
                $data[$hours[$i] . " - " . $hours[0]]["ring_inbound_transfer"] = $ring_inbound_transfer;
                $data[$hours[$i] . " - " . $hours[0]]["inbound_transfer"] = $inbound_transfer;
                $data[$hours[$i] . " - " . $hours[0]]["hold_inbound_transfer"] = $hold_inbound_transfer;
                $data[$hours[$i] . " - " . $hours[0]]["ring_outbound_transfer"] = $ring_outbound_transfer;
                $data[$hours[$i] . " - " . $hours[0]]["hold_outbound_transfer"] = $hold_outbound_transfer;
                $data[$hours[$i] . " - " . $hours[0]]["desconectado"] = $desconectado;

                $data[$hours[$i] . " - " . $hours[0]]["diff_inicial"] = $temp_diff_ini;
                $data[$hours[$i] . " - " . $hours[0]]["diff_final"] = $temp_diff_fin;
                $data[$hours[$i] . " - " . $hours[0]]["total"] = $total;
                $data[$hours[$i] . " - " . $hours[0]]["nivel_ocupacion"] = round($total_ocupacion, 2);
                $data[$hours[$i] . " - " . $hours[0]]["nivel_ocupacion_backoffice"] = round($total_ocupacion_backoffice, 2);
            }
        }
        return $data;
    }

    protected function getDiffDatetime($datetime_ini, $datetime_fin, $onlyTime = false)
    {
        if ($onlyTime) {
            $first = Carbon::parse(Carbon::parse($datetime_ini)->format("H:i:s"));
            $second = Carbon::parse(Carbon::parse($datetime_fin)->format("H:i:s"));
            return $first->diffInSeconds($second);// 10 min
        } else {
            $first = Carbon::parse($datetime_ini);
            $second = Carbon::parse($datetime_ini);
            return $first->diffInSeconds($second);// 10 min
        }
    }

    function getReportByHour($filter)
    {
        if ($filter == '1800') { //reporte por 1/2 hora
            $data = 'return data de 1800';
        } else if ($filter == '3600') { //reporte por 1 hora
            $data = 'return data de 3600';
        } else {
            $data = 'return filtro invalido';
        }
        return $data;
    }

    function generateDateRange($start_date, $end_date)
    {
        $dates = [];
        for ($date = Carbon::parse($start_date); $date->lte(Carbon::parse($end_date)); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }

    function generateTimeRange($start_hour, $end_hour, $minute = 30, $range = true)
    {
        $newtimes = [];
        $times = [];
        for ($time = Carbon::parse($start_hour); $time->lte(Carbon::parse($end_hour)); $time->addMinute($minute)) {
            $times[] = $time->format('H:i:s');
        }
        if ($range) {
            foreach ($times as $k => $v) {
                if (isset($times[$k + 1])) {
                    $newtimes[$k] = $times[$k] . ' - ' . $times[$k + 1];
                } else {
                    $newtimes[$k] = $times[$k] . ' - ' . $times[0];
                }
            }
        } else {
            $newtimes = $times;
        }
        return $newtimes;
    }

    function getReportByDate($min = 60, $option = null,$request = null)
    {
        ini_set('max_execution_time', 300);
        $dates = $this->generateDateRange('2018-03-02', '2018-03-02');
        $times = $this->generateTimeRange('00:00:00', '23:30:00', $min, false);

        $data = [];
        $login = 0;
        $acd = 0;
        $break = 0;
        $sshh = 0;
        $refrigerio = 0;
        $feedback = 0;
        $capacitacion = 0;
        $backoffice = 0;
        $inbound = 0;
        $outbound = 0;
        $ring_inbound = 0;
        $ring_outbound = 0;
        $hold_inbound = 0;
        $hold_outbound = 0;
        $ring_inbound_interno = 0;
        $inbound_interno = 0;
        $outbound_interno = 0;
        $ring_outbound_interno = 0;
        $hold_inbound_interno = 0;
        $hold_outbound_interno = 0;
        $ring_inbound_transfer = 0;
        $ring_outbound_transfer = 0;
        $inbound_transfer = 0;
        $hold_inbound_transfer = 0;
        $hold_outbound_transfer = 0;
        $outbound_transfer = 0;
        $desconectado = 0;
        $events = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28];
        $current_user_id = "";
        $current_range_time = "";

//        if (count($dates)) {
            //Stgart Ciclo Dates
            foreach ($dates as $k => $date) {
                //Variables
                $i = $k;
                $j = $k + 1;
                //Start Ciclo Times
                foreach ($times as $kk => $time) {
                    //Variables
                    $ii = $kk;
                    $jj = $kk + 1;
                    $total = 0;

                    $temp_diff_ini = 0;
                    $temp_diff_fin = 0;
                    $set = false;
                    $last_data = null;

                    $param_user_id = $option["user"] != null ? $option["user"]->id : $request->user_id;

                    if (isset($times[$jj])) {
                        $query = DB::select('CALL SP_GET_REPORT_BY_RANGE_DATE_TIME(?,?,?,?,?)', [$date, $date, $time, $times[$jj], $param_user_id]);
                    } else {
                        $query = DB::select('CALL SP_GET_REPORT_BY_RANGE_DATE_TIME(?,?,?,?,?)', [$date, $date, $time, $times[0], $param_user_id]);
                    }

                    //Start Ciclo Query
                    if (count($query)) {

                        if (isset($times[$jj])) {
                            $range_time = $times[$ii] . " - " . $times[$jj];
                        } else {
                            $range_time = $times[$ii] . " - " . $times[0];
                        }
                        //Ultimo indice
                        $index_final =  (count($query) == 1) ? count($query) : count($query) -1;
                        //Recorrer registros
                        foreach ($query as $kkk => $vvv) {
                            //Variables
                            $iii = $kkk;
                            $jjj = $kkk + 1;

                            //Validar si estamos tratando el mismo usuario
                            if ($option["user"] != null) {
                                if ($vvv->user_id != $current_user_id) {
                                    $data = [];
                                }
                                if ($vvv->user_id == $option["user"]->id) {
                                    $current_user_id = $vvv->user_id;
                                }
                            }
//##
                            if (isset($query[$jjj])) {
                                $diff_total = $this->getDiffDatetime($vvv->date_event, $query[$jjj]->date_event, true);
                            } else {
                                $diff_total = $this->getDiffDatetime($vvv->date_event, $query[0]->date_event, true);
                            }
                            //Primera regla
                            //Si es el primer indice
                            if ($iii == 0) {
                                $h = (new \DateTime($query[$iii]->date_event))->format("H:i:s");
                                if ($h != $times[$ii]) {
                                    $temp_diff_ini = $this->getDiffDatetime($query[$iii]->date_event, $times[$ii], true);
                                }
                            }
                            //Segunda regla
                            //Si es el ultimo indice
                            if ($iii == $index_final) {
                                $h = (new \DateTime($query[$index_final]->date_event))->format("H:i:s");
                                if ($h != $times[$jj]) {
                                    $temp_diff_fin = $this->getDiffDatetime($query[$index_final]->date_event, $times[$jj], true);
                                }
                            }
                            //Recorrer array rango por hora armado
                            for ($g = 0; $g <= count($times); $g++) {
                                //Si es diferente al rango de hora
                                if ($range_time != $current_range_time) {
                                    //Reinicializar estados
                                    $login = 0;
                                    $acd = 0;
                                    $break = 0;
                                    $sshh = 0;
                                    $refrigerio = 0;
                                    $feedback = 0;
                                    $capacitacion = 0;
                                    $backoffice = 0;
                                    $inbound = 0;
                                    $outbound = 0;
                                    $ring_inbound = 0;
                                    $ring_outbound = 0;
                                    $hold_inbound = 0;
                                    $hold_outbound = 0;
                                    $ring_inbound_interno = 0;
                                    $inbound_interno = 0;
                                    $outbound_interno = 0;
                                    $ring_outbound_interno = 0;
                                    $hold_inbound_interno = 0;
                                    $hold_outbound_interno = 0;
                                    $ring_inbound_transfer = 0;
                                    $ring_outbound_transfer = 0;
                                    $inbound_transfer = 0;
                                    $hold_inbound_transfer = 0;
                                    $hold_outbound_transfer = 0;
                                    $outbound_transfer = 0;
                                    $desconectado = 0;
                                    //Reinicializar total
                                    $total = 0;
                                }
                                //Validar si seguimos en el rango de hora
                                if (isset($times[$g + 1])) {
                                    if ($range_time == $times[$g] . " - " . $times[$g + 1]) {
                                        //Set variable con rango de hora actual
                                        $current_range_time = $range_time;
                                    }
                                }
                            }
                            //Validar evento existente
                            $do = false;
                            for ($x = 0; $x <= count($events); $x++) {
                                if ($x == $vvv->evento_id) {
                                    $do = true;
                                    break;
                                } else {
                                    $do = false;
                                }
                            }
                            //Set ultimo indice para recalcular
                            if ($kk == $index_final) {
                                $diff_total = 0;
                                $set = true;
                                $last_data = array_merge(["data" => $vvv], ["range" => $times[$ii] . " - " . $times[$jj]]);
                            } else {
                                $set = false;
                                $last_id = null;
                            }
                            //Validar y Cargar por evento
                            if ($do) {
                                switch ($vvv->evento_id) {
                                    case 1:
                                        $acd += $diff_total;
                                        break;
                                    case 2:
                                        $break += $diff_total;
                                        break;
                                    case 3:
                                        $sshh += $diff_total;
                                        break;
                                    case 4:
                                        $refrigerio += $diff_total;
                                        break;
                                    case 5:
                                        $feedback += $diff_total;
                                        break;
                                    case 6:
                                        $capacitacion += $diff_total;
                                        break;
                                    case 7:
                                        $backoffice += $diff_total;
                                        break;
                                    case 8:
                                        $inbound += $diff_total;
                                        break;
                                    case 9:
                                        $outbound += $diff_total;
                                        break;
                                    case 11:
                                        $login += $diff_total;
                                        break;
                                    case 12:
                                        $ring_inbound += $diff_total;
                                        break;
                                    case 13:
                                        $ring_outbound += $diff_total;
                                        break;
                                    case 15:
                                        $desconectado += $diff_total;
                                        break;
                                    case 16:
                                        $hold_inbound += $diff_total;
                                        break;
                                    case 17:
                                        $hold_outbound += $diff_total;
                                        break;
                                    case 18:
                                        $ring_inbound_interno += $diff_total;
                                        break;
                                    case 19:
                                        $inbound_interno += $diff_total;
                                        break;
                                    case 20:
                                        $outbound_interno += $diff_total;
                                        break;
                                    case 21:
                                        $ring_outbound_interno += $diff_total;
                                        break;
                                    case 22:
                                        $hold_inbound_interno += $diff_total;
                                        break;
                                    case 23:
                                        $hold_outbound_interno += $diff_total;
                                        break;
                                    case 24:
                                        $ring_inbound_transfer += $diff_total;
                                        break;
                                    case 25:
                                        $inbound_transfer += $diff_total;
                                        break;
                                    case 26:
                                        $hold_inbound_transfer += $diff_total;
                                        break;
                                    case 27:
                                        $ring_outbound_transfer += $diff_total;
                                        break;
                                    case 28:
                                        $hold_outbound_transfer += $diff_total;
                                        break;
                                    case 29:
                                        $outbound_transfer += $diff_total;
                                        break;
                                }
                            } else {
                                switch ($vvv->evento_id) {
                                    case 1:
                                        $acd = $diff_total;
                                        break;
                                    case 2:
                                        $break = $diff_total;
                                        break;
                                    case 3:
                                        $sshh = $diff_total;
                                        break;
                                    case 4:
                                        $refrigerio = $diff_total;
                                        break;
                                    case 5:
                                        $feedback = $diff_total;
                                        break;
                                    case 6:
                                        $capacitacion = $diff_total;
                                        break;
                                    case 7:
                                        $backoffice = $diff_total;
                                        break;
                                    case 8:
                                        $inbound = $diff_total;
                                        break;
                                    case 9:
                                        $outbound = $diff_total;
                                        break;
                                    case 11:
                                        $login = $diff_total;
                                        break;
                                    case 12:
                                        $ring_inbound = $diff_total;
                                        break;
                                    case 13:
                                        $ring_outbound = $diff_total;
                                        break;
                                    case 15:
                                        $desconectado = $diff_total;
                                        break;
                                    case 16:
                                        $hold_inbound = $diff_total;
                                        break;
                                    case 17:
                                        $hold_outbound = $diff_total;
                                        break;
                                    case 18:
                                        $ring_inbound_interno = $diff_total;
                                        break;
                                    case 19:
                                        $inbound_interno = $diff_total;
                                        break;
                                    case 20:
                                        $outbound_interno = $diff_total;
                                        break;
                                    case 21:
                                        $ring_outbound_interno = $diff_total;
                                        break;
                                    case 22:
                                        $hold_inbound_interno = $diff_total;
                                        break;
                                    case 23:
                                        $hold_outbound_interno = $diff_total;
                                        break;
                                    case 24:
                                        $ring_inbound_transfer = $diff_total;
                                        break;
                                    case 25:
                                        $inbound_transfer = $diff_total;
                                        break;
                                    case 26:
                                        $hold_inbound_transfer = $diff_total;
                                        break;
                                    case 27:
                                        $ring_outbound_transfer = $diff_total;
                                        break;
                                    case 28:
                                        $hold_outbound_transfer = $diff_total;
                                        break;
                                    case 29:
                                        $outbound_transfer = $diff_total;
                                        break;
                                }
                            }
                            //Calcular total, no sumar los ultimos registros para estabilizar los 30 min
                            if ($kk != $index_final) {
                                $total += $diff_total;
                            }
                        }//Fin ciclo $query

                        //Calcular diferencias temporales
                        //Si tiene temporal inicial Ej: [00:00:00 - 00:30:00] -> 00:10:00 = 10 min
                        if ($temp_diff_ini > 0) {
                            $total = $total + $temp_diff_ini;
                        }
                        //Si tiene temporal fin Ej: [00:00:00 - 00:30:00] -> 00:25:00 = 5 min
                        if ($temp_diff_fin > 0) {
                            $total = $total + $temp_diff_fin;
                        }
                        //Acondicionar los resultados de diferencia por estado
                        if ($set) {
                            if ($last_data != null) {
                                if ($times[$ii] . " - " . $times[$jj] == $last_data["range"]) {
                                    switch ($last_data["data"]->evento_id) {
                                        case 1:
                                            $acd += $temp_diff_fin;
                                            break;
                                        case 2:
                                            $break += $temp_diff_fin;
                                            break;
                                        case 3:
                                            $sshh += $temp_diff_fin;
                                            break;
                                        case 4:
                                            $refrigerio += $temp_diff_fin;
                                            break;
                                        case 5:
                                            $feedback += $temp_diff_fin;
                                            break;
                                        case 6:
                                            $capacitacion += $temp_diff_fin;
                                            break;
                                        case 7:
                                            $backoffice += $temp_diff_fin;
                                            break;
                                        case 8:
                                            $inbound += $temp_diff_fin;
                                            break;
                                        case 9:
                                            $outbound += $temp_diff_fin;
                                            break;
                                        case 11:
                                            $login += $temp_diff_fin;
                                            break;
                                        case 12:
                                            $ring_inbound += $temp_diff_fin;
                                            break;
                                        case 13:
                                            $ring_outbound += $temp_diff_fin;
                                            break;
                                        case 15:
                                            $desconectado += $temp_diff_fin;
                                            break;
                                        case 16:
                                            $hold_inbound += $temp_diff_fin;
                                            break;
                                        case 17:
                                            $hold_outbound += $temp_diff_fin;
                                            break;
                                        case 18:
                                            $ring_inbound_interno += $temp_diff_fin;
                                            break;
                                        case 19:
                                            $inbound_interno += $temp_diff_fin;
                                            break;
                                        case 20:
                                            $outbound_interno += $temp_diff_fin;
                                            break;
                                        case 21:
                                            $ring_outbound_interno += $temp_diff_fin;
                                            break;
                                        case 22:
                                            $hold_inbound_interno += $temp_diff_fin;
                                            break;
                                        case 23:
                                            $hold_outbound_interno += $temp_diff_fin;
                                            break;
                                        case 24:
                                            $ring_inbound_transfer += $temp_diff_fin;
                                            break;
                                        case 25:
                                            $inbound_transfer += $temp_diff_fin;
                                            break;
                                        case 26:
                                            $hold_inbound_transfer += $temp_diff_fin;
                                            break;
                                        case 27:
                                            $ring_outbound_transfer += $temp_diff_fin;
                                            break;
                                        case 28:
                                            $hold_outbound_transfer += $temp_diff_fin;
                                            break;
                                        case 29:
                                            $outbound_transfer += $temp_diff_fin;
                                            break;
                                    }
                                }
                            }
                        }

                    } else {
//
                        $login = 0;
                        $acd = 0;
                        $break = 0;
                        $sshh = 0;
                        $refrigerio = 0;
                        $feedback = 0;
                        $capacitacion = 0;
                        $backoffice = 0;
                        $inbound = 0;
                        $outbound = 0;
                        $ring_inbound = 0;
                        $ring_outbound = 0;
                        $hold_inbound = 0;
                        $hold_outbound = 0;
                        $ring_inbound_interno = 0;
                        $inbound_interno = 0;
                        $outbound_interno = 0;
                        $ring_outbound_interno = 0;
                        $hold_inbound_interno = 0;
                        $hold_outbound_interno = 0;
                        $ring_inbound_transfer = 0;
                        $ring_outbound_transfer = 0;
                        $inbound_transfer = 0;
                        $hold_inbound_transfer = 0;
                        $hold_outbound_transfer = 0;
                        $desconectado = 0;
                        $temp_diff_ini = 0;
                        $temp_diff_fin = 0;
                        $total = 0;
                    }

                    //End Ciclo Query
                    //Calcular Porcentajes
                    $totalACD = $inbound + $hold_inbound;
                    $totalOutbound = $outbound + $ring_outbound + $hold_outbound;
                    $totalBackoffice = $backoffice +
                        $inbound_interno +
                        $ring_inbound_interno +
                        $hold_inbound_interno +
                        $outbound_interno +
                        $ring_outbound_interno +
                        $hold_outbound_interno;
                    $totalAuxiliares = $break + $sshh + $refrigerio + $feedback + $capacitacion;
                    $totalAuxiliaresBack = $totalAuxiliares + $totalBackoffice;
                    $totalSuma = $acd + $break + $sshh + $refrigerio + $feedback + $capacitacion + $backoffice + $inbound + $outbound +
                        $ring_inbound + $ring_outbound + $hold_inbound + $hold_outbound + $ring_inbound_interno + $inbound_interno +
                        $outbound_interno + $ring_outbound_interno + $hold_inbound_interno + $hold_outbound_interno + $ring_inbound_transfer + $inbound_transfer +
                        $hold_inbound_transfer + $ring_outbound_transfer + $hold_outbound_transfer + $outbound_transfer + $desconectado;
                    $tiempoLogeo = $totalSuma - $desconectado;
                    $n1 = ($totalACD + $totalOutbound);
                    $n2 = ($tiempoLogeo - $totalAuxiliaresBack);

                    if ($n1 > 0 && $n2 > 0) {
                        $total_ocupacion = (float)(($totalACD + $totalOutbound) / ($tiempoLogeo - $totalAuxiliaresBack));
                    } else {
                        $total_ocupacion = 0;
                    }
                    $n3 = ($totalACD + $totalOutbound + $totalBackoffice);
                    $n4 = ($tiempoLogeo - $totalAuxiliares);
                    if ($n3 > 0 && $n4 > 0) {
                        $total_ocupacion_backoffice = (float)(($totalACD + $totalOutbound + $totalBackoffice) / ($tiempoLogeo - $totalAuxiliares));
                    } else {
                        $total_ocupacion_backoffice = 0;
                    }

//                    if(count($query)) {
                    if(count($query) == false){
                        $kk = $kk+1;
                    }

                        //Set por rango de hora y estado
                        if (isset($times[$jj])) {
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["acd"] = $acd;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["break"] = $break;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["sshh"] = $sshh;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["refrigerio"] = $refrigerio;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["feedback"] = $feedback;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["capacitacion"] = $capacitacion;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["backoffice"] = $backoffice;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["inbound"] = $inbound;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["outbound"] = $outbound;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["login"] = $login;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["ring_inbound"] = $ring_inbound;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["ring_outbound"] = $ring_outbound;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["hold_inbound"] = $hold_inbound;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["hold_outbound"] = $hold_outbound;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["ring_inbound_interno"] = $ring_inbound_interno;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["inbound_interno"] = $inbound_interno;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["outbound_interno"] = $outbound_interno;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["ring_outbound_interno"] = $ring_outbound_interno;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["hold_inbound_interno"] = $hold_inbound_interno;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["hold_outbound_interno"] = $hold_outbound_interno;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["ring_inbound_transfer"] = $ring_inbound_transfer;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["inbound_transfer"] = $inbound_transfer;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["hold_inbound_transfer"] = $hold_inbound_transfer;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["ring_outbound_transfer"] = $ring_outbound_transfer;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["hold_outbound_transfer"] = $hold_outbound_transfer;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["desconectado"] = $desconectado;

                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["diff_inicial"] = $temp_diff_ini;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["diff_final"] = $temp_diff_fin;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["total"] = $total;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["nivel_ocupacion"] = round($total_ocupacion, 2);;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["nivel_ocupacion_backoffice"] = round($total_ocupacion_backoffice, 2);
                        } else {
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["acd"] = $acd;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["break"] = $break;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["sshh"] = $sshh;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["refrigerio"] = $refrigerio;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["feedback"] = $feedback;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["capacitacion"] = $capacitacion;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["backoffice"] = $backoffice;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["inbound"] = $inbound;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["outbound"] = $outbound;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["login"] = $login;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["ring_inbound"] = $ring_inbound;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["ring_outbound"] = $ring_outbound;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["hold_inbound"] = $hold_inbound;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["hold_outbound"] = $hold_outbound;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["ring_inbound_interno"] = $ring_inbound_interno;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["inbound_interno"] = $inbound_interno;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["outbound_interno"] = $outbound_interno;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["ring_outbound_interno"] = $ring_outbound_interno;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["hold_inbound_interno"] = $hold_inbound_interno;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["hold_outbound_interno"] = $hold_outbound_interno;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["ring_inbound_transfer"] = $ring_inbound_transfer;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["inbound_transfer"] = $inbound_transfer;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["hold_inbound_transfer"] = $hold_inbound_transfer;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["ring_outbound_transfer"] = $ring_outbound_transfer;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["hold_outbound_transfer"] = $hold_outbound_transfer;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["desconectado"] = $desconectado;

                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["diff_inicial"] = $temp_diff_ini;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["diff_final"] = $temp_diff_fin;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["total"] = $total;
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["nivel_ocupacion"] = round($total_ocupacion, 2);
                            $data[$dates[$i]][$times[$ii] . ' - ' . $times[0]]["nivel_ocupacion_backoffice"] = round($total_ocupacion_backoffice, 2);
                        }


//                    }else{
//                        dd($query);
//                        $data[$date][$times[$ii] . ' - ' . $times[$kk]]["acd"] = $acd;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["break"] = $break;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["sshh"] = $sshh;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["refrigerio"] = $refrigerio;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["feedback"] = $feedback;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["capacitacion"] = $capacitacion;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["backoffice"] = $backoffice;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["inbound"] = $inbound;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["outbound"] = $outbound;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["login"] = $login;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["ring_inbound"] = $ring_inbound;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["ring_outbound"] = $ring_outbound;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["hold_inbound"] = $hold_inbound;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["hold_outbound"] = $hold_outbound;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["ring_inbound_interno"] = $ring_inbound_interno;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["inbound_interno"] = $inbound_interno;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["outbound_interno"] = $outbound_interno;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["ring_outbound_interno"] = $ring_outbound_interno;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["hold_inbound_interno"] = $hold_inbound_interno;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["hold_outbound_interno"] = $hold_outbound_interno;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["ring_inbound_transfer"] = $ring_inbound_transfer;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["inbound_transfer"] = $inbound_transfer;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["hold_inbound_transfer"] = $hold_inbound_transfer;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["ring_outbound_transfer"] = $ring_outbound_transfer;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["hold_outbound_transfer"] = $hold_outbound_transfer;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["desconectado"] = $desconectado;
//
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["diff_inicial"] = $temp_diff_ini;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["diff_final"] = $temp_diff_fin;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["total"] = $total;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["nivel_ocupacion"] = round($total_ocupacion, 2);;
//                        $data[$dates[$i]][$times[$ii] . ' - ' . $times[$jj]]["nivel_ocupacion_backoffice"] = round($total_ocupacion_backoffice, 2);
//                    }
                }
                //End Ciclo Times
            }
            //End Ciclo Dates
//        }
//        return $data;
        dd($data);
    }

    function getHtml($data)
    {
//        return $this->getReportByDate(30)
        $suma = 0;
        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<th>Fecha</th>';
        $html .= '<th>Tiempo</th>';
        $html .= '<th>Acd</th>';
        $html .= '<th>Inbound</th>';
        $html .= '</tr>';
        foreach ($data as $k => $date) {
            foreach ($data[$k] as $kk => $time) {
                $html .= '<tr>';
                $html .= '<td>' . $k . '</td>';
                $html .= '<td>' . $kk . '</td>';
                foreach ($time as $kkk => $item) {
//                    $suma += 1;
                    foreach ($item as $value) {
//                        dd($value);
                        $html .= '<td>' . $value->id . '</td>';
                    }
                }
                $html .= '</tr>';
//                $suma = 0;
            }
        }
        $html .= '</table>';
        return  $html;
    }


}