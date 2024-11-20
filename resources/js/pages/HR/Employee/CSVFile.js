import exportFromJSON from "export-from-json";

export const excelParser = () => {
  function exportDataFromJSON(data, newFileName, fileExportType) {
    if (!data) return;
    try {
      const fileName = newFileName || "ListEmployeeExport";
      const exportType = exportFromJSON.types[fileExportType] || "csv";
      const selectedData = data.map(item => ({
            name: `${item.last_name} ${item.first_name}`,
            gender: item.gender,
            department: item.dept_name,
            position: item.role_name,
            start_date: item.start_date,
            email: item.email,
            phone: item.phone,
            address: item.address
      }));

      exportFromJSON({ data: selectedData, fileName, exportType });
    } catch (e) {
      throw new Error("Parsing failed!");
    }
  }

  return {
    exportDataFromJSON
  };
};
