<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Importar fuentes comunes si es necesario, pero es mejor usar fuentes estándar -->
    <title>Examen de Orina</title>
    <style>
        @page {
            margin: 10mm;
            size: A4;
        }
        body {
            font-family: Arial, Helvetica, sans-serif; /* Fuente estándar */
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
            border-bottom: 2px solid #0066cc; /* Color del borde */
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
            border-radius: 3px; /* Puede no renderizarse perfectamente en DomPDF */
        }
        .field-label {
            font-weight: bold;
            color: #2d3748;
            display: inline-block;
            min-width: 110px;
            vertical-align: top;
            font-size: 10pt;
        }

        .field-value {
            color: #333;
            word-wrap: break-word;
            display: inline-block;
            font-size: 10pt;
        }
        /* Cambiamos de flex a tablas o divs con display: table */
        .data-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }
        .data-label {
            display: table-cell;
            font-weight: bold;
            width: 160px; /* Ancho fijo */
            color: #2c3e50;
            font-size: 10pt;
            vertical-align: top; /* Alineación superior */
        }
        .data-value {
            display: table-cell;
            padding-left: 6px;
            font-size: 10pt;
            vertical-align: top; /* Alineación superior */
        }
        /* Usamos tablas para los resultados en lugar de grid */
        .results-table {
            width: 100%;
            border-collapse: collapse; /* Junta los bordes de las celdas */
            margin-bottom: 5px;
        }
        .results-table td {
            border: 1px solid #ddd;
            padding: 4px 6px;
            vertical-align: top;
            font-size: 9pt;
        }
        .results-table .label-cell {
            background-color: #f0f0f0; /* Color de fondo para las etiquetas */
            font-weight: bold;
            width: 33%; /* Aproximadamente 1/3 del ancho */
        }
        .results-table .value-cell {
            background-color: #f8f9fa;
        }
        .text-field {
            /* Este estilo se aplica directamente a los valores en la tabla */
            /* padding: 4px 6px; ya está en td */
            min-height: 18px;
            /* font-size: 9pt; ya está en td */
            line-height: 1.2;
        }
        .footer {
            position: fixed; /* DomPDF maneja esto, pero puede ser inconsistente */
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
            /* Cambiamos de flex a tablas */
            display: table;
            width: 100%;
        }
        .footer-clinic, .footer-contact {
            display: table-cell;
            padding: 0 15px;
            vertical-align: middle;
        }
        .footer-clinic {
            text-align: left; /* Alineación a la izquierda */
            font-weight: bold;
            color: #333;
            font-size: 8pt;
        }
        .footer-contact {
            text-align: right; /* Alineación a la derecha */
            font-size: 7pt;
        }

        .no-break {
            page-break-inside: avoid; /* Evita cortes dentro de la sección */
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
    </style>
</head>
<body>
    <div class="header no-break">
        <div class="header-title">{{ $en->nombre_comercial }}</div>
        <div class="header-info">
            <p><b>{{ $en->representante_legal }} - Obstetra</b></p>
            <p>CMP: 55555 | RNE: 33333</p>
            <p>Dirección: {{ $en->direccion }}, {{ $en->ciudad }}</p>
        </div>
    </div>

    <div class="document-title">Examen de Orina</div>

    <div class="section">
        <div class="section-title">Información del Paciente</div>
        <div class="data-row">
            <div class="field"><span class="field-label">Historia ID:</span> <span class="field-value">{{ $ut->historia->dni.$ut->historia->id }}</span></div>
            <div class="field"><span class="field-label">Nombres:</span> <span class="field-value">{{ $ut->historia->nombres }}</span></div>
            <div class="field"><span class="field-label">Registrado:</span> <span class="field-value">{{ $ut->created_at }}</span></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Características Físicas</div>
        <table class="results-table">
            <tr>
                <td class="label-cell">Color:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->color ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Aspecto:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->aspecto ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Densidad:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->densidad ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">pH:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->ph ?? 'N/A' }}</div></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Características Químicas</div>
        <table class="results-table">
            <tr>
                <td class="label-cell">Proteínas:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->proteinas ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Glucosa:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->glucosa ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Cetonas:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->cetonas ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Bilirrubina:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->bilirrubina ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Sangre Oculta:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->sangre_oculta ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Urobilinógeno:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->urobilinogeno ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Nitritos:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->nitritos ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Leucocitos (Químico):</td>
                <td class="value-cell"><div class="text-field">{{ $ut->leucocitos_quimico ?? 'N/A' }}</div></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Sedimento Urinario</div>
        <table class="results-table">
            <tr>
                <td class="label-cell">Leucocitos/Campo:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->leucocitos_campo ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Hematíes/Campo:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->hematies_campo ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Células Epiteliales:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->celulas_epiteliales ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Bacterias:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->bacterias ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Cristales:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->cristales ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Cilindros:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->cilindros ?? 'N/A' }}</div></td>
            </tr>
            <tr>
                <td class="label-cell">Moco:</td>
                <td class="value-cell"><div class="text-field">{{ $ut->mucus ?? 'N/A' }}</div></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Observaciones</div>
        <div class="text-field">
            {{ $ut->observaciones }}
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <div class="footer-clinic">{{ $en->nombre_comercial }}</div>
            <div class="footer-contact">Atención: Lunes - Sábado | Tel: {{ $en->telefono_comercial }}</div>
        </div>
    </div>
</body>
</html>