
const b10ptions = [
    ['W1', 'KUALA LUMPUR PUSAT', 3.14925905877391, 101.754098819705],
    ['B1', 'PETALING JAYA', 3.1128074178475, 101.605270457169],
    ['B1', 'RAWANG', 3.47839445121726, 101.622905486475],
    ['B1', 'KUALA SELANGOR', 3.40703209426401, 101.317426926947],
    ['B2', 'KLANG', 3.08428642705789, 101.436185279023],
    ['B2', 'PELABUHAN KLANG', 2.98188527916042, 101.324234779569],
    ['B4', 'CHERAS', 3.14197346621987, 101.849883983416],
    ['B4', 'BANTING', 2.82111390453244, 101.505890775541],
    ['B4', 'BANGI', 2.965810949933260, 101.81881303103104],
    ['B4', 'PUTRAJAYA & CYBERJAYA', 2.92875032271019, 101.675338316575],
    ['B4', 'SEPANG', 2.734218580014375, 101.69394518452967],
    ['B4', 'PUCHONG', 2.971632230751114, 101.62918173453126]
];

 

    function setMinDate(minDate){
        $('#excel_to_date').attr('min',minDate);
    }

    function setMaxDate(maxDate){
        $('#excel_from_date').attr('max',maxDate);
    }




    function getBa (selectedValue) {

        const areaSelect = $('#excelBa');
        // Clear previous options
        areaSelect.empty();
        areaSelect.append(`<option value="" hidden>Select ba</option>`)

        b10ptions.forEach((data) => {
            if (selectedValue == data[0]) {
                areaSelect.append(`<option value="${data[1]}">${data[1]}</option>`);
            }
        });

        $('#search_wp').empty();
        $('#search_wp').append(`<option value="" hidden>Select Work Package</option>`);

    }




