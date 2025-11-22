<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen de Hematología</title>
    <style>
        @page {
            margin: 10mm;
            size: A4;
        }
        
        body {
            font-family: Arial, sans-serif;
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
        
        .data-row {
            display: flex;
            margin-bottom: 4px;
            align-items: flex-start;
        }
        
        .data-label {
            font-weight: bold;
            min-width: 160px;
            flex-shrink: 0;
            color: #2c3e50;
            font-size: 10pt;
        }
        
        .data-value {
            flex-grow: 1;
            /*padding-left: 6px;*/
            font-size: 10pt;
        }
        
        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .grid-item {
            margin-bottom: 6px;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9pt;
        }
        
        .results-table th {
            background-color: #34495e;
            color: white;
            padding: 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #34495e;
        }
        
        .results-table td {
            padding: 4px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        .results-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .text-field {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 4px 6px;
            min-height: 18px;
            font-size: 9pt;
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
    </style>
</head>
<body>
    <div class="header no-break">
        <div class="header-title">{{ $en->nombre_comercial }}</div>
        <div class="header-info">
            <p><b>{{ $en->representante_legal }} - Obstetra </b></p>
            <p>CMP: 55555 | RNE: 33333</p>
            <p>Dirección: {{ $en->direccion.', '.$en->ciudad }}</p>
        </div>
    </div>
    <div class="document-title">Examen de Hematología</div>
    <div class="section">
        <div class="section-title">Información General</div>
        <div class="data-row">
            <div class="field"><span class="field-label">Historia ID:</span> <span class="field-value">{{ $bt->historia->dni.$bt->historia->id }}</span></div>
            <div class="field"><span class="field-label">Nombres:</span> <span class="field-value">{{ $bt->historia->nombres }}</span></div>
            <div class="field"><span class="field-label">Registrado:</span> <span class="field-value">{{ $bt->created_at }}</span></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Hemograma Completo</div>
        <table class="results-table">
            <tr>
                <th>Parámetro</th>
                <th>Valor</th>
                <th>Unidad</th>
                <th>Referencia</th>
            </tr>
            <tr>
                <td>Hemoglobina</td>
                <td {{ is_numeric($bt->hemoglobina) ? 
                    ($bt->hemoglobina > 16.0 ? 'style="background-color: #F9C1BE;"' : ($bt->hemoglobina < 12 ? 'style="background-color: #3527F5"' : '') ) : '' }}>{{ $bt->hemoglobina ?? 'N/A' }}</td>
                <td>g/dL</td>
                <td>12.0 - 16.0</td>
            </tr>
            <tr>
                <td>Hematocrito</td>
                <td>{{ $bt->hematocrito ?? 'N/A' }}</td>
                <td>%</td>
                <td>36.0 - 46.0</td>
            </tr>
            <tr>
                <td>Leucocitos</td>
                <td>{{ $bt->leucocitos ?? 'N/A' }}</td>
                <td>x10³/&#181;L</td>
                <td>4.0 - 11.0</td>
            </tr>
            <tr>
                <td>Neutrófilos</td>
                <td>{{ $bt->neutrofilos ?? 'N/A' }}</td>
                <td>%</td>
                <td>40.0 - 75.0</td>
            </tr>
            <tr>
                <td>Linfocitos</td>
                <td>{{ $bt->linfocitos ?? 'N/A' }}</td>
                <td>%</td>
                <td>20.0 - 50.0</td>
            </tr>
            <tr>
                <td>Monocitos</td>
                <td>{{ $bt->monocitos ?? 'N/A' }}</td>
                <td>%</td>
                <td>2.0 - 12.0</td>
            </tr>
            <tr>
                <td>Eosinófilos</td>
                <td>{{ $bt->eosinofilos ?? 'N/A' }}</td>
                <td>%</td>
                <td>1.0 - 6.0</td>
            </tr>
            <tr>
                <td>Basófilos</td>
                <td>{{ $bt->basofilos ?? 'N/A' }}</td>
                <td>%</td>
                <td>0.0 - 2.0</td>
            </tr>
            <tr>
                <td>Plaquetas</td>
                <td>{{ $bt->plaquetas ?? 'N/A' }}</td>
                <td>x10³/&#181;L</td>
                <td>150.0 - 450.0</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Química Sanguínea</div>
        <table class="results-table">
            <tr>
                <th>Parámetro</th>
                <th>Valor</th>
                <th>Unidad</th>
                <th>Referencia</th>
            </tr>
            <tr>
                <td>Glucosa</td>
                <td>{{ $bt->glucosa ?? 'N/A' }}</td>
                <td>mg/dL</td>
                <td>70.0 - 100.0</td>
            </tr>
            <tr>
                <td>Urea</td>
                <td>{{ $bt->urea ?? 'N/A' }}</td>
                <td>mg/dL</td>
                <td>7.0 - 20.0</td>
            </tr>
            <tr>
                <td>Creatinina</td>
                <td>{{ $bt->creatinina ?? 'N/A' }}</td>
                <td>mg/dL</td>
                <td>0.6 - 1.2</td>
            </tr>
            <tr>
                <td>Ácido Úrico</td>
                <td>{{ $bt->acido_urico ?? 'N/A' }}</td>
                <td>mg/dL</td>
                <td>2.4 - 6.0</td>
            </tr>
            <tr>
                <td>Colesterol Total</td>
                <td>{{ $bt->colesterol_total ?? 'N/A' }}</td>
                <td>mg/dL</td>
                <td>< 200.0</td>
            </tr>
            <tr>
                <td>Triglicéridos</td>
                <td>{{ $bt->trigliceridos ?? 'N/A' }}</td>
                <td>mg/dL</td>
                <td>< 150.0</td>
            </tr>
            <tr>
                <td>Transaminasas (GOT)</td>
                <td>{{ $bt->transaminasas_got ?? 'N/A' }}</td>
                <td>U/L</td>
                <td>5.0 - 40.0</td>
            </tr>
            <tr>
                <td>Transaminasas (GPT)</td>
                <td>{{ $bt->transaminasas_gpt ?? 'N/A' }}</td>
                <td>U/L</td>
                <td>7.0 - 56.0</td>
            </tr>
            <tr>
                <td>Bilirrubina Total</td>
                <td>{{ $bt->bilirrubina_total ?? 'N/A' }}</td>
                <td>mg/dL</td>
                <td>0.3 - 1.2</td>
            </tr>
            <tr>
                <td>Bilirrubina Directa</td>
                <td>{{ $bt->bilirrubina_directa ?? 'N/A' }}</td>
                <td>mg/dL</td>
                <td>0.0 - 0.3</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Enzimas y Proteínas</div>
        <table class="results-table">
            <tr>
                <th>Parámetro</th>
                <th>Valor</th>
                <th>Unidad</th>
                <th>Referencia</th>
            </tr>
            <tr>
                <td>Fosfatasa Alcalina</td>
                <td>{{ $bt->fosfatasa_alcalina ?? 'N/A' }}</td>
                <td>U/L</td>
                <td>44.0 - 147.0</td>
            </tr>
            <tr>
                <td>Proteínas Totales</td>
                <td>{{ $bt->proteinas_totales ?? 'N/A' }}</td>
                <td>g/dL</td>
                <td>6.0 - 8.3</td>
            </tr>
            <tr>
                <td>Albúmina</td>
                <td>{{ $bt->albumina ?? 'N/A' }}</td>
                <td>g/dL</td>
                <td>3.5 - 5.0</td>
            </tr>
            <tr>
                <td>Globulina</td>
                <td>{{ $bt->globulina ?? 'N/A' }}</td>
                <td>g/dL</td>
                <td>2.0 - 3.5</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Electrolitos</div>
        <table class="results-table">
            <tr>
                <th>Parámetro</th>
                <th>Valor</th>
                <th>Unidad</th>
                <th>Referencia</th>
            </tr>
            <tr>
                <td>Sodio</td>
                <td>{{ $bt->sodio ?? 'N/A' }}</td>
                <td>mmol/L</td>
                <td>136.0 - 145.0</td>
            </tr>
            <tr>
                <td>Potasio</td>
                <td>{{ $bt->potasio ?? 'N/A' }}</td>
                <td>mmol/L</td>
                <td>3.5 - 5.1</td>
            </tr>
            <tr>
                <td>Cloro</td>
                <td>{{ $bt->cloro ?? 'N/A' }}</td>
                <td>mmol/L</td>
                <td>98.0 - 107.0</td>
            </tr>
            <tr>
                <td>Calcio</td>
                <td>{{ $bt->calcio ?? 'N/A' }}</td>
                <td>mg/dL</td>
                <td>8.5 - 10.2</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Marcadores</div>
        <table class="results-table">
            <tr>
                <th>Parámetro</th>
                <th>Valor</th>
                <th>Unidad</th>
                <th>Referencia</th>
            </tr>
            <tr>
                <td>VSG</td>
                <td>{{ $bt->vsg ?? 'N/A' }}</td>
                <td>mm/h</td>
                <td>0.0 - 20.0</td>
            </tr>
            <tr>
                <td>Tiempo de Protrombina</td>
                <td>{{ $bt->tiempo_protrombina ?? 'N/A' }}</td>
                <td>seg</td>
                <td>11.0 - 13.0</td>
            </tr>
            <tr>
                <td>TPT</td>
                <td>{{ $bt->tpt ?? 'N/A' }}</td>
                <td>seg</td>
                <td>25.0 - 35.0</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Observaciones</div>
        <div class="text-field">
            {{ $bt->observaciones ?? 'N/A' }}
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