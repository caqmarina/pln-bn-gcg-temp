import * as XLSX from 'xlsx';

document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('arahan-detail-export');
    const dataElement = document.getElementById('arahan-detail-export-data');

    if (!button || !dataElement) {
        return;
    }

    button.addEventListener('click', () => {
        let rows;

        try {
            rows = JSON.parse(dataElement.textContent || '[]');
        } catch {
            window.alert('Data Arahan Detail tidak dapat diproses.');
            return;
        }

        if (!Array.isArray(rows) || rows.length === 0) {
            window.alert('Belum ada data Arahan Detail untuk diekspor.');
            return;
        }

        const workbook = XLSX.utils.book_new();
        const worksheet = XLSX.utils.json_to_sheet(rows, {
            header: ['No', 'Aspek', 'Arahan', 'Tindak Lanjut', 'Status', 'Eviden'],
        });

        worksheet['!cols'] = [
            { wch: 6 },
            { wch: 34 },
            { wch: 70 },
            { wch: 70 },
            { wch: 24 },
            { wch: 24 },
        ];

        XLSX.utils.book_append_sheet(workbook, worksheet, 'Arahan Detail');

        const title = document.title
            .replace(/[^a-z0-9]+/gi, '-')
            .replace(/^-|-$/g, '')
            .toLowerCase() || 'arahan-detail';

        XLSX.writeFile(workbook, `${title}-${new Date().toISOString().slice(0, 10)}.xlsx`);
    });
});
