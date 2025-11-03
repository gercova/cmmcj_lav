$('#histories').jtable({
    title       : "HOSPITALIZACIONES",
    paging      : true,
    overflow    : scroll,
    sorting     : true,
    actions: {
        listAction: `${API_URL}/sys/histories/list`,
    },
    toolbar: {
        items: [{
            cssClass: 'buscador',
            text: buscador
        }]
    },
    fields: {
        created_at: {
            key: false,
            title: 'FECHA',
            width: '6%' ,
        },
        dni: {
            key: true,
            title: 'DNI',
            width: '6%' ,

        },
        nombres: {
            title: 'NOMBRES',
            width: '20%',

        },
        fecha_nacimiento: {
            title: 'F.N',
            width: '6%',

        },
        edad: {
            title: 'EDAD',
            width: '4%',
        },
        sexo: {
            title: 'SEXO',
            width: '4%' ,
        },
        ver:{
            title: 'OPCIONES',
            width: '10%',
            sorting:false,
            edit:false,
            create:false,
            display: (data) => {
                const permissions = data.record.Permissions || {}; // Obtenemos los permisos del registro
                let buttons = '';
                if (permissions.view_exm) {
                    buttons += `
                        <button type="button" class="btn btn-info view-row btn-xs" value="${data.record.id}">
                            <i class="bi bi-folder"></i> Ver
                        </button>&nbsp;
                    `;
                }
                if (permissions.add_exm) {
                    buttons += `
                        <button type="button" class="btn btn-success add-new btn-xs" value="${data.record.id}">
                            <i class="bi bi-plus-square-fill"></i> Nuevo
                        </button>&nbsp;
                    `;
                }
                
                return buttons;
            }
        },
    },
    recordsLoaded: (event, data) => {
        $('.add-new').click(function(e){
            e.preventDefault();
            let id = $(this).attr('value');
            window.location.href = `${API_URL}/sys/exams/new/${id}`;
        });
        
        $('.view-row').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('value');
            window.location.href = `${API_URL}/sys/exams/see/${id}`;
        });
    }
});