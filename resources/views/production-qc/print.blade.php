<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Work Order - {{ $productionQc->wo_number }}</title>
    <style>
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 14px; 
            color: #333;
            line-height: 1.5;
            margin: 40px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h2 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; font-size: 16px; font-weight: bold; color: #555; }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 25px;
        }
        th, td { 
            border: 1px solid #999; 
            padding: 10px; 
            text-align: left; 
        }
        th { 
            background-color: #f4f4f4; 
            width: 30%; 
        }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 16px;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bg-light { background-color: #f9f9f9; }
        
        .signature-box {
            margin-top: 50px;
            width: 100%;
            display: table;
        }
        .signature-col {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        .signature-line {
            margin-top: 70px;
            border-bottom: 1px solid #000;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 5px;
        }
        
        /* Hilangkan elemen yang tidak perlu saat di print (contoh tombol) */
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding:10px 20px; cursor:pointer;">Cetak Ulang Dokumen</button>
        <button onclick="window.close()" style="padding:10px 20px; cursor:pointer;">Tutup</button>
    </div>

    <div class="header">
        <h2>Dokumen Work Order (Quality Check)</h2>
        <p>{{ $productionQc->wo_number }}</p>
    </div>

    <div class="section-title">Informasi Referensi</div>
    <table>
        <tr>
            <th>No. Good Issue</th>
            <td>{{ $productionQc->goodIssue->gi_number }}</td>
            <th>Status Dokumen</th>
            <td style="font-weight:bold;">{{ strtoupper($productionQc->status) }}</td>
        </tr>
        <tr>
            <th>Target Part Name</th>
            <td>{{ $productionQc->part->part_name ?? '-' }}</td>
            <th>Tanggal QC</th>
            <td>{{ $productionQc->qc_date->format('d F Y') }}</td>
        </tr>
    </table>

    <div class="section-title">Hasil Produksi (Quality Control)</div>
    <table>
        <tr>
            <th class="text-center" style="width: 70%;">Kategori Hasil</th>
            <th class="text-center" style="width: 30%;">Jumlah / Quantity</th>
        </tr>
        <tr>
            <td>Good (OK) - Menambah Stok Part</td>
            <td class="text-right" style="font-weight:bold;">{{ number_format($productionQc->quantity_passed, 2) }}</td>
        </tr>
        <tr>
            <td>Not Good (NG) - Buang / Scrap</td>
            <td class="text-right">{{ number_format($productionQc->quantity_failed, 2) }}</td>
        </tr>
        <tr>
            <td>Not Good (NG) - Retur Menjadi Material</td>
            <td class="text-right">{{ number_format($productionQc->quantity_failed_retur, 2) }}</td>
        </tr>
        <tr class="bg-light">
            <td class="text-right" style="font-weight:bold;">TOTAL NOT GOOD (NG) KESELURUHAN</td>
            <td class="text-right" style="font-weight:bold; font-size:16px;">{{ number_format($productionQc->total_ng, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">{{ __("app.common.additional_notes") }}</div>
    <div style="border: 1px solid #999; padding: 15px; min-height: 60px; margin-bottom:30px;">
        {{ $productionQc->notes ?: 'Tidak ada catatan' }}
    </div>

    <div class="signature-box">
        <div class="signature-col">
            Dibuat / Diperiksa Oleh,
            <div class="signature-line"></div>
            ( {{ $productionQc->checker->name ?? '..................................' }} )
        </div>
        <div class="signature-col">
            Disetujui Oleh,
            <div class="signature-line"></div>
            ( .................................. )
        </div>
    </div>

</body>
</html>