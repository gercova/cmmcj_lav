<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/'.$en->logo) }}">
    <title>Receta Médica - {{ $en->nombre_comercial }}</title>
    <style>
        /* RESET Y ESTILOS GENERALES OPTIMIZADOS */
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
            margin: 0;
            padding: 0;
        }

        /* ENCABEZADO PROFESIONAL */
        .header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 8px 0;
            border-bottom: 2px solid #007bff;
            margin-bottom: 8px;
        }

        .company-info {
            text-align: center;
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
            margin-bottom: 4px;
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

        /* CONTENIDO PRINCIPAL */
        .content {
            padding: 0 12px 60px; /* Espacio para el footer */
        }

        .patient-info {
            background-color: #e3f2fd;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 10px;
            border-left: 4px solid #2196f3;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .patient-info .inline-block {
            display: inline-block;
            margin-right: 15px;
            font-size: 11px;
        }

        .patient-info b {
            color: #1565c0;
        }

        .diagnosis-section {
            padding: 6px;
            background-color: #fff3e0;
            border-radius: 4px;
            border-left: 4px solid #ff9800;
        }

        .diagnosis-section p {
            font-size: 12px;
            margin: 0;
            font-weight: 500;
        }

        /* SECCIÓN DE RECETA MEJORADA */
        .prescription-section h3 {
            font-size: 14px;
            margin: 8px 0 3px 0;
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
            margin-bottom: 3px;
            padding: 3px;
            border-bottom: 1px solid #e0e0e0;
            page-break-inside: avoid;
        }

        .prescription-list li:last-child {
            border-bottom: none;
        }

        .drug-name {
            font-weight: bold;
            color: #2c3e50;
            display: block;
            margin-bottom: 2px;
            font-size: 13px !important;
        }

        .drug-details {
            color: #555;
            font-size: 13px !important;
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
        }
        /* PIE DE PÁGINA FIJADO */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            padding: 6px 0;
            border-top: 2px solid #004085;
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

        /* MEJORAS PARA IMPRESIÓN */
        @media print {
            body {
                font-size: 11px;
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
                -webkit-print-color-adjust: exact;
                position: fixed;
            }
            
            .content {
                padding-bottom: 70px;
            }
        }

        /* UTILIDADES */
        .text-center {
            text-align: center;
        }

        .mb-2 {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <h1>{{ $en->nombre_comercial }}</h1>
            <p class="slogan">{{ $en->slogan }}</p>
            <div class="legal-info">
                <p><b>{{ $en->representante_legal }} - Médico Neumólogo</b></p>
                <p>CMP: 60432 | RNE: 39261</p>
                <p>Dirección: {{ $en->direccion }}, {{ $en->ubigeo }}</p>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="patient-info">
            <p>
                <span class="inline-block"><b>DNI:</b> {{ $hc[0]->dni }}</span>
                <span class="inline-block"><b>Nombres:</b> {{ $hc[0]->nombres }}</span>
                <span class="inline-block"><b>Edad:</b> {{ $hc[0]->edad }} años</span>
                <span class="inline-block"><b>Fecha:</b> {{ \Carbon\Carbon::parse($ex->created_at)->format('d/m/Y H:i') }}</span>
            </p>
        </div>
        
        <div class="diagnosis-section">
            <p><b>Diagnóstico (CIE10):</b> {{ !empty($dx) ? implode(' / ', array_map(function($d) { return $d->codigo; }, $dx)) : 'Sin diagnóstico' }}</p>
        </div>
        
        <div class="prescription-section">
            <h3>Rp.</h3>
            <ol class="prescription-list">
                @foreach($mx as $key => $m)
                    <li>
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
    </div>
    <div class="footer">
        <div class="footer-content">
            <div class="footer-clinic">Centro Médico de la Mujer Corazón de Jesús</div>
            <div class="footer-contact">Atención de lunes a sábado | Tel: {{ $en->telefono }}</div>
        </div>
    </div>
</body>
</html>