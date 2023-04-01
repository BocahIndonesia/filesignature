<?php

namespace App\Mine;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class MyFunction{
    static function matchBytes($file, string $hex, int $offset= 0){
        fseek($file, $offset);
        $hexFile= strtoupper(bin2hex(fread($file, strlen($hex)/2)));
        // fseek($file, 0);

        return $hexFile==strtoupper($hex);
    }
    
    static function validateExtension(UploadedFile $uploadedFile){
        $signatures= DB::table('Signature')
                        ->select('Signature.hex as hex', 'Signature.offset as offset')
                        ->join('Extension', 'Signature.extension_id', '=', 'Extension.id')
                        ->where('Extension.name', '=', $uploadedFile->getClientOriginalExtension())
                        ->orderBy('Signature.offset')
                        ->orderBy(DB::raw('length(Signature.hex)', 'desc'))
                        ->get();

        if($signatures){
            $file= fopen($uploadedFile->path(), 'r');
            foreach($signatures as $signature){
                if(MyFunction::matchBytes($file, $signature->hex, $signature->offset)){
                    fclose($file);
                    return [
                        'validity'=> true,
                        'validSignature'=> $signature,
                        'signatures'=> $signatures
                    ];
                }
            }
        }
        fclose($file);
        return [
            'validity'=> false,
            'validSignature'=> null,
            'signatures'=> null
        ];
    }

    static function validateMime(UploadedFile $uploadedFile){
        $signatures= DB::table('Signature')
                        ->select('Signature.hex as hex', 'Signature.offset as offset')
                        ->join(
                            DB::raw('(
                                SELECT Extension.id AS id, Extension.name AS name, Mime.name AS mime
                                FROM Extension
                                JOIN Mime
                                ON Extension.mime_id=Mime.id
                            ) as Extension'), 'Signature.extension_id', '=', 'Extension.id'
                        )
                        ->where('Extension.mime', '=', $uploadedFile->getMimeType())
                        ->orderBy('Signature.offset')
                        ->orderBy(DB::raw('length(Signature.hex)', 'desc'))
                        ->get();

        if($signatures){
            $file= fopen($uploadedFile->path(), 'r');
            foreach($signatures as $signature){
                if(MyFunction::matchBytes($file, $signature->hex, $signature->offset)){
                    fclose($file);
                    return [
                        'validity'=> true,
                        'validSignature'=> $signature,
                        'signatures'=> $signatures
                    ];
                }
            }
        }
        fclose($file);
        return [
            'validity'=> false,
            'validSignature'=> null,
            'signatures'=> null
        ];
    }
}