const DeleteHandler = (() => {
    const config = {
        baseUrl: API_URL,
        confirmText: '¿Estás seguro?',
        confirmDeleteText: 'Este registro será eliminado',
        btnConfirmText: 'Sí, eliminar',
        btnCancelText: 'Cancelar'
    };

    const deleteItem = async (options = {}) => {
        const { 
            id, 
            endpoint, 
            table = null, 
            customMessage = null,
            onSuccess = null, 
            onError = null 
        } = options;

        try {
            const result = await Swal.fire({
                title: customMessage?.title || config.confirmText,
                text: customMessage?.text || config.confirmDeleteText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: customMessage?.confirmText || config.btnConfirmText,
                cancelButtonText: customMessage?.cancelText || config.btnCancelText
            });

            if (result.isConfirmed) {
                const { data } = await axios.delete(`${config.baseUrl}/${endpoint}/delete/${id}`);
                
                alertNotify(data.type, data.messages);
                if (table) table.ajax.reload();
                if (onSuccess) onSuccess(data);
            }
        } catch (error) {
            const msg = error.response?.data?.messages || 'Error al procesar';
            alertNotify('error', msg);
            if (onError) onError(error);
        }
    };
    // Sistema de botones dinámicos
    const initButtons = (buttonsConfig = []) => {
        buttonsConfig.forEach(btn => {
            $(document).on('click', btn.selector, (e) => {
                e.preventDefault();
                deleteItem({
                    id: $(e.target).closest(btn.selector).attr('value'),
                    endpoint: btn.endpoint,
                    table: btn.table,
                    customMessage: btn.customMessage
                });
            });
        });
    };

    return { deleteItem, initButtons, config };
})();