<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Recycle Good Issue - {{ $returnGi->return_number }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 14px; 
            line-height: 1.5;
            margin: 40px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h2 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; font-size: 16px; font-weight: bold; }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 25px;
        }
        th, td { 
            border: 1px solid #000; 
            padding: 8px 10px; 
            text-align: left; 
        }
        th { background-color: #f2f2f2; }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 16px;
            text-transform: uppercase;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .signature-box {
            margin-top: 60px;
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
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding:8px 15px;">Cetak Dokumen</button>
        <button onclick="window.close()" style="padding:8px 15px;">Tutup</button>
    </div>

    <div class="header">
        <h2>Bukti Retur Material (Inbound Gudang)</h2>
        <p>{{ $returnGi->return_number }}</p>
    </div>

    <div class="section-title">A. Referensi Dokumen</div>
    <table>
        <tr>
            <th width="25%">No. Work Order (QC)</th>
            <td width="25%">{{ $returnGi->productionQc->wo_number ?? '-' }}</td>
            <th width="25%">Tanggal Retur</th>
            <td width="25%">{{ $returnGi->return_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <th>No. Good Issue Asal</th>
            <td>{{ $returnGi->goodIssue->gi_number ?? '-' }}</td>
            <th>Diinput Oleh</th>
            <td>{{ $returnGi->returner->name ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">B. Rincian Material yang Dikembalikan</div>
    <table>
        <tr>
            <th class="text-center" width="5%">No</th>
            <th>{{ __('app.material.name') }}</th>
            <th width="20%">Kode Item</th>
            <th class="text-right" width="20%">Qty Diterima Gudang</th>
        </tr>
        @forelse($returnGi->items as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $item->material->name ?? '-' }}</td>
            <td>{{ $item->material->code ?? '-' }}</td>
            <td class="text-right" style="font-weight:bold;">
                {{ number_format($item->quantity, 2) }} {{ $item->unit ?? 'Pcs' }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">Tidak ada rincian material</td>
        </tr>
        @endforelse
    </table>

    <div class="section-title">C. Catatan</div>
    <div style="border: 1px solid #000; padding: 10px; min-height: 50px;">
        {{ $returnGi->notes ?: 'Tidak ada catatan.' }}
    </div>

    <div class="signature-box">
        <div class="signature-col">
            Dibuat Oleh / Tim Produksi,
            <div class="signature-line"></div>
            ( {{ $returnGi->returner->name ?? '..................................' }} )
        </div>
        <div class="signature-col">
            Diterima Oleh / Tim Gudang,
            <div class="signature-line"></div>
            ( .................................. )
        </div>
    </div>

</body>
</html>