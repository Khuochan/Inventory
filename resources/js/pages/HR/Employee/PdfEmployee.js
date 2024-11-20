import jsPDF from 'jspdf';
import 'jspdf-autotable';

export function generatePDF(data) {
    const pdf = new jsPDF('portrait');
    console.log(data);
    const y = 20;

     // Add title to the PDF
    pdf.setFontSize(16);
    pdf.setTextColor(0, 0, 255); // Set font color to blue
    pdf.setFont('helvetica', 'bold'); // Set font style to bold
    pdf.text('Employee Leave Report', 70, y + 10);
    pdf.setTextColor(0, 0, 0); // Set font color to black
    pdf.setFont('helvetica', 'normal'); // Set font style to normal
    pdf.setFontSize(12);

    // Add text
    let yPos = y + 20;
    const item = data[0];
    pdf.text(`Name: ${item.last_name} ${item.first_name}`, 14, yPos);
    pdf.text(`Position: ${item.role_name}`, 14, yPos + 10);
    yPos += 10;

    const headers = [['Leave Type', 'Balance']];
    const rows = data.slice(1).map(item => [
      item.leave_name,
      item.leave_balance,
    ]);

    pdf.autoTable({
      startY: yPos + 10,
      head: headers,
      body: rows
    });

    pdf.save('LeaveReport.pdf');
}
