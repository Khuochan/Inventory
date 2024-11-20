
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

pdfMake.vfs = pdfFonts.pdfMake.vfs;

export function generatePDF(data) {
    const docDefinition = {
      pageSize: 'A4',
      pageOrientation: 'landscape',
      content: [
        { text: 'List Employee', fontSize: 16 },
        { canvas: [{ type: 'line', x1: 0, y1: 10, x2: 760, y2: 10 }] },
        { text: '', margin: [0, 10, 0, 0] },
        {
          table: {
            headerRows: 2,
            widths: ['auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto'],
            body: [
              [
                { text: 'Employee', colSpan: 7, alignment: 'center', fillColor: '#6699FF' },
                {}, {}, {}, {}, {}, {},
                { text: 'Family', colSpan: 3, alignment: 'center', fillColor: '#CCCCCC' },
                {}, {},
              ],
              [
                { text: "Employee's Name", colSpan: 2, alignment: 'center' },
                {},
                { text: 'Family Status', alignment: 'center' },
                { text: 'Position', alignment: 'center' },
                { text: 'Joining Date', alignment: 'center' },
                { text: 'Phone', alignment: 'center' },
                { text: 'Address', alignment: 'center' },
                { text: 'Family Phone', alignment: 'center' },
                { text: 'Family Member', alignment: 'center' },
                { text: 'Family Name', alignment: 'center' },
              ],
              ...data.map((item) => [
                { text: `${item.last_name} ${item.first_name}`, colSpan: 2, alignment: 'center' },
                {},
                { text: item.family_status, alignment: 'center' },
                { text: item.dept_name, alignment: 'center' },
                { text: item.start_date, alignment: 'center' },
                { text: item.phone, alignment: 'center' },
                { text: item.address, alignment: 'center' },
                { text: item.family_phone, alignment: 'center' },
                { text: item.family_member, alignment: 'center' },
                { text: item.family_name, alignment: 'center' },
              ]),
            ],
          },
        },
      ],
      styles: {
        defaultStyle: {
          fontSize: 11, // Set font size to 11
        },
      },
    };

    pdfMake.createPdf(docDefinition).download('EmployeeList.pdf');
  }
