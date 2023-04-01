<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Mime, Extension, Signature};
use Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $extensions= json_decode(Storage::disk('public')->get('json/magic.json'), true);

        foreach($extensions as $name=>$info){
            Mime::firstOrCreate([
                'name'=> $info['mime']
            ])->extensions()->create([
                'name'=> $name
            ])->signatures()->createMany(array_values($info['signs']));
        }
    }
}
