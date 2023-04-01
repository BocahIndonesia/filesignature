<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, JsonResponse, UploadedFile};
use Illuminate\Support\Facades\DB;
use App\Models\{Extension, Mime, Signature};
use App\Mine\{MyFunction, MyController};

class ServiceController extends MyController
{
    public function scan(Request $request){
        $report= [];
        foreach($request->file() as $input=>$uploadedFile){
            $extensionReport= MyFunction::validateExtension($uploadedFile);
            $mimeReport= MyFunction::validateMime($uploadedFile);
 
            if($extensionReport['validity'] && $mimeReport['validity']){
                $report[$input]= [
                    'validity'=> true,
                    'validExtension'=> [$uploadedFile->getClientOriginalExtension()],
                    'validMime'=> [$uploadedFile->getMimeType()]
                ];
            }
            else if(!$extensionReport['validity'] && $mimeReport['validity']){
                $report[$input]= [
                    'validity'=> false,
                    'validExtension'=> [DB::table('Extension')
                                        ->select('Extension.name as name')
                                        ->join('Mime', 'Extension.mime_id', '=', 'Mime.id')
                                        ->where('Mime.name', '=', $uploadedFile->getMimeType())
                                        ->first()
                                        ->name],
                    'validMime'=> [$uploadedFile->getMimeType()]
                ];
            }
            else if($extensionReport['validity'] && !$mimeReport['validity']){
                $report[$input]= [
                    'validity'=> false,
                    'validExtension'=> [$uploadedFile->getClientOriginalExtension()],
                    'validMime'=> [DB::table('Mime')
                                ->select('Mime.name as name')
                                ->join('Extension', 'Mime.id', '=', 'Extension.mime_id')
                                ->where('Extension.name', '=', $uploadedFile->getClientOriginalExtension())
                                ->first()
                                ->name]
                ];
            }
            else{
                $report[$input]= [
                    'validity'=> false,
                    'validExtension'=> null,
                    'validMime'=> null
                ];
                $signatures= DB::table('Signature')
                                ->select('Signature.hex as hex', 'Signature.offset as offset', DB::raw('group_concat(DISTINCT Extension.name) as extensions'), DB::raw('group_concat(DISTINCT Extension.mime) as mime'))
                                ->join(
                                    DB::raw('(
                                        SELECT Extension.id AS id, Extension.name AS name, Mime.name AS mime
                                        FROM Extension
                                        JOIN Mime
                                        ON Extension.mime_id=Mime.id
                                    ) as Extension'), 'Signature.extension_id', '=', 'Extension.id'
                                )
                                ->groupBy('Signature.hex', 'Signature.offset')
                                ->orderBy('Signature.offset')
                                ->orderBy(DB::raw('length(Signature.hex)', 'desc'));
                
                $file= fopen($uploadedFile->path(), 'r');
                $signatures->chunk(100, function($signatures) use (&$file, &$report, &$input){
                    foreach($signatures as $signature){
                        if(MyFunction::matchBytes($file, $signature->hex, $signature->offset)){
                            $info= DB::table('Signature')
                                        ->join(
                                            DB::raw('(
                                                SELECT Extension.id AS id, Extension.name AS name, Mime.name AS mime
                                                FROM Extension
                                                JOIN Mime
                                                ON Extension.mime_id=Mime.id
                                            ) as Extension'), 'Signature.extension_id', '=', 'Extension.id'
                                        )
                                        ->where('Signature.hex', '=', $signature->hex)
                                        ->where('Signature.offset', '=', $signature->offset);

                            $report[$input]= [
                                'hex'=> $signature->hex,
                                'validity'=> false,
                                'validExtension'=> $info->select('Extension.name as name')->get()->map(fn($extension)=> $extension->name),
                                'validMime'=> $info->select('Extension.mime as mime')->get()->map(fn($extension)=> $extension->mime)
                            ];
                            return;
                        }
                    }
                });
                fclose($file);
            }
            
            $report[$input]['originalExtension']= $uploadedFile->getClientOriginalExtension();
            $report[$input]['originalMime']= $uploadedFile->getMimeType();
        }

        return new JsonResponse([
            'data'=> $report,
            'message'=> 'gfdgdr ergdfg dr gdgdf'
        ], 200);
    }

    public function validateExtension(Request $request){
        $report= [];
        foreach($request->file() as $input=>$uploadedFile){
            $report[$input]= MyFunction::validateExtension($uploadedFile)['validity'];
        }

        return new JsonResponse([
            'data'=> $report,
            'message'=> 'gfdgdr ergdfg dr gdgdf'
        ], 200);
    }

    public function validateMime(Request $request){
        $report= [];
        foreach($request->file() as $input=>$uploadedFile){
            $report[$input]= MyFunction::validateMime($uploadedFile)['validity'];
        }

        return new JsonResponse([
            'data'=> $report,
            'message'=> 'gfdgdr ergdfg dr gdgdf'
        ], 200);
    }
}
