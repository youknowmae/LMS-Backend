<?php

namespace App\Http\Controllers;

use App\Models\Article, App\Models\Book, App\Models\Periodical;
use Dompdf\Dompdf, Dompdf\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Project;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CatalogingReportController extends Controller
{
    public function countMaterials(){
        // for books
        $books = Book::all();
        $titles = $books->unique('title')->count();
        $volumes = $books->count();

        // for periodicals
        $periodicals = Periodical::all();

        $p_count = ['journals' => 0, 'magazines' => 0, 'newspapers' => 0];
        foreach($periodicals as $x){
            if($x->material_type == 'journal')
                $p_count['journals'] = $p_count['journals'] + 1;
            elseif($x->material_type == 'magazine')
                $p_count['magazines'] = $p_count['magazines'] + 1;
            elseif($x->material_type == 'newspaper')
                $p_count['newspapers'] = $p_count['newspapers'] + 1;
        }

        // for articles
        $articles = Article::all()->count();

        $projects = Project::with('program')->get();

        $pr_count = ['ccs' => 0, 'cahs' => 0, 'ceas' => 0, 'chtm' => 0, 'cba' => 0];
        foreach($projects as $x) {
            switch($x->program->department){
                case 'CCS':
                    $pr_count['ccs'] = $pr_count['ccs'] + 1;
                    break;
                
                case 'CAHS':
                    $pr_count['cahs'] = $pr_count['cahs'] + 1;
                    break;

                case 'CEAS':
                    $pr_count['ceas'] = $pr_count['ceas'] + 1;
                    break;

                case 'CHTM':
                    $pr_count['chtm'] = $pr_count['chtm'] + 1;
                    break;

                case 'CBA':
                    $pr_count['cba'] = $pr_count['cba'] + 1;
                    break;

                default:
                    break;
                }
        }

        return [
            'titles' => $titles,
            'volumes' => $volumes,
            'journals' => $p_count['journals'],
            'magazines' => $p_count['magazines'],
            'newspapers' => $p_count['newspapers'],
            'articles' => $articles,
            'ccs' => $pr_count['ccs'],
            'cahs' => $pr_count['cahs'],
            'ceas' => $pr_count['ceas'],
            'chtm' => $pr_count['chtm'],
            'cba' => $pr_count['cba']
        ];
    }

    public function getCount() {
        $counts = $this->countMaterials();
        
        return response()->json([
            'titles' => $counts['titles'],
            'volumes' => $counts['volumes'],
            'journals' => $counts['journals'],
            'magazines' => $counts['magazines'],
            'newspapers' => $counts['newspapers'],
            'articles' => $counts['articles']
        ]);
    }

    public function generatePDF(){

        $dompdf = new Dompdf();

        $counts = $this->countMaterials();
        // Render the HTML as PDF

        $html = view('cataloging.reports', ['counts' => $counts])->render();
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF (DOMPDF will stream it to the browser by default)
        $dompdf->render();

        // Output the generated PDF to Browser
        $date = Carbon::now('Asia/Singapore')->format('Ymd-Hisu');
        return $dompdf->stream($date);
    }

    public function excel(Request $request, string $type) {
        $spreadsheet = new Spreadsheet();

        $startDateParsed = null;
        $endDateParsed = null;

        if($request->startDate)
            $startDateParsed = Carbon::parse($request->startDate);
        if($request->endDate)
            $endDateParsed = Carbon::parse($request->endDate);

        if($startDateParsed) {
            $materials = Book::
            where([
                ['created_at', '>=', $startDateParsed . ' 00:00:00'],
                ['created_at', '<=', $endDateParsed . ' 23:59:59'],
                ['copyright', '=', $request->copyright]
            ])
            ->get()
            ->toArray();
        }

        $material_arr = [];
        foreach($materials as $x) {
            $x_date = Carbon::parse($x['copyright']);
            array_push($material_arr, [
                $x['id'], $x_date->format('m.d.Y'), $x['call_number'], $x['author'], $x['title'],
                $x['edition'], $x['pages'], $x['source_of_fund'],
            ]);
        }

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($material_arr, null, 'A2')
        ->calculateColumnWidths();

        $sheet->setCellValue('A1', "ACC. \nNUMBER")->setCellValue('B1', 'DATE RECEIVED')
        ->setCellValue('C1', 'CALL NUMBER')->setCellValue('D1', 'AUTHOR')->setCellValue('E1', 'TITLE')
        ->setCellValue('F1', 'EDITION')->setCellValue('G1', 'PAGES')->setCellValue('H1', 'SOURCE OF FUND');
        $sheet->fromArray($material_arr, null, 'A2');

        $dimension = $sheet->calculateWorksheetDimension();
        $style = $sheet->getStyle($dimension);
        $font = $style->getFont();
        $font->setSize(11)->setName('Arial');
        $sheet->getStyle($dimension)->getAlignment()->setWrapText(true);

        // Widths 
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $widths = [20, 25, 70, 50, 70, 20, 20, 40];

        foreach (range(0, 7) as $i) {
            $sheet->getColumnDimension($letters[$i])->setWidth($widths[$i]);
            $sheet->getStyle('A1:H1')->getAlignment()->setVertical('center')->setHorizontal('center');
            if($letters[$i] != 'D' && $letters[$i] != 'E')
                $sheet->getStyle($letters[$i] . '1:' . $letters[$i] . $sheet->getHighestRow())->getAlignment()->setHorizontal('center');
        }

        $sheet->getRowDimension(1)->setRowHeight(30);

        $date = Carbon::now('Asia/Singapore')->format('Ymd-Hisu');
        $writer = new Xlsx($spreadsheet);
        $writer->save($date . '.xlsx');

        return response()->download($date . '.xlsx')->deleteFileAfterSend(true);
    }
}
