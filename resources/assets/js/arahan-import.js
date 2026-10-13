import * as XLSX from 'xlsx';

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('arahan-import-form');
  if (!form) return;

  const fileInput = document.getElementById('arahan-import-file');
  const submitButton = document.getElementById('arahan-import-submit');
  const feedback = document.getElementById('arahan-import-feedback');

  const cellValue = (row, column) => String(row[column] ?? '').trim();

  form.addEventListener('submit', async event => {
    event.preventDefault();
    const file = fileInput.files[0];
    if (!file) {
      feedback.textContent = 'Pilih file Excel terlebih dahulu.';
      feedback.className = 'text-danger small mt-2';
      return;
    }

    submitButton.disabled = true;
    feedback.textContent = 'Membaca file Excel...';
    feedback.className = 'text-muted small mt-2';

    try {
      const workbook = XLSX.read(await file.arrayBuffer(), { type: 'array' });
      const normalizeHeader = value => String(value ?? '')
        .trim()
        .toLocaleLowerCase()
        .replace(/[^\p{L}\p{N}]/gu, '');

      const normalizeStatus = value => {
        const status = String(value ?? '').trim().toLocaleLowerCase();
        const statuses = {
          open: 'Open',
          progress: 'Progress',
          selesai: 'Selesai',
          'selesaiberkelanjutan': 'Selesai Berkelanjutan'
        };

        return statuses[status.replace(/\s+/g, '')] || 'Open';
      };

      let rows = [];

      for (const sheetName of workbook.SheetNames) {
        const worksheetRows = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], {
          header: 1,
          defval: '',
          raw: false
        });
        const headerIndex = worksheetRows.findIndex(row => {
          const headers = row.map(normalizeHeader);
          return headers.includes('aspek') && headers.includes('arahan');
        });

        if (headerIndex === -1) continue;

        const headers = worksheetRows[headerIndex].map(normalizeHeader);
        const column = name => headers.indexOf(name);
        const aspekColumn = column('aspek');
        const arahanColumn = column('arahan');
        const tindakLanjutColumn = column('tindaklanjut');
        const statusColumn = column('status');

        rows = worksheetRows
          .slice(headerIndex + 1)
          .map(row => ({
            aspek: cellValue(row, aspekColumn),
            arahan: cellValue(row, arahanColumn),
            rekomendasi: tindakLanjutColumn === -1 ? '' : cellValue(row, tindakLanjutColumn),
            status: statusColumn === -1 ? 'Open' : normalizeStatus(cellValue(row, statusColumn))
          }))
          .filter(row => row.aspek && row.arahan);
        break;
      }

      if (!rows.length) throw new Error('Tidak ditemukan data. Pastikan file memiliki header Aspek dan Arahan.');
      if (!rows.length) throw new Error('Tidak ditemukan rekomendasi. Pastikan file memakai kolom C (standar), F (status), dan G (rekomendasi).');

      const request = new FormData(form);
      request.set('rows', JSON.stringify(rows));
      const response = await fetch(form.action, {
        method: 'POST',
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: request
      });

      if (response.redirected) {
        window.location.assign(response.url);
        return;
      }

      const body = await response.json();
      throw new Error(body.message || 'Import gagal.');
    } catch (error) {
      feedback.textContent = error.message || 'Import gagal. Coba lagi.';
      feedback.className = 'text-danger small mt-2';
      submitButton.disabled = false;
    }
  });
});
