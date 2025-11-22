<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/'.$en->logo) }}">
    <title>Reporte de Hospitalización - {{ $en->nombre_comercial }}</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            background: white;
            width: 210mm;
            height: 297mm;
        }

        /* Contenedor A4 con márgenes personalizados */
        .a4-container {
            padding: 5mm 10mm 5mm 10mm; /* Top, Right, Bottom, Left */
            margin: 0 auto;
            position: relative;
            border: none;
        }

        /* ENCABEZADO COMPACTO Y ELEGANTE */
        .header.no-break {
            background: linear-gradient(135deg, #f9fbfd 0%, #edf2f7 100%);
            padding: 6px 8px 4px;
            border-bottom: 1.5px solid #007bff;
            margin-bottom: 10px;
            text-align: center;
            border-radius: 4px 4px 0 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 14px;
            color: #1a365d;
            margin-bottom: 1px;
            font-weight: bold;
            letter-spacing: -0.3px;
        }

        .header .slogan {
            font-size: 9px;
            color: #4a5568;
            margin: 2px 0;
            font-style: italic;
        }

        .header .legal-info p {
            margin: 1px 0;
            font-size: 10px;
            color: #2d3748;
        }

        .header .legal-info b {
            color: #1a202c;
        }

        /* SECCIÓN DE CONTENIDO */
        .content {
            padding: 0;
        }

        .section {
            margin-bottom: 10px;
            padding: 7px 9px;
            border-radius: 4px;
            background-color: #f8fafc;
            border-left: 3px solid #3182ce;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .section h2 {
            font-size: 12px;
            color: #2c5282;
            margin-bottom: 6px;
            padding-bottom: 2px;
            border-bottom: 1px solid #bfdbfe;
        }

        .section-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 7px;
        }

        .field {
            margin-bottom: 3px;
        }

        .field-label {
            font-weight: bold;
            color: #2d3748;
            display: inline-block;
            min-width: 110px;
            vertical-align: top;
        }

        .field-value {
            color: #333;
            word-wrap: break-word;
            display: inline-block;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        /* Estilo para campos largos (como resumen o motivos) */
        .field-value.long-text {
            white-space: pre-line;
            line-height: 1.5;
            font-family: 'Arial', sans-serif;
        }

        /* PIE DE PÁGINA FIJO EN LA PARTE INFERIOR */
        .footer {
            position: absolute;
            bottom: 5mm; /* 0.5 cm desde abajo */
            left: 10mm; /* 1 cm desde la izquierda */
            right: 10mm; /* 1 cm desde la derecha */
            padding: 5px 0;
            text-align: center;
            font-size: 9.5px;
            color: #4a5568;
            border-top: 1px solid #cbd5e0;
        }

        .footer .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 10px;
        }

        .footer-clinic {
            font-weight: bold;
            font-size: 10px;
            color: #1a365d;
        }

        .footer-contact {
            font-size: 9px;
            color: #4a5568;
        }

        /* Evitar saltos de página dentro de bloques importantes */
        .no-break {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Mejora para impresión */
        @media print {
            body, html {
                margin: 0;
                padding: 0;
                size: A4;
            }
            .a4-container {
                padding: 5mm 10mm;
            }
            .footer {
                position: fixed;
            }
        }
    </style>
</head>
<body>
    <div class="a4-container">
        <!-- Encabezado -->
        <div class="header no-break">
            <div class="company-info">
                <h1>{{ $en->nombre_comercial }}</h1>
                <p class="slogan">{{ $en->slogan }}</p>
                <div class="legal-info">
                    <p><b>{{ $en->representante_legal }} - Obstetra</b></p>
                    <p>CMP: 55555 | RNE: 33333</p>
                    <p>Dirección: {{ $en->direccion }}, {{ $en->ubigeo }}</p>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <!-- Información básica -->
            <div class="section no-break">
                <h2>Información General</h2>
                <div class="section-content">
                    <div class="field"><span class="field-label">Historia ID:</span> <span class="field-value">{{ $hsp->historia->dni.$hsp->historia->id }}</span></div>
                    <div class="field"><span class="field-label">Nombre:</span> <span class="field-value">{{ $hsp->historia->nombres }}</span></div>
                    <div class="field"><span class="field-label">Cama:</span> <span class="field-value">{{ $hsp->cama->description }}</span></div>
                    <div class="field"><span class="field-label">Fecha Admisión:</span> <span class="field-value">{{ \Carbon\Carbon::parse($hsp->fecha_admision)->format('d/m/Y') }}</span></div>
                    <div class="field"><span class="field-label">Tipo Admisión ID:</span> <span class="field-value">{{ $hsp->tipoAdmision->nombre }}</span></div>
                    <div class="field"><span class="field-label">Vía Ingreso ID:</span> <span class="field-value">{{ $hsp->viaIngreso->nombre }}</span></div>
                    <div class="field"><span class="field-label">Servicio ID:</span> <span class="field-value">{{ $hsp->servicio->nombre }}</span></div>
                    <div class="field"><span class="field-label">Tipo Cuidado ID:</span> <span class="field-value">{{ $hsp->tipoCuidado->nombre }}</span></div>
                    <div class="field"><span class="field-label">Registrado por:</span> <span class="field-value">{{ $hsp->user->name }}</span></div>
                </div>
            </div>

            <!-- Signos vitales -->
            <div class="section no-break">
                <h2>Signos Vitales</h2>
                <div class="section-content">
                    <div class="field"><span class="field-label">FC:</span> <span class="field-value">{{ $hsp->fc ?? 'N/A' }}</span></div>
                    <div class="field"><span class="field-label">T°:</span> <span class="field-value">{{ $hsp->t ?? 'N/A' }}</span></div>
                    <div class="field"><span class="field-label">SO:</span> <span class="field-value">{{ $hsp->so2 ?? 'N/A' }}</span></div>
                </div>
            </div>

            <!-- Detalles clínicos -->
            <div class="section no-break">
                <h2>Detalles Clínicos</h2>
                <div class="section-content">
                    <div class="field full-width">
                        <span class="field-label">Motivo Hospitalización:</span>
                        <span class="field-value long-text">{{ !empty($hsp->motivo_hospitalizacion) ? $hsp->motivo_hospitalizacion : 'N/A' }}</span>
                    </div>
                    <div class="field full-width">
                        <span class="field-label">Alergias:</span>
                        <span class="field-value long-text">{{ !empty($hsp->alergias) ? $hsp->alergias : 'N/A' }}</span>
                    </div>
                    <div class="field full-width">
                        <span class="field-label">Medicamentos Habituales:</span>
                        <span class="field-value long-text">{{ !empty($hsp->medicamentos_habituales) ? $hsp->medicamentos_habituales : 'N/A' }}</span>
                    </div>
                    <div class="field full-width">
                        <span class="field-label">Antecedentes:</span>
                        <span class="field-value long-text">{{ !empty($hsp->antecedentes_importantes) ? $hsp->antecedentes_importantes : 'N/A' }}</span>
                    </div>
                    <div class="field full-width">
                        <span class="field-label">Resumen Evolución:</span>
                        <span class="field-value long-text">{{ !empty($hsp->resumen_evolucion) ? $hsp->resumen_evolucion : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Egreso -->
            <div class="section no-break">
                <h2>Egreso</h2>
                <div class="section-content">
                    <div class="field"><span class="field-label">Fecha Egreso:</span> <span class="field-value">{{ $hsp->fecha_egreso ? \Carbon\Carbon::parse($hsp->fecha_egreso)->format('d/m/Y') : 'N/A' }}</span></div>
                    <div class="field"><span class="field-label">Tipo Egreso ID:</span> <span class="field-value">{{ $hsp->tipoEgreso->nombre }}</span></div>
                    <div class="field"><span class="field-label">Condición Egreso:</span> <span class="field-value">{{ $hsp->condicionEgreso->nombre ?? 'N/A' }}</span></div>
                    <div class="field full-width"><span class="field-label">Diagnóstico Egreso:</span> <span class="field-value">{{ !empty($hsp->diagnostico_egreso) ? $hsp->diagnostico_egreso : 'N/A' }}</span></div>
                    <div class="field full-width"><span class="field-label">Causa Muerte:</span> <span class="field-value">{{ !empty($hsp->causa_muerte) ? $hsp->causa_muerte : 'N/A' }}</span></div>
                </div>
            </div>

            <!-- Seguro -->
            <div class="section no-break">
                <h2>Seguro Médico</h2>
                <div class="section-content">
                    <div class="field"><span class="field-label">Nro. Autorización:</span> <span class="field-value">{{ !empty($hsp->nro_autorizacion_seguro) ? $hsp->nro_autorizacion_seguro : 'N/A' }}</span></div>
                    <div class="field"><span class="field-label">Aseguradora:</span> <span class="field-value">{{ !empty($hsp->aseguradora) ? $hsp->aseguradora : 'N/A' }}</span></div>
                </div>
            </div>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-clinic">{{ $en->nombre_comercial }}</div>
                <div class="footer-contact">Atención: Lunes - Sábado | Tel: {{ $en->telefono_comercial }}</div>
            </div>
        </div>
    </div>
</body>
</html>