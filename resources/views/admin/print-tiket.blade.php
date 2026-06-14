<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrean {{ $antrean->nomorantrean }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; margin: 0; padding: 20px; background: #e0e0e0; color: #000; display: flex; justify-content: center; }
        .ticket { background: #fff; width: 300px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .text-xl { font-size: 20px; }
        .text-3xl { font-size: 48px; margin: 10px 0; }
        .border-dashed { border-top: 2px dashed #000; border-bottom: 2px dashed #000; margin: 15px 0; padding: 15px 0; }
        .mb { margin-bottom: 8px; }
        .info { text-align: left; font-size: 14px; line-height: 1.5; margin-bottom: 15px; }
        .print-btn { display: block; margin: 20px auto; padding: 10px 20px; background: #000; color: #fff; border: none; font-weight: bold; cursor: pointer; font-family: inherit; }
        @media print { 
            body { background: #fff; display: block; padding: 0; margin: 0; }
            .ticket { width: 100%; box-shadow: none; padding: 0; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="ticket">
        <div class="center">
            <div class="bold text-xl mb">PUSKESMAS DIGITAL</div>
            <div>Jl. Sehat Selalu No. 123</div>
            
            <div class="border-dashed">
                <div class="mb">NOMOR ANTREAN</div>
                <div class="text-3xl bold">{{ $antrean->nomorantrean }}</div>
                <div class="mb bold">{{ $antrean->jadwal->poli->namapoli }}</div>
                <div>{{ $antrean->jadwal->dokter->namadokter }}</div>
            </div>
            
            <div class="info">
                <div><span class="bold">Tgl:</span> {{ $antrean->jadwal->tanggal->format('d M Y') }}</div>
                <div><span class="bold">Pasien:</span> {{ $antrean->pasien->namapasien }}</div>
                <div><span class="bold">Waktu Daftar:</span> {{ $antrean->waktudaftar->format('H:i') }} WIB</div>
            </div>
            
            <div class="border-dashed">
                <div style="font-size: 12px; font-style: italic;">Silakan menunggu di ruang tunggu poli yang bersangkutan.</div>
            </div>
            
            <div class="bold">Terima Kasih</div>
        </div>
        
        <button class="print-btn" onclick="window.print()">Cetak Tiket</button>
    </div>
</body>
</html>
