<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen de Heces</title>
    <style>
        @page {
            margin: 10mm;
            size: A4;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #0066cc;
        }
        .header-title {
            font-size: 14pt;
            font-weight: bold;
            margin: 0 0 10px 0;
            color: #333;
        }
        .header-info {
            font-size: 9pt;
            margin: 0;
            line-height: 1.4;
            color: #333;
        }
        .header-info p {
            margin: 2px 0;
        }
        .document-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            margin: 10px 0 15px 0;
            color: #2c3e50;
            text-transform: uppercase;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 6px;
        }
        .section {
            margin-bottom: 10px;
        }
        .section-title {
            background-color: #34495e;
            color: white;
            padding: 4px 8px;
            font-size: 9pt;
            font-weight: bold;
            margin: 0 0 6px 0;
            border-radius: 3px;
        }
        /* REEMPLAZAR display:table con una tabla real */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .data-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .data-label {
            font-weight: bold;
            width: 160px;
            color: #2c3e50;
            font-size: 10pt;
            vertical-align: top;
        }
        .data-value {
            padding-left: 6px;
            font-size: 10pt;
            vertical-align: top;
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .results-table td {
            border: 1px solid #ddd;
            padding: 4px 6px;
            vertical-align: top;
            font-size: 9pt;
        }
        .results-table .label-cell {
            background-color: #f0f0f0;
            font-weight: bold;
            width: 33%;
        }
        .results-table .value-cell {
            background-color: #f8f9fa;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
            font-size: 7pt;
            color: #333;
            border-top: 2px solid #0066cc;
            padding-top: 3px;
        }
        .footer-content {
            display: table;
            width: 100%;
        }
        .footer-clinic, .footer-contact {
            display: table-cell;
            padding: 0 15px;
            vertical-align: middle;
        }
        .footer-clinic {
            text-align: left;
            font-weight: bold;
            color: #333;
            font-size: 8pt;
        }
        .footer-contact {
            text-align: right;
            font-size: 7pt;
        }
        .no-break {
            page-break-inside: avoid;
        }
        .signature-section {
            margin-top: 20px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 180px;
            margin: 30px auto 8px auto;
            padding-top: 4px;
        }
        .small-text {
            font-size: 8pt;
        }
        /* Asegurar que las tablas tengan estructura completa */
        table {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header no-break">
        <div class="header-title">{{ $en->nombre_comercial }}</div>
        <div class="header-info">
            <p><b>{{ $en->representante_legal }} - Obstetra</b></p>
            <p>CMP: 55555 | RNE: 33333</p>
            <p>Dirección: {{ $en->direccion.', '.$en->ciudad }}</p>
        </div>
    </div>

    <div class="document-title">Examen de Heces</div>

    <div class="section">
        <div class="section-title">Información General</div>
        <!-- REEMPLAZAR divs con tabla real -->
        <table class="data-table">
            <tr>
                <td class="data-label">Historia ID:</td>
                <td class="data-value">{{ $st->historia->dni.$st->id }}</td>
            </tr>
            <tr>
                <td class="data-label">Nombres:</td>
                <td class="data-value">{{ $st->historia->nombres }}</td>
            </tr>
            <tr>
                <td class="data-label">Registrado:</td>
                <td class="data-value">{{ $st->created_at }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Características Macroscópicas</div>
        <table class="results-table">
            <tr>
                <td class="label-cell">Consistencia:</td>
                <td class="value-cell">{{ $st->consistencia ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Color:</td>
                <td class="value-cell">{{ $st->color ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Moco:</td>
                <td class="value-cell">{{ $st->moco ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Sangre:</td>
                <td class="value-cell">{{ $st->sangre ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Restos Alimenticios:</td>
                <td class="value-cell">{{ $st->restos_alimenticios ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Características Microscópicas</div>
        <table class="results-table">
            <tr>
                <td class="label-cell">Leucocitos:</td>
                <td class="value-cell">{{ $st->leucocitos ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Hematíes:</td>
                <td class="value-cell">{{ $st->hematies ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Bacterias:</td>
                <td class="value-cell">{{ $st->bacterias ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Levaduras:</td>
                <td class="value-cell">{{ $st->levaduras ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Parásitos:</td>
                <td class="value-cell">{{ $st->parasitos ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Huevos de Parásitos:</td>
                <td class="value-cell">{{ $st->huevos_parasitos ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Exámenes Químicos</div>
        <table class="results-table">
            <tr>
                <td class="label-cell">Sangre Oculta:</td>
                <td class="value-cell">{{ $st->sangre_oculta ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">pH:</td>
                <td class="value-cell">{{ $st->ph ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Grasa Fecal:</td>
                <td class="value-cell">{{ $st->grasa_fecal ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Cultivo</div>
        <table class="results-table">
            <tr>
                <td class="label-cell">Cultivo Bacteriano:</td>
                <td class="value-cell">{{ $st->cultivo_bacteriano ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Sensibilidad Antimicrobiana:</td>
                <td class="value-cell">{{ $st->sensibilidad_antimicrobiana ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Observaciones</div>
        <div style="padding: 8px; border: 1px solid #ddd; background-color: #f8f9fa; min-height: 40px;">
            {{ $st->observaciones ?? 'Ninguna observación' }}
        </div>
    </div>

    <!--<div class="signature-section">
        <div class="signature-line"></div>
        <div class="small-text">Firma del Bioquímico</div>
        <div class="small-text">Laboratorio Clínico</div>
    </div>-->

    <div class="footer">
        <div class="footer-content">
            <div class="footer-clinic">{{ $en->nombre_comercial }}</div>
            <div class="footer-contact">Atención: Lunes - Sábado | Tel: {{ $en->telefono ?? '999999999' }}</div>
        </div>
    </div>
</body>
</html>