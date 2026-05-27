<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Material;
use App\Models\Part;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialStockSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================================
        // SUPPLIERS
        // =====================================================================
        $suppliers = [
            // Kode supplier 8 digit (diisi manual sesuai data aktual)
            'S01'     => ['name' => 'CV. MADYA MANDIRI TEKHNIK',         'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'JL. RAYA LAPAN NO.09A RT.012/001 PEKAYON'],
            'S02'     => ['name' => 'PT. MOUNT ZUGSPITE',                'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Kawasan Industri Delta Silikon 2, Bekasi, Jawa Barat'],
            'S03'     => ['name' => 'PT. PANASONIC MANUFACTURING INDONESIA', 'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Jl. Raya Bogor KM.29, Pekayon'],
            'S04'     => ['name' => 'PT. OSEKE UTAMA METAL',             'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Pertokoan Godok Jaya, Jakarta Barat'],
            'S05'     => ['name' => 'PT. TEKNIK MAJU KENCANA',           'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Puri Persada Indah Blok BK-19 RT.009/012, Sindang Mulya'],
            'S07'     => ['name' => 'CV. MAHKOTA',                       'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'JL. Pademangan Raya 3 No. 27 Rt.03 Rw.09, Pademangan Timur'],
            'S10'     => ['name' => 'CV. HASEATECH',                     'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Cibitung'],
            'S11'     => ['name' => 'HARIS TEA',                         'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'JL. Mangga, Kalisari, Pasar Rebo, Jakarta Timur'],
            'S13'     => ['name' => 'CV. DUA SEJAHTERA',                 'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'JL.H.Ir. Juanda, Ruko Juanda Elok No.9, Bulak Kapal, Bekasi'],
            'S14'     => ['name' => 'CV. SUMBER MAKMUR',                 'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Pertokoan Godok Jaya, Jakarta Barat'],
            'S15'     => ['name' => 'PT. CAHAYA MANDALA',                'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'JL. RAYA BOGOR KM. 38 SUKAMAJU'],
            'S16'     => ['name' => 'PT. SURYA MAKMUR SEMPURNA',         'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Kawasan Segitiga Emas JL Samsung 2A BLOK C2.A, Mekarmukti'],
            'S17'     => ['name' => 'PT. GLOBAL DIMENSI METALINDO',      'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'JL. Mitra Karawang II'],
            'S18'     => ['name' => 'PT. NIAGA FAJAR MAKMUR',            'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Puri Persada Indah Blok BK-19 RT.009/012'],
            'S19'     => ['name' => 'PT. MULTI STEEL DLUCH',             'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Komplek Duta Mas Plaza Blok F Taman Cibodas, Tangerang'],
            'S20'     => ['name' => 'PT. GRAPHINDO JAYA PERKASA',        'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Jl. Nangka rt. 04 rw. 05, Sindang Karsa'],
            'S22'     => ['name' => 'PT. BUMI BAUREKSA PRATAMA',         'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'JL. M.H. Thamrin, Plasa Amsterdam, Sentul City, Bogor'],
            'S23'     => ['name' => 'PT. SRIREJEKI PERDANA STEEL',       'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'JL. Pasir Gombong, Cikarang'],
            'S24'     => ['name' => 'PT. ANUGRAH PRESISI INDONESIA',     'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Jl. Daru Blok G5 No. 30B, Delta Silicon V, Lippo Cikarang, Bekasi'],
            'S25'     => ['name' => 'PT. SAMMAT TEKNIK MANDIRI',         'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Jl. Palem Manis Raya, Jatiuwung, Tangerang'],
            'S26'     => ['name' => 'PT. ZAMINDO PUTRA PERKASA',         'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'JL. Zamrud Raya Blok R7 No. 19, Metland Tambun, Bekasi'],
            'S27'     => ['name' => 'CV. MITRA PENSI PRATAMA',           'contact_person' => '-', 'phone' => '-', 'email' => '-', 'address' => 'Kp. Jati, Jatimulya, Tambun Selatan, Bekasi'],
        ];

        foreach ($suppliers as $code => $data) {
            Supplier::firstOrCreate(['code' => $code], array_merge($data, ['is_active' => true]));
        }

        // =====================================================================
        // CUSTOMERS
        // =====================================================================
        $customers = [
            'CUS001' => 'PT.BME',
            'CUS002' => 'PT.BME P3',
            'CUS003' => 'PT. CPL',
            'CUS004' => 'PT. INDTA',
            'CUS005' => 'PT. STALLION',
            'CUS006' => 'PT. YK',
            'CUS007' => 'PT.GDM',
            'CUS008' => 'PT. SGS',
        ];

        foreach ($customers as $code => $name) {
            Customer::firstOrCreate(['code' => $code], [
                'name'           => $name,
                'contact_person' => '-',
                'phone'          => '-',
                'email'          => strtolower(str_replace([' ', '.'], ['', ''], $name)) . '@example.com',
                'address'        => '-',
                'is_active'      => true,
            ]);
        }

        // =====================================================================
        // WAREHOUSES
        // =====================================================================
        $warehouses = [
            'WH01' => ['name' => 'Gudang 1', 'location' => 'Plant 1 Gudang 1'],
            'WH02' => ['name' => 'Gudang 2', 'location' => 'Plant 1 Gudang 2'],
            'WH03' => ['name' => 'Gudang 3', 'location' => 'Plant 1 Gudang 3'],
        ];

        foreach ($warehouses as $code => $data) {
            Warehouse::firstOrCreate(['code' => $code], array_merge($data, ['is_active' => true]));
        }

        // Helper closures
        $sup  = fn($code) => Supplier::where('code', $code)->value('id');
        $cus  = fn($code) => Customer::where('code', $code)->value('id');
        $wh   = fn($code) => Warehouse::where('code', $code)->value('id');
        $proj = fn($name) => DB::table('m_project')->where('name', $name)->value('id');

        // =====================================================================
        // PARTS  (m_parts)
        // Data dari sheet DATABASE file __1_.xlsx
        // =====================================================================
        $parts = [
            // [ part_no, part_name, customer_code, panjang_part, bq ]
            ['2PV-F1585-00',        'ARM, STAY FENDER 1 & 2',          'CUS001', 47.6,  61],
            ['19040-K84-9001-21',   'AS JOINT SHROUD',                  'CUS001', 289.6, 10],
            ['50196-KWWX-6003-HI',  'BAR COMP STD STOPPER',             'CUS001', 150.0, 1],
            ['2PV-XFI41-00',        'BAR CROSS',                        'CUS001', 156.5, 1],
            ['50530-K45-N000-21',   'BAR INNER K45',                    'CUS001', 30.0,  91],
            ['50530-K64 -NA00-20',  'BAR STAND K64J',                   'CUS001', 188.5, 1],
            ['50530-K64 -N000-20',  'BAR STAND K64A',                   'CUS001', 188.5, 1],
            ['46500-K64A-N000-22-BOSS',  'BOSS K64A',                   'CUS001', 18.5,  156],
            ['1FD-F1437-00',        'Boss Main Stand 1',                 'CUS001', 7.8,   185],
            ['1FD-F138-00',         'Boss Main Stand 2',                 'CUS001', 12.8,  127],
            ['90387-064W3',         'Collar 87A1',                      'CUS001', 13.0,  188],
            ['48516-25050-A',       'COLLAR ABSORBER',                  'CUS001', 51.2,  37],
            ['24711-K64J-ND00-22',  'COLLAR K64J',                      'CUS001', null,  null],
            ['50500-KOJA-N002-22',  'COLLAR KOJA',                      'CUS001', 126.0, 47],
            ['50190-KWW0-6000-23',  'COLLAR RR BRAKE PIVOT',            'CUS001', 8.0,   91],
            ['5BP-F5388-00',        'COLLAR SHAFT PULLER CHAIN 5BP',    'CUS001', null,  null],
            ['46500-K64A-N000-22-PDL', 'PEDAL K64A',                   'CUS001', 51.0,  5],
            ['50610-KYZ-9000-27',   'Pin Hook K41',                     'CUS001', 38.0,  73],
            ['50503-KZL-9300-22',   'Pin K1AA',                         'CUS001', 28.0,  97],
            ['50259-KPH-9000-H1',   'Pin Spring hook KPH',              'CUS001', 17.5,  146],
            ['841',                 'Pipa Rod OTL',                     'CUS001', 320.0, 9],
            ['2PV-XF141-00-C',      'Pipe 3',                           'CUS001', null,  null],
            ['50512-KAN-9610-A',    'Pipe Main Stand Pivot',            'CUS001', 122.0, 48],
            ['50530-K81A-N001-21',  'Pipe Stand Inner K81',             'CUS001', 31.0,  88],
            ['50107-K56A-N0006-21', 'Pipe Stand Pivot',                 'CUS001', null,  null],
            ['50351-K0J -N000-28',  'Pipe Link Eng Hanger',             'CUS001', 126.6, 48],
            ['50500-K15-9200-21',   'Pivot Stand Pipe',                 'CUS001', 20.0,  261],
            ['50503-KOJA-N003-20',  'Shaft Koja',                       'CUS001', 184.0, 32],
            ['50526-KVX-6000-A',    'Shaft Main Stand Pivot',           'CUS001', 202.5, 27],
            ['77234-KYZ-9000',      'Spring Seatlock',                  'CUS001', null,  null],
            ['50351-K59-A100-33',   'Bar Eng Stopper',                  'CUS002', null,  null],
            ['50500-K59 -A100-24',  'Collar K2VM/K2SA',                 'CUS002', 134.0, 19],
            ['50351-KZR-6000-21',   'Collar KZR/K59',                   'CUS002', 175.5, 14],
            ['50351-K0J -N000-28B', 'Pipe Link K0J',                    'CUS002', 126.6, 48],
            ['46500-K64A-N000-22B', 'BOSS K64A (CPL)',                  'CUS003', null,  null],
            ['46500-K64A-N000-22C', 'PEDAL K64A (CPL)',                 'CUS003', null,  null],
            ['50500-KOJA-N002-22B', 'Collar KOJJ',                      'CUS004', 126.0, 47],
            ['43122-09G00',         'Boss Brake Pedal',                 'CUS005', 36.0,  97],
            ['SZ106-08029',         'Bolt Lurus',                       'CUS006', 34.5,  null],
            ['SZ106-08030',         'Bolt Tirus',                       'CUS006', 38.5,  null],
            ['52525-EWO21',         'Collar Bumper',                    'CUS006', 5.9,   112],
            // MPR Parts
            ['MC908212',            'PIN HINGE',                        'CUS007', 76.0,  null],
            ['1PA-F4374-00',        'PIN 1PA',                          'CUS007', 60.0,  null],
            ['5D9-F1413-00',        'BAR CROSS TUBE',                   'CUS007', 156.5, null],
            ['51938-VT010',         'LOCK SPARE WHEEL',                 'CUS007', null,  1],
            ['BWP',                 'PIPE HEAD BWP',                    'CUS007', 122.5, 25],
            ['-',                   'TUBE BAR CROSS 1DY',               'CUS007', 129.0, 23],
            ['BWN-F4112-00',        'PIPE CONNECTING BWN',              'CUS007', 51.0,  56],
            ['9004A-17141',         'NUT HEIGHT ROUND',                 'CUS008', 20.0,  1],
            ['9.7588026004E10',     'BOLT ADJUSTER CAB LOCK ROD',       'CUS008', 60.5,  null],
            ['1FD-F612F-00',        'BOSS 1FD',                         'CUS008', 34.0,  1],
            ['BBS-E8115-00',        'ROD SHIFT BBS',                    'CUS008', 263.5, null],
            ['BDJ-E8115-00',        'ROD SHIFT BDJ',                    'CUS008', 163.1, null],
            ['3C1-E9115-00',        'ROD SHIFT 3C1',                    'CUS008', 163.1, null],
            ['1WD-E8115-00',        'ROD SHIFT 1WD',                    'CUS008', 116.5, null],
            ['B3F',                 'SPACER B3F',                       'CUS008', 123.0, 24],
            ['2PKE811500080',       'ROD SHIFT 2PK',                    'CUS008', 247.5, null],
            ['B3M-F1197-00',        'CROSS TUBE 2',                     'CUS008', null,  1],
            ['145P-E818A-00',       'BOSS 1WD',                         'CUS008', 22.0,  116],
        ];

        foreach ($parts as [$part_no, $part_name, $customer_code, $panjang_part, $bq]) {
            Part::firstOrCreate(
                ['part_no' => $part_no, 'part_name' => $part_name],
                [
                    'm_customer_id' => $cus($customer_code),
                    'panjang_part'  => $panjang_part,
                    'bq'            => $bq,
                    'is_active'     => true,
                ]
            );
        }

        // =====================================================================
        // MATERIALS (m_materials)  +  STOCKS (m_stocks)
        // Data dari sheet "Data Stock MMT" dan "Data Stock MPR" file __2_.xlsx
        // =====================================================================

        // Format: [specification, panjang_material, supplier_code, warehouse_code, part_name, customer, bq, cut_per_day, stock, unit, min_stock, max_stock, project_name]
        $materials = [
            // === PROJECT MMT ===
            ['SS400 Ø9.95',             6000, 'S02', 'WH01', 'Shaft Koja',              'BME', 32,  188, 1000, 'Batang', 1880,  3760,  'MMT'],
            ['SS400 Ø8',                3000, 'S02', 'WH02', 'Pin K1AA',                'BME', 96,  275, 250,  'Batang', null,  null,  'MMT'],
            ['SS400 Ø8',                3000, 'S02', 'WH02', 'PIN KPH',                 'BME', 146, 275, 0,    'Batang', null,  null,  'MMT'],
            ['SS400 Ø8',                3000, 'S02', 'WH02', 'PIN K41',                 'BME', 76,  275, 0,    'Batang', null,  null,  'MMT'],
            ['SS400 Ø20',               3000, 'S02', 'WH02', 'PIPE STAND PIVOT K56',    'BME', 18,  null,0,    'Batang', null,  null,  'MMT'],
            ['SS400 Ø19',               6000, 'S02', 'WH02', 'COLLAR KCJ',              'BME', 250, null,0,    'Batang', null,  null,  'MMT'],
            ['STAM290GA Ø20 x Ø15.2',  3000, 'S16', 'WH02', 'BOSS MAIN STAND 1',       'BME', 277, 16,  0,    'Batang', null,  null,  'MMT'],
            ['STAM290GA Ø20 x Ø15.2',  3000, 'S16', 'WH02', 'BOSS MAIN STAND 2',       'BME', 259, 17,  0,    'Batang', null,  null,  'MMT'],
            ['S45C Ø12',                3000, 'S15', 'WH02', 'BOLT LURUS',              'BME', 13,  null,0,    'Batang', null,  null,  'MMT'],
            ['S45C Ø14',                3000, 'S15', 'WH02', 'BOLT TIRUS',              'BME', 94,  null,0,    'Batang', null,  null,  'MMT'],
            ['SS400 Ø28',               6000, 'S15', 'WH02', 'COLLAR 5BP',              'BME', 240, null,0,    'Batang', null,  null,  'MMT'],
            ['SS400 Ø14',               151,  'S15', 'WH01', 'BAR COMP',                'BME', 1,   null,0,    'Batang', null,  null,  'MMT'],
            ['SS400 Ø9.95',             3000, 'S15', 'WH01', 'SHAFT KOJA',              'BME', 16,  260, 0,    'Batang', null,  null,  'MMT'],
            ['STKM11A Ø25.4 x Ø18.05', 3000, 'S16', 'WH01', 'BOSS BREAK PEDAL',        'BME', 75,  null,0,    'Batang', null,  null,  'MMT'],
            ['STAM390GA Ø22.2 x Ø14.5',3000, 'S16', 'WH01', 'BOSS K64',                'BME', 93,  6,   0,    'Batang', null,  null,  'MMT'],
            ['STAM290GA Ø15 x Ø10.2',  6000, 'S16', 'WH02', 'COLLAR KOJA',             'BME', 47,  65,  0,    'Batang', null,  null,  'MMT'],
            ['STKM11A Ø24 x Ø18',      3000, 'S16', 'WH02', 'COLLAR ABSORBER',         'BME', 54,  null,0,    'Batang', null,  null,  'MMT'],
            ['STAM290GA Ø16.65 x Ø12.1',6000,'S16', 'WH02', 'PIPE MAIN STAND PIVOT',   'BME', 46,  40,  0,    'Batang', null,  null,  'MMT'],
            ['STAM390GA Ø17.3 x Ø10.8', 6000,'S16', 'WH02', 'PIPE LINK ENG HANGER',    'BME', 47,  48,  0,    'Batang', null,  null,  'MMT'],
            ['STAM390GA Ø21.7 x Ø16.8', 3000,'S16', 'WH02', 'PIVOT STAND PIPE',        'BME', 130, null,0,    'Batang', null,  null,  'MMT'],
            ['STAM390GA Ø16.7 x Ø12',  6000, 'S16', 'WH02', 'SHAFT MAIN STAND PIVOT',  'BME', 28,  30,  0,    'Batang', null,  null,  'MMT'],
            ['SS400 Ø14',               150,  'S17', 'WH01', 'BAR COMP (API)',           'BME', 1,   null,0,    'Batang', null,  null,  'MMT'],
            ['STKM11AC Ø15.9 x 1.6',   3000, 'S23', 'WH03', 'ARM',                     'BME', 59,  null,0,    'Batang', null,  null,  'MMT'],
            ['STKM11AC Ø15.9 x 1.4',   3000, 'S23', 'WH03', 'PIPE STAND INNER K81',    'BME', 88,  null,0,    'Batang', null,  null,  'MMT'],
            ['STAM290GA Ø25 x Ø17',    245,  'S10', 'WH02', 'BAR STAND K64',           'BME', 1,   null,0,    'Batang', null,  null,  'MMT'],
            ['STKM 11AC Ø50.8 x 2',    3000, 'S10', 'WH02', 'PIPE ROD OTL',            'BME', null,22,  0,    'Batang', null,  null,  'MMT'],
            ['STAM390GA Ø25.4 x t3.2', 1000, 'S18', 'WH03', 'COLLAR RR',               'BME', 90,  20,  0,    'Batang', null,  null,  'MMT'],
            ['STAM390GA Ø17.3 x t2.3', 250,  'S18', 'WH02', 'PEDAL K64',               'BME', 3,   null,0,    'Batang', null,  null,  'MMT'],
            ['STAM290GA Ø15 x 10.2',   3000, 'S18', 'WH01', 'COLLAR K2VM/K2SA',        'BME', null,180, 0,    'Batang', null,  null,  'MMT'],
            ['STAM290GA Ø15 x 10.2',   3000, 'S18', 'WH01', 'COLLAR K29/KZR',          'BME', null,180, 0,    'Batang', null,  null,  'MMT'],
            ['STAM390GA Ø22.2 x 14.5', 3000, 'S18', 'WH01', 'BOSS K64 (SPS)',          'BME', 93,  null,0,    'Batang', null,  null,  'MMT'],
            ['SS400 Ø14',               150,  'S19', 'WH01', 'BAR COMP (MPP)',           'BME', 1,   null,0,    'Batang', null,  null,  'MMT'],

            // === PROJECT MPR ===
            ['SS400 Ø11',              6000, 'S02', 'WH01', 'BOLT ADJUSTER',            'SGS', 91,  null,0,    'Batang', null,  null,  'MPR'],
            ['SS400 Ø7',               6000, 'S02', 'WH02', 'GUIDE',                   'GDM', null,508, 0,    'Batang', null,  null,  'MPR'],
            ['SS400 Ø7',               6000, 'S02', 'WH02', 'EXTENTION',               'GDM', null,508, 0,    'Batang', null,  null,  'MPR'],
            ['SS400 Ø14',              6000, 'S02', 'WH01', 'PIN HINGE',               'SGS', null,null,0,    'Batang', null,  null,  'MPR'],
            ['SS400 Ø9',               6000, 'S02', 'WH01', 'PIN 1PA',                 'GDM', 95,  null,0,    'Batang', null,  null,  'MPR'],
            ['SS400 Ø15',              150,  'S19', 'WH01', 'BAR CROSS TUBE (MPP)',     'GDM', 1,   null,0,    'Batang', null,  null,  'MPR'],
            ['STKM11A Ø22.0 x Ø0.8',  6000, 'S18', 'WH02', 'Inner ES',                'PMI', null,73,  0,    'Batang', null,  null,  'MPR'],
            ['STKM11A Ø38.1 x Ø0.8',  6000, 'S18', 'WH02', 'Outer ES',                'PMI', null,44,  0,    'Batang', null,  null,  'MPR'],
            ['STKM11A Ø22.0 x Ø0.8',  6000, 'S18', 'WH02', 'Inner EL',                'PMI', null,73,  0,    'Batang', null,  null,  'MPR'],
            ['STKM11A Ø38.1 x Ø0.8',  6000, 'S18', 'WH02', 'Outer EL',                'PMI', null,44,  0,    'Batang', null,  null,  'MPR'],
            ['STKM11A Ø22.0 x Ø0.8',  6000, 'S18', 'WH02', 'pipe base a',             'PMI', null,null,0,    'Batang', null,  null,  'MPR'],
            ['STKM11A Ø22.0 x Ø0.8',  6000, 'S18', 'WH02', 'pipe base b',             'PMI', null,null,0,    'Batang', null,  null,  'MPR'],
            ['SS400 Ø16 x 21.5',       21,   'S13', 'WH02', 'NUT HEIGHT GROUND',       'SGS', 1,   null,0,    'Batang', null,  null,  'MPR'],
            ['SS400 Ø15',              157,  'S13', 'WH01', 'BAR CROSS TUBE (MSD)',     'GDM', 1,   null,0,    'Batang', null,  null,  'MPR'],
            ['SS400 Ø16 x 34.5',       34,   'S13', 'WH02', 'BOSS 1FD',                'SGS', 1,   null,0,    'Batang', null,  null,  'MPR'],
            ['S45C Ø6',                3000, 'S13', 'WH01', 'ROD SHIFT',               'SGS', 1,   null,0,    'Batang', null,  null,  'MPR'],
            ['SS400 Ø15',              156,  'S19', 'WH01', 'BAR CROSS TUBE (MPP-SGS)', 'SGS', 1,   null,0,    'Batang', null,  null,  'MPR'],
            ['SS400 Ø16 x 20',         20,   'S19', 'WH02', 'NUT HEIGHT GROUND (MPP)', 'SGS', 1,   null,0,    'Batang', null,  null,  'MPR'],
        ];

        $matCounter = Material::max('id') ?? 0;

        foreach ($materials as [
            $spec, $panjang, $sup_code, $wh_code, $part_name,
            $customer, $bq, $cut_per_day, $stock, $unit,
            $min_stock, $max_stock, $project_name
        ]) {
            $supplierId  = $sup($sup_code);
            $warehouseId = $wh($wh_code);
            $projectId   = $proj($project_name);

            $existing = Material::where('name', $part_name)
                ->where('specification', $spec)
                ->where('project_id', $projectId)
                ->first();

            if ($existing) {
                $material = $existing;
            } else {
                $matCounter++;
                $material = Material::create([
                    'name'             => $part_name,
                    'specification'    => $spec,
                    'project_id'       => $projectId,
                    'm_supplier_id'    => $supplierId,
                    'code'             => 'MAT-' . str_pad($matCounter, 3, '0', STR_PAD_LEFT),
                    'unit'             => $unit ?? 'Batang',
                    'panjang_material' => $panjang,
                    'bq'               => $bq,
                    'cut_per_day'      => $cut_per_day,
                    'is_active'        => true,
                ]);
            }

            // Buat stock
            Stock::firstOrCreate(
                [
                    'm_warehouse_id' => $warehouseId,
                    'm_material_id'  => $material->id,
                ],
                [
                    'current_stock' => $stock ?? 0,
                    'minimum_stock' => $min_stock ?? 0,
                    'max_stock'     => $max_stock ?? 0,
                ]
            );
        }
    }
}