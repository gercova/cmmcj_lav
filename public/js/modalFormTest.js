const examConfig = {
    'blood': {
        title: 'Examen de Sangre',
        route: {
            view: '/sys/exams/detail/',
            store: '/sys/exams/store-blood'
        },
        fields: [
            'hemoglobina', 'hematocrito', 'leucocitos', 'neutrofilos', 'linfocitos', 'monocitos',
            'eosinofilos', 'basofilos', 'plaquetas', 'glucosa', 'urea', 'creatinina', 'acido_urico',
            'colesterol_total', 'trigliceridos', 'transaminasas_got', 'transaminasas_gpt',
            'bilirrubina_total', 'bilirrubina_directa', 'fosfatasa_alcalina', 'proteinas_totales',
            'albumina', 'globulina', 'sodio', 'potasio', 'cloro', 'calcio', 'vsg',
            'tiempo_protrombina', 'tpt', 'observaciones'
        ],
        hiddenFields: ['examen_sangre_id']
    },
    'urine': {
        title: 'Examen de Orina',
        route: {
            view: '/sys/exams/detail/',
            store: '/sys/exams/store-urine'
        },
        fields: [
            'color', 'aspecto', 'densidad', 'ph', 'proteinas', 'glucosa', 'cetonas', 'bilirrubina',
            'sangre_oculta', 'urobilinogeno', 'nitritos', 'leucocitos_quimico', 'leucocitos_campo',
            'hematies_campo', 'celulas_epiteliales', 'bacterias', 'cristales', 'cilindros', 'mucus', 'observaciones'
        ],
        hiddenFields: ['examen_orina_id']
    },
    'stool': {
        title: 'Examen de Heces',
        route: {
            view: '/sys/exams/detail/',
            store: '/sys/exams/store-stool'
        },
        fields: [
            'consistencia', 'color', 'mucus', 'restos_alimenticios', 'leucocitos', 'hematies',
            'bacterias', 'levaduras', 'parasitos', 'huevos_parasitos', 'sangre_oculta', 'ph',
            'grasa_fecal', 'cultivo_bacteriano', 'sensibilidad_antimicrobiana', 'observaciones'
        ],
        hiddenFields: ['examen_heces_id']
    }
};

function generateExamForm(examType, examData = null) {
    const config = examConfig[examType];
    let formHTML = `
        <form id="dynamicExamForm" autocomplete="off">
            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
            <input type="hidden" name="historia_id" id="historia_id" value="${examData?.historia_id || ''}">
            <input type="hidden" name="examen_id" id="examen_id" value="${examData?.examen_id || ''}">
    `;

    // Agregar campos ocultos específicos
    config.hiddenFields.forEach(field => {
        formHTML += `<input type="hidden" name="${field}" id="${field}" value="${examData?.[field] || ''}">`;
    });

    // Información del usuario y fecha
    formHTML += `
        <div class="row">
            <div class="col-md-8">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Atendido por:</label>
                    <div class="col-sm-8">
                        <input type="text" value="${$('meta[name="user-name"]').attr('content') || 'Usuario'}" class="form-control form-control-sm" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Fecha:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm date" name="created_at" value="${new Date().toISOString().split('T')[0]}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>DNI :: NOMBRES</label>
                    <input type="text" class="form-control form-control-sm" id="patientInfo" readonly>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
    `;

    // Generar campos del formulario según el tipo de examen
    config.fields.forEach(field => {
        const value = examData?.[field] || '';
        const isObservaciones = field === 'observaciones';
        const colClass = isObservaciones ? 'col-md-12' : 'col-md-6';

        formHTML += `
            <div class="${colClass}">
                <div class="form-group">
                    <label for="${field}" class="text-capitalize">${field.replace(/_/g, ' ')}:</label>
                    <input type="${field.includes('observaciones') ? 'text' : 'number'}"
                        step="${field.includes('observaciones') ? '' : '0.01'}"
                        class="form-control form-control-sm"
                        id="${field}"
                        name="${field}"
                        value="${value}">
                </div>
            </div>
        `;
    });

    formHTML += `
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
    `;

    return formHTML;
}

// Función única para manejar agregar exámenes
$(document).on('click', '#btnAddBloodTest, #btnAddUrineTest, #btnAddStoolTest', async function(e) {
    e.preventDefault();

    const examType = this.id.replace('btnAdd', '').replace('Test', '').toLowerCase();
    const examId = $(this).attr('value');
    const config = examConfig[examType];

    try {
        const response = await axios.get(`${API_URL}${config.route.view}${examId}`);
        console.log('Response completo:', response);

        if (response.status === 200) {
            const examData = response.data; // El examen viene directamente en response.data
            console.log('Datos del examen:', examData);

            // Generar y cargar el formulario
            const formHTML = generateExamForm(examType, {
                historia_id: examData.historia_id, // Ahora sí existe
                examen_id: examData.id
            });

            // Actualizar el modal
            $('.modal-title').text(`Agregar ${config.title}`);
            $('.modal-body').html(formHTML);
            $('#patientInfo').val(`${examData.historia.dni} :: ${examData.historia.nombres}`);

            // Mostrar modal
            $('#modal-default').modal('show');
        }
    } catch (error) {
        console.error('Error al cargar el examen:', error);
        alert('Error al cargar los datos del examen');
    }
});

// Función única para manejar editar exámenes
$(document).on('click', '.update-row-bt, .update-row-ut, .update-row-st', async function(e) {
    e.preventDefault();
    const examId        = $(this).attr('value');
    const buttonClass   = $(this).attr('class');
    let examType        = '';
    let apiRoute        = '';

    // Determinar tipo de examen basado en la clase del botón
    if (buttonClass.includes('update-row-bt')) {
        examType = 'blood';
        apiRoute = `${API_URL}/sys/ex-bt/${examId}`;
    } else if (buttonClass.includes('update-row-ut')) {
        examType = 'urine';
        apiRoute = `${API_URL}/sys/ex-ut/${examId}`;
    } else if (buttonClass.includes('update-row-st')) {
        examType = 'stool';
        apiRoute = `${API_URL}/sys/ex-st/${examId}`;
    }

    const config = examConfig[examType];

    try {
        const response = await axios.get(apiRoute);

        if (response.status === 200) {
            const exam = response.data;
            console.log(exam);
            // Generar y cargar el formulario con datos existentes
            const formHTML = generateExamForm(examType, exam);

            // Actualizar el modal
            $('.modal-title').text(`Actualizar ${config.title}`);
            $('.modal-body').html(formHTML);
            $('#patientInfo').val(`${exam.examen.historia.dni || ''} :: ${exam.examen.historia.nombres || ''}`);
            // Mostrar modal
            $('#modal-default').modal('show');
        }
    } catch (error) {
        console.error('Error al cargar el examen:', error);
        alert('Error al cargar los datos del examen');
    }
});

// Manejar el envío del formulario dinámico
$(document).on('submit', '#dynamicExamForm', async function(e) {
    e.preventDefault();
    const formData  = new FormData(this);
    const examId    = $('input[name="examen_id"]').val();
    const buttonId  = $('.btn-primary:focus').attr('id') || '';
    let examType    = '';
    let storeRoute  = '';

    // Determinar tipo de examen basado en los campos presentes
    if (formData.has('examen_sangre_id')) {
        examType = 'blood';
        storeRoute = examConfig.blood.route.store;
    } else if (formData.has('examen_orina_id')) {
        examType = 'urine';
        storeRoute = examConfig.urine.route.store;
    } else if (formData.has('examen_heces_id')) {
        examType = 'stool';
        storeRoute = examConfig.stool.route.store;
    }

    try {
        const response = await axios.post(`${API_URL}${storeRoute}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        if (response.status === 200) {
            $('#modal-default').modal('hide');
            alert(response.data.message || 'Datos guardados correctamente');
            // Recargar la tabla o actualizar la interfaz según sea necesario
            if (typeof reloadExamTable === 'function') {
                reloadExamTable();
            }
        }
    } catch (error) {
        console.error('Error al guardar:', error);
        // Manejar errores de validación
        if (error.response && error.response.status === 422) {
            const errors = error.response.data.errors;
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            for (const field in errors) {
                $(`[name="${field}"]`).addClass('is-invalid');
                $(`[name="${field}"]`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
            }
        } else {
            alert('Error al guardar los datos');
        }
    }
});

// Manejar el envío del formulario dinámico
$(document).on('submit', '#dynamicExamForm', async function(e) {
    e.preventDefault();

    const formData  = new FormData(this);
    const examId    = $('input[name="examen_id"]').val();
    const buttonId  = $('.btn-primary:focus').attr('id') || '';
    let examType    = '';
    let storeRoute  = '';

    // Determinar tipo de examen basado en los campos presentes
    if (formData.has('examen_sangre_id')) {
        examType    = 'blood';
        storeRoute  = examConfig.blood.route.store;
    } else if (formData.has('examen_orina_id')) {
        examType    = 'urine';
        storeRoute  = examConfig.urine.route.store;
    } else if (formData.has('examen_heces_id')) {
        examType    = 'stool';
        storeRoute  = examConfig.stool.route.store;
    }

    try {
        const response = await axios.post(`${API_URL}${storeRoute}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response.status === 200) {
            $('#modal-default').modal('hide');
            alert(response.data.message || 'Datos guardados correctamente');
            // Recargar la tabla o actualizar la interfaz según sea necesario
            if (typeof reloadExamTable === 'function') {
                reloadExamTable();
            }
        }
    } catch (error) {
        console.error('Error al guardar:', error);

        // Manejar errores de validación
        if (error.response && error.response.status === 422) {
            const errors = error.response.data.errors;
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            for (const field in errors) {
                $(`[name="${field}"]`).addClass('is-invalid');
                $(`[name="${field}"]`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
            }
        } else {
            alert('Error al guardar los datos');
        }
    }
});
