// modalDetails.js
const ModalDetails = (function() {
    // Función privada para formatear fecha
    const formatDate = (dateString) => {
        const fecha = new Date(dateString);
        return fecha.toLocaleString('es-ES', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    };
    // Función privada para construir el HTML del modal
    const buildModalContent = (data, type) => {
        const { hc, diagnostic, medication } = data;
        const record = type == 'appointments' ? data.ap : data.exam;
        return `
            <div class="row">
                <div class="col-12">
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th width="70%">DNI: ${hc.dni}</th>
                                <th>Fecha: ${formatDate(record.created_at)}</th>
                            </tr>
                        </thead>
                    </table>
                    <p class="text-uppercase"><strong>Nombres y Apellidos:</strong> ${hc.nombres}</p>
                    <p><strong>Diagnóstico:</strong></p>
                    <div class="col-12" style="float: none; margin: 0 auto;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Diagnóstico</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${diagnostic.map((value, i) => `
                                    <tr>
                                        <td>${i + 1}</td>
                                        <td>${value.codigo + ' - ' + value.diagnostico}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    <p><strong>Receta:</strong></p>
                    <div class="col-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fármaco</th>
                                    <th>Receta</th>
                                    <th>Dosis</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${medication.map((value, i) => `
                                    <tr>
                                        <td>${i + 1}</td>
                                        <td>${value.drug}</td>
                                        <td>${value.rp || ''}</td>
                                        <td>${value.dosis || ''}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
    };
    // Función privada para construir los botones
    const buildModalButtons = (id, type) => {
        const endpoint = type === 'appointments' ? 'appointments' : 'exams';
        return `
            <div class="d-flex justify-content-between align-items-center w-100">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i> Cerrar
                </button>
        
                <div class="text-center">
                    <small class="text-muted d-block mb-1">Formato de impresión</small>
                    <div class="row">
                        <a class="btn btn-primary" href="${API_URL}/sys/${endpoint}/print/${id}/a4" target="_blank">
                            <i class="fas fa-print mr-2"></i> A4 
                            <span class="badge badge-light ml-2">Carta</span>
                        </a>
                        &nbsp;
                        <a class="btn btn-info" href="${API_URL}/sys/${endpoint}/print/${id}/a5" target="_blank">
                            <i class="fas fa-print mr-2"></i> A5
                            <span class="badge badge-light ml-2">Medio</span>
                        </a>
                    </div>
                </div>
            </div>
        `;
    };
    // Función pública para mostrar detalles
    const showDetails = async (options) => {
        const { id, type, selector, titlePrefix } = options;
        // Limpiar el modal
        $('.modal-body').empty();
        $('.modal-footer').empty();
        $('.modal-title').empty();
        try {
            const response = await axios.get(`${API_URL}/sys/${type}/view/${id}`);
            if (response.status === 200 && response.data) {
                const record = type === 'appointments' ? response.data.ap : response.data.exam;
                console.log(record);
                $('.modal-title').text(`${titlePrefix} ${response.data.hc.dni}-${record.id}`);
                $('.modal-body').append(buildModalContent(response.data, type));
                $('.modal-footer').append(buildModalButtons(id, type));
                $('#modal-default').modal('show');
            } else {
                throw new Error('Datos de la respuesta no válidos');
            }
        } catch (error) {
            console.error(`Error al cargar los detalles del ${type}:`, error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `No se pudieron cargar los detalles del ${type}. Por favor, inténtelo de nuevo.`
            });
        }
    };
    // Inicializar listeners
    const init = () => {
        $(document).on('click', '.view-appointment', function(e) {
            e.preventDefault();
            $('.modal-title').html(null);
            $('.modal-body').html(null);
            $('.modal-footer').html(null);
            $('.modal-backdrop').remove();
            showDetails({
                id: $(this).attr('value'),
                type: 'appointments',
                titlePrefix: 'Detalles de la Cita'
            });
        });

        $(document).on('click', '.view-exam', function(e) {
            e.preventDefault();
            $('.modal-backdrop').remove();
            showDetails({
                id: $(this).attr('value'),
                type: 'exams',
                titlePrefix: 'Detalles del Examen'
            });
        });
    };

    return { init, showDetails };
})();
// Inicializar el módulo cuando el DOM esté listo
$(document).ready(function() {
    ModalDetails.init();
});