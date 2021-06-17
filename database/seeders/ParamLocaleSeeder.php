<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ParamLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Create a new param 'locales'
        $paramName = 'locales';
        $displayName = [
            ['en' => 'Locales'],
            ['pt-BR' => 'Localizações'],
            ['es' => 'Localizaciones']
        ];
        $this->command->info('Creating Param object ' . strtoupper($paramName));
        $param = \App\Models\Param::firstOrCreate(
            ['name' => $paramName],
            [
                'display_name' => $displayName,
            ]
        );

       
        $codes = ['en', 'pt-BR', 'es'];
        $displayName['en'] = [
            ['en' => 'English'],
            ['pt-BR' => 'Inglês'],
            ['es' => 'Ingles']
        ];
        $displayName['pt-BR'] = [
            ['en' => 'Portuguese'],
            ['pt-BR' => 'Português'],
            ['es' => 'Portugues']
        ];
        $displayName['es'] = [
            ['en' => 'Spanish'],
            ['pt-BR' => 'Espanhol'],
            ['es' => 'Spañol']
        ];

        foreach ($codes as $code) {
            $this->command->info('Creating ParamValues ' . $code . ' objects for ' . strtoupper($paramName));
            $paramValue = \App\Models\ParamValue::firstOrCreate(
                ['code' => $code],
                [
                    'param_id' => $param->id,
                    'name' => $displayName[$code],
                    'symbol' => $code,
                    'color' => null,
                    'is_visible' => true,
                    'is_default' => false,
                ]
            );
        }
    }
}
