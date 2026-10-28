import * as XLSX from 'xlsx';

document.addEventListener('DOMContentLoaded', () => {
  const button = document.getElementById('dashboard-report-export');
  const dataElement = document.getElementById('dashboard-report-export-data');

  if (!button || !dataElement) {
    return;
  }

  button.addEventListener('click', () => {
    let report;

    try {
      report = JSON.parse(dataElement.textContent || '{}');
    } catch {
      window.alert('Data laporan dashboard tidak dapat diproses.');
      return;
    }

    const originalLabel = button.innerHTML;
    button.disabled = true;
    button.textContent = 'Menyiapkan file...';

    try {
      const workbook = XLSX.utils.book_new();
      const summaryRows = [
        ['Laporan Dashboard GCG'],
        ['Diekspor pada', report.generatedAt || ''],
        [],
        ['Ringkasan', 'Nilai'],
        ...Object.entries(report.summary || {}),
      ];

      const summarySheet = XLSX.utils.aoa_to_sheet(summaryRows);
      summarySheet['!cols'] = [{ wch: 32 }, { wch: 28 }];
      XLSX.utils.book_append_sheet(workbook, summarySheet, 'Ringkasan');

      const arahanRows = Array.isArray(report.arahan) ? report.arahan : [];
      const arahanSheet = XLSX.utils.json_to_sheet(arahanRows, {
        header: ['Judul', 'Tanggal', 'Progress (%)', 'Status', 'Selesai', 'Total'],
      });
      arahanSheet['!cols'] = [
        { wch: 42 },
        { wch: 14 },
        { wch: 16 },
        { wch: 22 },
        { wch: 10 },
        { wch: 10 },
      ];
      XLSX.utils.book_append_sheet(workbook, arahanSheet, 'Arahan');

      const assessmentRows = Array.isArray(report.assessments) ? report.assessments : [];
      const assessmentSheet = XLSX.utils.json_to_sheet(assessmentRows, {
        header: ['Framework', 'Tahun Assessment', 'Tahun Buku', 'Jenis', 'Progress (%)', 'Status', 'Skor'],
      });
      assessmentSheet['!cols'] = [
        { wch: 32 },
        { wch: 20 },
        { wch: 14 },
        { wch: 20 },
        { wch: 16 },
        { wch: 22 },
        { wch: 12 },
      ];
      XLSX.utils.book_append_sheet(workbook, assessmentSheet, 'Assessment');

      const analyticsRows = [
        ['Pemenuhan Indikator', 'Jumlah'],
        ...Object.entries(report.fulfillment || {}),
        [],
        ['Skor per Kategori', 'Skor', 'Maksimum'],
        ...(report.categoryScores || []).map(item => [item.name, item.score, item.maximum]),
        [],
        ['Tren Assessment', 'Rata-rata Skor'],
        ...(report.assessmentTrend || []).map(item => [item.year, item.score]),
      ];
      const analyticsSheet = XLSX.utils.aoa_to_sheet(analyticsRows);
      analyticsSheet['!cols'] = [{ wch: 32 }, { wch: 20 }, { wch: 16 }];
      XLSX.utils.book_append_sheet(workbook, analyticsSheet, 'Analitik');

      const now = new Date();
      const today = [now.getFullYear(), String(now.getMonth() + 1).padStart(2, '0'), String(now.getDate()).padStart(2, '0')].join('-');
      XLSX.writeFile(workbook, `laporan-dashboard-gcg-${today}.xlsx`);
    } catch {
      window.alert('File Excel tidak dapat dibuat. Silakan coba lagi.');
    } finally {
      button.disabled = false;
      button.innerHTML = originalLabel;
    }
  });
});
