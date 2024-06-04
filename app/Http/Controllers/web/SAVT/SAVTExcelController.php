<?php


namespace App\Http\Controllers\web\SAVT;

use App\Http\Controllers\Controller;
use App\Models\SAVT;
use App\Traits\Filter;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SAVTExcelController extends Controller
{
    //
    use Filter;


    public function generateSAVTExcel(Request $req)
    {
        try
        {
            $result = SAVT::query();
            $result = $this->filter($result , 'visit_date',$req);

            $result = $result->whereNotNull('visit_date')->select('*', DB::raw('ST_X(geom) as x'), DB::raw('ST_Y(geom) as y'))->get();
            // return $req;
            if ($result)
            {
                $excelFile = public_path('assets/excel-template/SAVT-template.xlsx');
                $spreadsheet = IOFactory::load($excelFile);
                $worksheet = $spreadsheet->getSheet(0);

                $i = 7;
                foreach ($result as $rec)
                {

                    $worksheet->setCellValue('A' . $i, $i - 6);
                    $worksheet->setCellValue('B' . $i, $rec->supplier_pmu_ppu);
                    $worksheet->setCellValue('C' . $i, $rec->supplier_feeder_no);
                    $worksheet->setCellValue('D' . $i, $rec->sec_from);
                    $worksheet->setCellValue('E' . $i,  $rec->sec_to);
                    $worksheet->setCellValue('F' . $i,  $rec->tiang_no);
                    $worksheet->setCellValue('G' . $i, $rec->voltan_kv);
                    $worksheet->setCellValue('H' . $i, $rec->abc_size_mmp);
                    $worksheet->setCellValue('I' . $i, $rec->abc_panjang_meter);
                    // $worksheet->setCellValue('J' . $i, $rec->type);
                    $worksheet->setCellValue('K' . $i, $rec->bare_size_mmp);
                    $worksheet->setCellValue('L' . $i, $rec->bare_panjang_meter);
                    // $worksheet->setCellValue('M' . $i, $rec->type);
                    // $worksheet->setCellValue('N' . $i, $rec->bare_panjang_meter);
                    $worksheet->setCellValue('O' . $i, $rec->underground_cabel_size_mmp);
                    $worksheet->setCellValue('P' . $i, $rec->underground_cabel_length_meter);
                    $worksheet->setCellValue('Q' . $i, $rec->eqp_no_auto_circuit_recloser);
                    $worksheet->setCellValue('R' . $i, $rec->eqp_no_load_break_switch);
                    $worksheet->setCellValue('S' . $i, $rec->eqp_no_isolator_switch);
                    $worksheet->setCellValue('T' . $i, $rec->eqp_no_set_lfi);
                    // $worksheet->setCellValue('U' . $i, $rec->bare_panjang_meter);

                    $i++;
                }


                $secondWorksSheet = $spreadsheet->getSheet(1);

                $i = 7;
                foreach ($result as $rec)
                {

                    $secondWorksSheet->setCellValue('A' . $i, $i - 6);
                    $secondWorksSheet->setCellValue('B' . $i, $rec->tiang_no);

                    $secondWorksSheet->setCellValue('C' . $i, $rec->tiang_rust);
                    $secondWorksSheet->setCellValue('D' . $i, $rec->tiang_leaning);
                    $secondWorksSheet->setCellValue('E' . $i, $rec->tiang_tarikh_repair_done);

                    $secondWorksSheet->setCellValue('G' . $i, $rec->conductor_sagging);
                    $secondWorksSheet->setCellValue('H' . $i, $rec->conductor_torn);
                    $secondWorksSheet->setCellValue('I' . $i, $rec->conductor_broken);
                    $secondWorksSheet->setCellValue('J' . $i, $rec->conductor_hotspot);
                    $secondWorksSheet->setCellValue('K' . $i, $rec->conductor_tarikh_repair_done);

                    $secondWorksSheet->setCellValue('M' . $i, $rec->umbang_sagging);
                    $secondWorksSheet->setCellValue('N' . $i, $rec->umbang_disconnect);
                    $secondWorksSheet->setCellValue('O' . $i, $rec->umbang_tarikh_repair_done);

                    $secondWorksSheet->setCellValue('Q' . $i, $rec->cabel_terminate_crossing);
                    $secondWorksSheet->setCellValue('R' . $i, $rec->cabel_terminate_hotspot);
                    $secondWorksSheet->setCellValue('S' . $i, $rec->cabel_terminate_tarikh_repair_done);

                    $secondWorksSheet->setCellValue('U' . $i, $rec->lightning_arrester_ocp_broken);
                    $secondWorksSheet->setCellValue('V' . $i, $rec->lightning_arrester_ocp_disconnected);
                    $secondWorksSheet->setCellValue('W' . $i, $rec->lightning_arrester_ocp_hot_spot);
                    $secondWorksSheet->setCellValue('X' . $i, $rec->lightning_arrester_ocp_tarikh_repair_done);

                    $secondWorksSheet->setCellValue('Z' . $i, $rec->lightning_arrester_pac_broken);
                    $secondWorksSheet->setCellValue('AA' . $i, $rec->lightning_arrester_pac_disconnected);
                    $secondWorksSheet->setCellValue('AB' . $i, $rec->lightning_arrester_pac_hot_spot);
                    $secondWorksSheet->setCellValue('AC' . $i, $rec->lightning_arrester_pac_tarikh_repair_done);

                    $secondWorksSheet->setCellValue('AE' . $i, $rec->need_rentis);
                    $secondWorksSheet->setCellValue('AF' . $i, $rec->rentis_tarikh_repair_done);

                    $secondWorksSheet->setCellValue('AH' . $i, $rec->switch_link_need_repair);
                    $secondWorksSheet->setCellValue('AI' . $i, $rec->switch_link_need_tarikh_repair_done);

                    $secondWorksSheet->setCellValue('AK' . $i, $rec->tiang_needs_repaint);
                    $secondWorksSheet->setCellValue('AL' . $i, $rec->tiang_needs_repaint_tarikh_repair_done);

                    // $secondWorksSheet->setCellValue('AN' . $i, $rec->umbang_insulation_status);
                    // $secondWorksSheet->setCellValue('AO' . $i, $rec->umbang_insulation_status);

                    $secondWorksSheet->setCellValue('AP' . $i, $rec->earth_bounding_status);
                    $secondWorksSheet->setCellValue('AQ' . $i, $rec->earth_bounding_hotspt);
                    $secondWorksSheet->setCellValue('AR' . $i, $rec->earth_bounding_ultrasound_status);
                    $secondWorksSheet->setCellValue('AS' . $i, $rec->earth_bounding_repair_done);

                    $secondWorksSheet->setCellValue('AU' . $i, $rec->cabel_tray_change);
                    $secondWorksSheet->setCellValue('AV' . $i, $rec->cabel_tray_repair_done_date);

                    $secondWorksSheet->setCellValue('AX' . $i, $rec->suspension_clamp_change);
                    $secondWorksSheet->setCellValue('AY' . $i, $rec->sc_repair_done_date);

                    $secondWorksSheet->setCellValue('BA' . $i, $rec->triangular_braker_change);
                    $secondWorksSheet->setCellValue('BB' . $i, $rec->triangular_braker_repair_done_date);

                    $secondWorksSheet->setCellValue('BD' . $i, $rec->crossarm_rust);
                    $secondWorksSheet->setCellValue('BE' . $i, $rec->crossarm_bent);
                    $secondWorksSheet->setCellValue('BF' . $i, $rec->crossarm_repair_done_date);

                    $secondWorksSheet->setCellValue('BH' . $i, $rec->earth_crossarm_rust);
                    $secondWorksSheet->setCellValue('BI' . $i, $rec->earth_crossarm_bent);
                    $secondWorksSheet->setCellValue('BJ' . $i, $rec->earth_crossarm_repair_done_date);

                    $secondWorksSheet->setCellValue('BL' . $i, $rec->ce_sagging);
                    $secondWorksSheet->setCellValue('BM' . $i, $rec->ce_btc);
                    $secondWorksSheet->setCellValue('BN' . $i, $rec->ce_broken);
                    $secondWorksSheet->setCellValue('BO' . $i, $rec->ce_repair_done_date);

                    $secondWorksSheet->setCellValue('BQ' . $i, $rec->wte_hanging_disconnected);
                    $secondWorksSheet->setCellValue('BR' . $i, $rec->wte_repair_done_date);

                    $secondWorksSheet->setCellValue('BT' . $i, $rec->insulation_flashover);
                    $secondWorksSheet->setCellValue('BU' . $i, $rec->insulation_full);
                    $secondWorksSheet->setCellValue('BV' . $i, $rec->insulation_broken);
                    $secondWorksSheet->setCellValue('BW' . $i, $rec->insulation_hotspot);
                    $secondWorksSheet->setCellValue('BX' . $i, $rec->insulation_defect_repair_date);

                    $secondWorksSheet->setCellValue('BZ' . $i, $rec->lcol_ripped_off);
                    $secondWorksSheet->setCellValue('CA' . $i, $rec->lcol_hotspot);
                    $secondWorksSheet->setCellValue('CB' . $i, $rec->lcol_repair_date);

                    $secondWorksSheet->setCellValue('CD' . $i, $rec->jumper_need_repair);
                    $secondWorksSheet->setCellValue('CE' . $i, $rec->jumper_hotspot);
                    $secondWorksSheet->setCellValue('CF' . $i, $rec->jumper_repair_date);

                    $secondWorksSheet->setCellValue('CH' . $i, $rec->pg_cc_need_change);
                    $secondWorksSheet->setCellValue('CI' . $i, $rec->pg_cc_hotspot);
                    $secondWorksSheet->setCellValue('CJ' . $i, $rec->pg_cc_repair_date);

                    $secondWorksSheet->setCellValue('CL' . $i, $rec->climbing_barrier_need_change);
                    $secondWorksSheet->setCellValue('CM' . $i, $rec->cb_repair_date);

                    $secondWorksSheet->setCellValue('CO' . $i, $rec->arcing_horn_need_repair);
                    $secondWorksSheet->setCellValue('CP' . $i, $rec->ah_repair_done_date);

                    $secondWorksSheet->setCellValue('CR' . $i, $rec->lfi_break);
                    $secondWorksSheet->setCellValue('CS' . $i, $rec->lfi_repair_date);
                    $secondWorksSheet->setCellValue('CU' . $i, $rec->remarks);

                    $i++;
                }


                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                $filename = "savt ( $req->to_date - $req->from_date ) ".rand(2,10000).".xlsx";
                $writer->save(public_path('assets/updated-excels/') . $filename);
                return response()->download(public_path('assets/updated-excels/') . $filename)->deleteFileAfterSend(true);
            }
            else
            {
                return redirect()->back() ->with('failed', 'No records found ');
            }
        }
        catch (\Throwable $th)
        {
            return redirect()->back()->with('failed', 'Request Failed '. $th->getMessage());
        }
    }
}
