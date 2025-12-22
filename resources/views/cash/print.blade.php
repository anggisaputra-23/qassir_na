<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Kas</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
        }
        .text-center { text-align: center; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 2px 0; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .minus { color: red; font-weight: bold; }
        .plus { color: green; font-weight: bold; }
        @media print {
            @page { size: 80mm auto; margin: 5px; }
            button { display: none; }
        }
    </style>
</head>
<body>

    <div class="text-center">
        <h3>REKAP KAS</h3>
        <small>{{ \Carbon\Carbon::parse($closing->date)->translatedFormat('d F Y') }}</small><br>
        <small>Kasir: {{ $closing->user->name ?? 'Tidak diketahui' }}</small>
    </div>

    <div class="line"></div>

    <table>
        <tr>
            <td>Kas Awal</td>
            <td class="right">Rp {{ number_format($closing->opening_amount,0,',','.') }}</td>
        </tr>
        <tr>
            <td>Total Omset</td>
            <td class="right">Rp {{ number_format($closing->total_sales,0,',','.') }}</td>
        </tr>
        <tr>
            <td>Omset Tunai</td>
            <td class="right">Rp {{ number_format($closing->total_cash,0,',','.') }}</td>
        </tr>
        <tr>
            <td>Omset Non Tunai</td>
            <td class="right">Rp {{ number_format($closing->total_non_cash,0,',','.') }}</td>
        </tr>
    </table>

    {{-- Detail Pengeluaran --}}
    @if($expenses->count() > 0)
    <div class="line"></div>
    <div><strong>Rincian Pengeluaran:</strong></div>
    <table>
        @foreach($expenses as $exp)
        <tr>
            <td>{{ $exp->expense_name }}</td>
            <td class="right">Rp {{ number_format($exp->amount,0,',','.') }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <div class="line"></div>
    <table>
        <tr>
            <td>Total Pengeluaran</td>
            <td class="right">Rp {{ number_format($closing->total_expenses,0,',','.') }}</td>
        </tr>
        <tr>
            <td><strong>Saldo Tunai Seharusnya</strong></td>
            <td class="right bold">Rp {{ number_format($closing->expected_cash,0,',','.') }}</td>
        </tr>
        <tr>
            <td>Uang Fisik di Laci</td>
            <td class="right">Rp {{ number_format($closing->actual_cash,0,',','.') }}</td>
        </tr>
        <tr>
            <td>Selisih</td>
            <td class="right {{ $closing->difference < 0 ? 'minus' : ($closing->difference > 0 ? 'plus' : 'bold') }}">
                {{ $closing->difference >= 0 ? '+' : '-' }}
                Rp {{ number_format(abs($closing->difference),0,',','.') }}
            </td>
        </tr>
    </table>

    <div class="line"></div>
    <div class="text-center">
        <p>Terima kasih 🙏<br>Sistem POS Qassir_NA</p>
    </div>

    <button onclick="window.print()">🖨 Cetak</button>

</body>
</html>
