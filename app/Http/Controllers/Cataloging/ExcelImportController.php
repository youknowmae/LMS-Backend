<?php

namespace App\Http\Controllers\Cataloging;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use App\Models\Material;
use DateTime, DB, Exception, Date, Storage;
use Log;

class ExcelImportController extends Controller
{
    public function import(Request $request) {

        $validated = $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:2048'
        ]);

        // Access the validated file
        $file = $validated['excel_file'];

        // Move the file to a temporary location
        $filePath = $file->storeAs('temp', $file->getClientOriginalName());

        // Determine the reader type based on the file extension
        $extension = $file->getClientOriginalExtension();
        switch ($extension) {
            case 'xlsx':
                $reader = new Xlsx();
                break;
            case 'xls':
                $reader = new Xls();
                break;
            default:
                throw new Exception('Unsupported file type');
        }

        // Load the file
        $spreadsheet = $reader->load(storage_path('app/' . $filePath));

        $sheet = $spreadsheet->getSheet(0);

        if (!$sheet) {
            return response()->json(['error' => 'Sheet not found'], 400);
        }

        // Process the spreadsheet
        $sheetData = $sheet->toArray(null, true, true, true);

        $headers = $sheetData[1];
        unset($sheetData[1]);
        $failed = [];

        DB::transaction(function() use ($sheetData, $headers, $failed) {

            foreach ($sheetData as $row) {
                $book = new Material();
                $accession = '';
                $title = '';
                $values = [];

                $book->material_type = 0;
                $book->status = 1;
                $book->inventory_status = 0;

                foreach ($headers as $column => $header) {
                    $value = $row[$column];

                    if($value != '') {
                        switch(strtolower($header)) {
                            case 'acc. number':
                                $book->accession = $value;
                                $accession = $value;
                                break;

                            case 'accession number':
                                $book->accession = $value;
                                $accession = $value;
                                break;
                            
                            case 'date received':
                                if (strtolower($value) == 'n.d.') {
                                    break;
                                }
                        
                                // First attempt to parse with 'M. d, Y' format
                                $dateTime = DateTime::createFromFormat('M. d, Y', $value);
                        
                                if (!$dateTime) {
                                    // If parsing fails, replace periods with hyphens and try with 'm-d-Y' format
                                    $value = str_replace('.', '-', $value);
                                    $dateTime = DateTime::createFromFormat('m-d-Y', $value);
                                }
                        
                                if ($dateTime) {
                                    $book->acquired_date = $dateTime->format('Y-m-d');
                                } else {
                                    break; // Skip to the next iteration
                                }
                                break;                                
                            
                            case 'location':
                                $book->location = $value;
                                break;
                            
                            case 'call number':
                                $book->call_number = $value;
                                break;

                            case 'author number':
                                $book->author_number = $value;
                                break;

                            case 'author':
                                $authors_array = [];
                                array_push($authors_array, $value);
                                $book->authors = json_encode($authors_array);
                                break;
                            
                            case 'title':
                                $book->title = $value;
                                $title = $value;
                                break;
        
                            case 'ed.':
                                $book->edition = $value;
                                break;
        
                            case 'edition':
                                $book->edition = $value;
                                break;
        
                            case 'pages':
                                $book->pages = $value;
                                break;
        
                            case 'source of fund':
                                $book->source_of_fund = $value;
                                break;
        
                            case 'price':
                                $cut = 0;
                                if(str_contains($value, 'Php')) {
                                    $cut = 3;
                                } else if(str_contains($value, '₱')) {
                                    $cut = 1;
                                }

                                $value = substr($value, $cut);
                                $book->price = (float)$value;
                                break;
        
                            case 'publisher':
                                $book->publisher = $value;
                                break;
                            
                            case 'copyright':
                                if($value > 1900 && $value <= (new DateTime())->format('Y')) {
                                    $book->copyright = $value;
                                }
                                break;
        
                            default:
                                break;
                        }
                    }
                }

                try {
                    $book->save();
                } catch (Exception $e) {
                    echo $accession;
                    array_push($failed, ['accession' => $accession, 'title' => $title]);
                    continue; // skip saving when it has error
                }
                
                // if(count($values) <= 1)
                //     break;
            }
        });

        Storage::delete($filePath);

        // Return a response
        return response()->json(['message' => 'File uploaded and data imported successfully.',
                                'failed imports' => json_encode($failed)]);
    }
}
