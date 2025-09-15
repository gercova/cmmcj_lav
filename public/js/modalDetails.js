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
        const record = type == 'appointments' ? data.ap : data.ex;
        return `
            <div class="row">
                <div class="col-12">
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th width="70%">DNI: ${hc[0].dni}</th>
                                <th>Fecha: ${formatDate(record.created_at)}</th>
                            </tr>
                        </thead>
                    </table>
                    <p class="text-uppercase"><strong>Nombres y Apellidos:</strong> ${hc[0].nombres}</p>
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
                                        <td>${value.diagnostic}</td>
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
                                </tr>
                            </thead>
                            <tbody>
                                ${medication.map((value, i) => `
                                    <tr>
                                        <td>${i + 1}</td>
                                        <td>${value.drug}</td>
                                        <td>${value.rp || ''}</td>
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
            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button>
            <a class="btn btn-primary pull-right" href="${API_BASE_URL}/${endpoint}/print/${id}" target="_blank">
                <i class="bi bi-file-earmark-pdf"></i> Imprimir
            </a>
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
            const response = await axios.get(`${API_BASE_URL}/${type}/viewDetail/${id}`);
            if (response.status === 200 && response.data) {
                const record = type === 'appointments' ? response.data.ap : response.data.ex;
                $('.modal-title').text(`${titlePrefix} ${record.dni}-${record.id}`);
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
            showDetails({
                id: $(this).attr('value'),
                type: 'appointments',
                titlePrefix: 'Detalles de la Cita'
            });
        });

        $(document).on('click', '.view-exam', function(e) {
            e.preventDefault();
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