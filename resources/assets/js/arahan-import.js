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
      // The two FINAL Level sheets contain the actionable recommendations.
      // "Table Scoring" is a summary for assessment reporting, not Arahan.
      const rows = workbook.SheetNames
        .filter(sheetName => /^FINAL\s+Level\s+[12]\s*$/i.test(sheetName))
        .flatMap(sheetName => {
          const sheet = workbook.Sheets[sheetName];
          const level = sheetName.replace(/^FINAL\s+/i, '').trim();
          let group = '';

          return XLSX.utils.sheet_to_json(sheet, { header: 'A', defval: '', raw: false })
            .map(row => {
              const item = [cellValue(row, 'A'), cellValue(row, 'B')].filter(Boolean).join(' / ');
              const heading = `${cellValue(row, 'A')} ${cellValue(row, 'B')}`;
              if (/LEVEL\s*2\s*\(.*BONUS/i.test(heading)) group = 'Bonus';
              if (/LEVEL\s*2\s*\(.*PENALT(?:Y|I)/i.test(heading)) group = 'Penalti';

              const code = item.replace(/^\(([BP])\)\s*/i, '');
              // The workbook is mainly A-D; one Level 1 item is labelled E.5.4,
              // so preserve that source label instead of leaving it ungrouped.
              const part = code.match(/^([A-E])(?:[.\s]|$)/i)?.[1]?.toUpperCase() || '';
              const section = part ? (group ? `${group} - ${part}` : part) : group;

              return {
                level,
                section,
                aspek: item,
                arahan: cellValue(row, 'C'),
                status: cellValue(row, 'F'),
                rekomendasi: cellValue(row, 'G')
              };
            })
            .filter(row => row.arahan && row.rekomendasi && /^(YES|NO)$/i.test(row.status));
      });

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
