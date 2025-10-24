<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/'.$en->logo) }}">
    <title>Receta Médica - {{ $en->nombre_comercial }}</title>
    <style>
        /* RESET Y ESTILOS GENERALES PARA A4 COMPACTO */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.3;
            width: 210mm;
            height: 297mm;
            background: white;
        }

        /* CONTENEDOR PRINCIPAL A4 CON MÁRGENES REDUCIDOS */
        .a4-container {
            padding: 5mm 10mm 5mm 10mm; /* Top, Right, Bottom, Left */
            position: relative;
        }

        /* ENCABEZADO COMPACTO */
        .header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 8px 0;
            border-bottom: 2px solid #007bff;
            margin-bottom: 12px;
            text-align: center;
            border-radius: 4px;
        }

        .company-info h1 {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 2px;
            font-weight: bold;
        }

        .company-info .slogan {
            font-size: 10px;
            color: #6c757d;
            margin-bottom: 3px;
            font-style: italic;
        }

        .legal-info {
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px solid #dee2e6;
        }

        .legal-info p {
            margin: 1px 0;
            font-size: 9px;
            color: #495057;
        }

        /* CONTENIDO PRINCIPAL COMPACTO */
        .content {
            padding: 0 2mm 20mm 2mm;
        }

        /* INFORMACIÓN DEL PACIENTE COMPACTA */
        .patient-info {
            background-color: #e3f2fd;
            padding: 8px 10px;
            border-radius: 4px;
            margin-bottom: 12px;
            border-left: 4px solid #2196f3;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .patient-info .inline-block {
            display: inline-block;
            margin-right: 15px;
            font-size: 11px;
            min-width: 140px;
        }

        .patient-info b {
            color: #1565c0;
        }

        /* SECCIÓN DIAGNÓSTICO COMPACTA */
        .diagnosis-section {
            padding: 8px;
            background-color: #fff3e0;
            border-radius: 4px;
            border-left: 4px solid #ff9800;
            font-size: 12px;
        }

        .diagnosis-section p {
            margin: 0;
            font-weight: 500;
        }

        /* SECCIÓN DE RECETA COMPACTA */
        .prescription-section h3 {
            font-size: 14px;
            margin: 12px 0 8px 0;
            color: #d32f2f;
            padding-bottom: 3px;
            border-bottom: 2px solid #d32f2f;
            font-weight: bold;
        }

        .prescription-list {
            margin: 0;
            padding-left: 15px;
        }

        .prescription-list li {
            margin-bottom: 5px;
            padding: 6px 8px;
            border-bottom: 1px solid #e0e0e0;
            page-break-inside: avoid;
            background: #fafafa;
            border-radius: 3px;
        }

        .prescription-list li:last-child {
            border-bottom: none;
        }

        .drug-name {
            font-weight: bold;
            color: #2c3e50;
            display: block;
            margin-bottom: 3px;
            font-size: 13px;
        }

        .drug-details {
            color: #555;
            font-size: 11px;
            line-height: 1.4;
        }

        .drug-line {
            display: block;
            margin-bottom: 2px;
        }

        .drug-quantity {
            display: inline-block;
            background-color: #e8f5e9;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            color: #2e7d32;
            margin-top: 3px;
            border: 1px solid #c8e6c9;
            font-size: 10px;
        }

        /* PIE DE PÁGINA COMPACTO */
        .footer {
            position: absolute;
            bottom: 8mm;
            left: 10mm;
            right: 10mm;
            padding: 6px 0;
            border-top: 2px solid #004085;
            border-radius: 0 0 4px 4px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 15px;
        }

        .footer-clinic {
            font-weight: bold;
            font-size: 11px;
        }

        .footer-contact {
            font-size: 10px;
            opacity: 0.9;
        }

        /* FIRMA DEL MÉDICO COMPACTA */
        .signature-section {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px dashed #ccc;
            text-align: right;
            padding-right: 50px;
        }

        .signature-line {
            width: 200px;
            height: 1px;
            background: #333;
            margin: 12px 0 4px auto;
        }

        .doctor-name {
            font-size: 11px;
            font-weight: bold;
            color: #2c3e50;
        }

        .doctor-credentials {
            font-size: 9px;
            color: #666;
        }

        /* INSTRUCCIONES Y OBSERVACIONES */
        .instructions-section {
            margin-top: 15px;
            padding: 8px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 3px solid #6c757d;
            font-size: 10px;
        }

        .instructions-section h4 {
            font-size: 11px;
            margin-bottom: 4px;
            color: #495057;
        }

        /* MEJORAS PARA IMPRESIÓN A4 COMPACTA */
        @media print {
            body {
                width: 210mm;
                height: 297mm;
                font-size: 11px;
                margin: 0;
                padding: 0;
            }
            
            .a4-container {
                width: 210mm;
                height: 297mm;
                padding: 8mm 10mm 15mm 10mm;
                margin: 0;
            }
            
            .header {
                background: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
            }
            
            .patient-info {
                background: #e3f2fd !important;
                -webkit-print-color-adjust: exact;
            }
            
            .footer {
                background: #007bff !important;
                -webkit-print-color-adjust: exact;
                position: absolute;
            }
            
            .prescription-list li {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }

        /* UTILIDADES COMPACTAS */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .compact-text {
            font-size: 10px;
            line-height: 1.2;
        }

        /* EVITAR SALTO DE PÁGINA */
        .no-break {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* GRID COMPACTO PARA MEJOR DISTRIBUCIÓN */
        .compact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 10px;
        }

        .grid-item {
            padding: 6px;
            background: #f8f9fa;
            border-radius: 3px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="a4-container">
        <div class="header no-break">
            <div class="company-info">
                <h1>{{ $en->nombre_comercial }}</h1>
                <p class="slogan">{{ $en->slogan }}</p>
                <div class="legal-info">
                    <p><b>{{ $en->representante_legal }} - Médico Neumólogo</b></p>
                    <p>CMP: 60432 | RNE: 39261</p>
                    <p class="compact-text">Dirección: {{ $en->direccion }}, {{ $en->ubigeo }}</p>
                </div>
            </div>
        </div>

        <div class="content">
            <!-- Información del paciente más compacta -->
            <div class="compact-grid no-break">
                <div class="grid-item">
                    <b>DNI:</b> {{ $hc[0]->dni }}
                    <b>Paciente:</b> {{ $hc[0]->nombres }}
                    <b>Edad:</b> {{ $hc[0]->edad }} años
                </div>
                <div class="grid-item">
                    <b>Fecha:</b> {{ \Carbon\Carbon::parse($ex->created_at)->format('d/m/Y H:i') }}
                </div>
            </div>
            
            <div class="diagnosis-section no-break">
                <p><b>Diagnóstico:</b> {{ !empty($dx) ? implode(' / ', array_map(function($d) { return $d->diagnostic; }, $dx)) : 'Sin diagnóstico' }}</p>
            </div>
            
            <div class="prescription-section">
                <h3>RECETA MÉDICA</h3>
                <ol class="prescription-list">
                    @foreach($mx as $key => $m)
                        <li class="no-break">
                            <span class="drug-name">{{ $m->drug }}</span>
                            <div class="drug-details">
                                @if (!empty($m->rp))
                                    <span class="drug-line">{{ $m->rp }}</span>
                                @endif
                                @if (!empty($m->quantity))
                                    <span class="drug-quantity"><strong>Cant:</strong> {{ $m->quantity }}</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

            <!-- Sección de instrucciones opcional -->
            <div class="instructions-section no-break">
                <h4>Instrucciones Generales:</h4>
                <p class="compact-text">• Cumplir con el tratamiento completo • No automedicarse • Asistir a controles programados • Consultar en caso de efectos adversos</p>
            </div>
        </div>

        <div class="footer">
            <div class="footer-content">
                <div class="footer-clinic">Clínica Rodriguez y Especialistas II</div>
                <div class="footer-contact">Atención: Lunes - Sábado | Tel: {{ $en->telefono }}</div>
            </div>
        </div>
    </div>
</body>
</html>