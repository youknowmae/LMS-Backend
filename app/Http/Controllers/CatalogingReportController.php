<?php

namespace App\Http\Controllers;

use App\Models\Article, App\Models\Book, App\Models\Periodical;
use Dompdf\Dompdf, Dompdf\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CatalogingReportController extends Controller
{
    public function count(){
        // for books
        $titles = Book::get()->groupBy('title')->count();
        $volumes = Book::select('title', 'volume')->distinct()->get()->count();

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

        return response()->json([
            'titles' => $titles,
            'volumes' => $volumes,
            'journals' => $p_count['journals'],
            'magazines' => $p_count['magazines'],
            'newspapers' => $p_count['newspapers'],
            'articles' => $articles
        ]);
    }

    public function generatePDF(){

        $dompdf = new Dompdf();

        // Render the HTML as PDF
        $html = view('welcome')->render();
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF (DOMPDF will stream it to the browser by default)
        $dompdf->render();

        // Output the generated PDF to Browser
        $date = Carbon::now('Asia/Singapore')->format('Ymd-Hisu');
        return $dompdf->stream($date);
    }
}
