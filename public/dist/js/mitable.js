
window.addEventListener('DOMContentLoaded', event => {

    const usertable = document.getElementById('userTabla');
    const datatable = document.getElementById('mitabla');
    const datatable2 = document.getElementById('mitabla2');
    const datatable3 = document.getElementById('mitabla3');

    if (usertable) {
        new simpleDatatables.DataTable(usertable, {
            perPage: 5,
            columns: [
                { select: [0], sortable: false, searchable: false },
                { select: [1], sortable: false, searchable: false }
            ]
        });
    }
    if (datatable) {
        new simpleDatatables.DataTable(datatable, {
            columnDefs: [{ width: 200, targets: 0 }],
            perPage: 5,
            columns: [
                { select: [0], sortable: false, searchable: false },
                { select: [1], sortable: false, searchable: false },
            ]
        });
    }
    if (datatable2) {
        new simpleDatatables.DataTable(datatable2, {
            perPage: 5,
            columns: [
                { select: [0], sortable: false, searchable: false },
                { select: [1], sortable: true },
            ]
        });
    }
    if (datatable3) {
        new simpleDatatables.DataTable(datatable3, {
            perPage: 5,
            columns: [
                { select: [0], sortable: true },
                { select: [1], sortable: true },
            ]
        });
    }
});
