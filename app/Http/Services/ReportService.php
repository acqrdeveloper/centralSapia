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
use Illuminate\Support\Facades\DB;

class ReportService
{
    use Utility;

    function reportToJsonService($request=null,$option=null)
    {
        try {

            $hours = [
                "00:00:00",
                "00:30:00",
                "01:00:00",
                "01:30:00",
                "02:00:00",
                "02:30:00",
                "03:00:00",
                "03:30:00",
                "04:00:00",
                "04:30:00",
                "05:00:00",
                "05:30:00",
                "06:00:00",
                "06:30:00",
                "07:00:00",
                "07:30:00",
                "08:00:00",
                "08:30:00",
                "09:00:00",
                "09:30:00",
                "10:00:00",
                "10:30:00",
                "11:00:00",
                "11:30:00",
                "12:00:00",
                "12:30:00",
                "13:00:00",
                "13:30:00",
                "14:00:00",
                "14:30:00",
                "15:00:00",
                "15:30:00",
                "16:00:00",
                "16:30:00",
                "17:00:00",
                "17:30:00",
                "18:00:00",
                "18:30:00",
                "19:00:00",
                "19:30:00",
                "20:00:00",
                "20:30:00",
                "21:00:00",
                "21:30:00",
                "22:00:00",
                "22:30:00",
                "23:00:00",
                "23:30:00"
            ];
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
                $pfecha = $request->pfecha;
                $puser_id = $option["user"] != null ? $option["user"]->id : $request->puser_id;
                $prol = $request->prol;
                //Validar posicion para el rango de horario
                if (isset($hours[$k + 1])) {
//                $query = DB::select("CALL SP_REPORT_30('" . $pfecha_ini . "','" . $pfecha_fin . "','" . $hours[$k] . "','" . $hours[$k + 1] . "','" . $puser_id . "','" . $prol . "'); ");
                    $query = DB::select("CALL SP_REPORT_1800('" . $pfecha . "','" . $hours[$k] . "','" . $hours[$k + 1] . "','" . $puser_id . "','" . $prol . "'); ");
                } else {
//                $query = DB::select("CALL SP_REPORT_30('" . $pfecha_ini . "','" . $pfecha_fin . "','" . $hours[$k] . "','" . $hours[0] . "','" . $puser_id . "','" . $prol . "'); ");
                    $query = DB::select("CALL SP_REPORT_1800('" . $pfecha . "','" . $hours[$k] . "','" . $hours[0] . "','" . $puser_id . "','" . $prol . "'); ");
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
                                $by_user = true;
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
                    $total = 0;
                    $temp_diff_ini = 0;
                    $temp_diff_fin = 0;
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

                    $data[$hours[$i] . " - " . $hours[$j]]["total"] = $total;
                    $data[$hours[$i] . " - " . $hours[$j]]["diff_inicial"] = $temp_diff_ini;
                    $data[$hours[$i] . " - " . $hours[$j]]["diff_final"] = $temp_diff_fin;
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

                    $data[$hours[$i] . " - " . $hours[0]]["total"] = $total;
                    $data[$hours[$i] . " - " . $hours[0]]["diff_inicial"] = $temp_diff_ini;
                    $data[$hours[$i] . " - " . $hours[0]]["diff_final"] = $temp_diff_fin;
                }
            }
            $this->fnSuccess($data);
        } catch (Exception $e) {
            $this->fnErrorException($e);
        }
        return $this->rpta;
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

}