<?php

require_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    // Load existing Excel file
   $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
   $spreadsheet = $reader->load('./lks.xlsx');
    
    // Get the active worksheet
    $worksheet = $spreadsheet->getActiveSheet();
	
$con = pg_connect("host=192.168.1.34 dbname=afi user=postgres password=Admin123");
if (!$con) {
    die('Connection failed: ' . pg_last_error());
}

   
   $sql98="select  pk_id,
    gid,
  (select pe_fl from fpl1 where pe_name=a.pe_name limit 1) as pe_fl,
    pe_name,
	(select tx1_fl from fpl1 where pe_name=a.pe_name limit 1) as tx_rating,
    (select st_y(geom)||','||st_x(geom) from fpl1 where pe_name=a.pe_name limit 1)  as cord_l1,
     a.l1_id,
	 CASE 
        WHEN a.l2_id IS NOT NULL and a.l2_id like 'SFP%' THEN 
            (SELECT 
                TRIM(TRAILING ',' FROM 
                    CONCAT(
                        CASE WHEN lvf1_fd LIKE '%'||a.l2_id||'%' THEN '1,' ELSE '' END,
                        CASE WHEN lvf2_fd LIKE '%'||a.l2_id||'%' THEN '2,' ELSE '' END,
                        CASE WHEN lvf3_fd LIKE '%'||a.l2_id||'%' THEN '3,' ELSE '' END,
                        CASE WHEN lvf4_fd LIKE '%'||a.l2_id||'%' THEN '4,' ELSE '' END,
                        CASE WHEN lvf5_fd LIKE '%'||a.l2_id||'%' THEN '5,' ELSE '' END,
                        CASE WHEN lvf6_fd LIKE '%'||a.l2_id||'%' THEN '6,' ELSE '' END,
                        CASE WHEN lvf7_fd LIKE '%'||a.l2_id||'%' THEN '7,' ELSE '' END,
                        CASE WHEN lvf8_fd LIKE '%'||a.l2_id||'%' THEN '8,' ELSE '' END,
                        CASE WHEN lvf9_fd LIKE '%'||a.l2_id||'%' THEN '9,' ELSE '' END,
                        CASE WHEN lvf10_fd LIKE '%'||a.l2_id||'%' THEN '10,' ELSE '' END
                    )
                ) AS fd_no
            FROM fpl1 
            WHERE l1_id = a.l1_id 
            LIMIT 1) 
        ELSE NULL
    END AS l1_fd,
	case when
        l2_id is not null and l2_id like 'SFP%' then
        (select st_y(geom)||','||st_x(geom) from sfp_l2 where l2_id=a.l2_id) 
        else null
    end as cord_l2,
    a.l2_id,
    CASE 
        WHEN a.l3_id IS NOT NULL and a.l3_id like 'MFP%' THEN 
            (SELECT 
                TRIM(TRAILING ',' FROM 
                    CONCAT(
                        CASE WHEN lvf1_fd LIKE '%'||a.l3_id||'%' THEN '1,' ELSE '' END,
                        CASE WHEN lvf2_fd LIKE '%'||a.l3_id||'%' THEN '2,' ELSE '' END,
                        CASE WHEN lvf3_fd LIKE '%'||a.l3_id||'%' THEN '3,' ELSE '' END,
                        CASE WHEN lvf4_fd LIKE '%'||a.l3_id||'%' THEN '4,' ELSE '' END,
                        CASE WHEN lvf5_fd LIKE '%'||a.l3_id||'%' THEN '5,' ELSE '' END,
                        CASE WHEN lvf6_fd LIKE '%'||a.l3_id||'%' THEN '6,' ELSE '' END,
                        CASE WHEN lvf7_fd LIKE '%'||a.l3_id||'%' THEN '7,' ELSE '' END,
                        CASE WHEN lvf8_fd LIKE '%'||a.l3_id||'%' THEN '8,' ELSE '' END,
                        CASE WHEN lvf9_fd LIKE '%'||a.l3_id||'%' THEN '9,' ELSE '' END,
                        CASE WHEN lvf10_fd LIKE '%'||a.l3_id||'%' THEN '10,' ELSE '' END
                    )
                ) AS fd_no
            FROM sfp_l2 
            WHERE l2_id = a.l2_id 
            LIMIT 1) 
        ELSE NULL
    END AS l2_fd,
    case when
        l3_id is not null and l3_id like 'MFP%' then
        -- FIXED: Use both st_x and st_y for coordinates
        (select st_y(st_centroid(geom))||','||st_x(st_centroid(geom)) from mfp_l3 where l3_id=a.l3_id) 
        else null
    end as cord_l3,
    l3_id,
    a.meter_type,
    a.install_id,
    a.site_eqp,
    a.cd_id,
    a.fd_no,
    a.phase,
    a.images,
    a.image2,
    a.image3,
    a.image4,
    a.image5,
    (select st_x(st_centroid(geom)) from demand_point where gid=a.gid)  as x,
   (select st_y(st_centroid(geom)) from demand_point where gid=a.gid)  as y,
	TO_CHAR(a.date_created, 'DD-MM-YYYY') as date_created
	from dp_high_rise a where qa_status='Accept' and data_submited<>'submitted'";
  

	$users = pg_query($con, $sql98);
$rows = pg_fetch_all($users);
pg_close($con);


    // Sample data to write (you can replace this with your actual data)
    $dataToWrite = [];
	
	$counter = 1;
	
	foreach ($rows as $row) {
    // Prepare the data for each entry
    $dataToWrite[] = [
		$row['pk_id'],                         // Sequential number (1, 2, 3, ...)
        $row['gid'],                         // 'JID001' equivalent
        $row['pe_fl'],                       // 'Location A' equivalent
        $row['pe_name'],                     // 'FL_Name_A' equivalent
        $row['tx_rating'],                   // 'Rating1'
        $row['cord_l1'],                     // 'Coord1'
        $row['l1_id'],                       // 'LI_JE1'
        $row['l1_fd'],                       // 'LI_N1'
        $row['cord_l2'],                     // 'Coord2'
        $row['l2_id'],                       // 'LI_JE2'
        $row['l2_fd'],                       // 'LI_N2'
        $row['cord_l3'],                     // 'Coord3'
        $row['l3_id'],                       // 'LI_JE3'
        $row['meter_type'],                  // 'Value1' (assuming it means something like that)
        $row['install_id'],                  // 'ID1'
        $row['site_eqp'],                    // 'Number1'
        $row['cd_id'],                       // 'LI_JE4'
        $row['fd_no'],                       // 'Value2'
        $row['phase'],                       // 'Phase1'
		$row['images'],
		$row['image2'],
		$row['image3'],
		$row['image4'],
		$row['image5'],
        $row['x'],                           // 'X'
        $row['y'],                           // 'Y'
        $row['date_created'],                // 'Date1'
    ];
}


    
    // Find the first empty row (assuming headers are in row 9 based on your image)
    $startRow = 13; // Start writing data from row 10
    $currentRow = $startRow;
    
    // Check if there's existing data and find the next empty row
    while ($worksheet->getCell('B' . $currentRow)->getValue() !== null) {
        $currentRow++;
    }
    
    // Write data to the worksheet
    foreach ($dataToWrite as $rowData) {
        $column = 'B';
        foreach ($rowData as $cellValue) {
            $worksheet->setCellValue($column . $currentRow, $cellValue);
            $column++;
        }
        $currentRow++;
    }
    
    // Alternative method: Write data starting from a specific cell
    /*
    $startCell = 'A10'; // Start from cell A10
    $worksheet->fromArray($dataToWrite, null, $startCell);
    */
    
    // Save the updated file
    $writer = new Xlsx($spreadsheet);
    $outputFileName = './lks_hr-'.date('Y-m-d').'.xlsx';
    $writer->save($outputFileName);
	

  
    // Download link with styling
    echo '<p>';
    echo '<a href="http://121.121.232.53:8191/' . $outputFileName . '" download="' . $outputFileName . '" style="';
    echo 'display: inline-block; padding: 12px 24px; background-color: #007bff; color: white; ';
    echo 'text-decoration: none; border-radius: 5px; font-weight: bold; margin-right: 10px;';
    echo '">📥 Download Excel File</a>';
    
} catch (Exception $e) {
    echo 'Error loading or saving file: ', $e->getMessage(), "\n";
}

// Example function to append single row of data
function appendRowToExcel($filePath, $rowData) {
    try {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        
        // Find the next empty row
        $row = 10; // Start checking from row 10
        while ($worksheet->getCell('A' . $row)->getValue() !== null) {
            $row++;
        }
        
        // Write the row data
        $column = 'A';
        foreach ($rowData as $value) {
            $worksheet->setCellValue($column . $row, $value);
            $column++;
        }
        
        // Save the file
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
        
        return true;
    } catch (Exception $e) {
        echo 'Error: ', $e->getMessage();
        return false;
    }
}

// Example usage of the append function
/*
$singleRow = [4, 'JID004', 'Location D', 'FL_Name_D', 'Rating4', 'Coord10'];
appendRowToExcel('path/to/your/file.xlsx', $singleRow);
*/

function downloadExistingFile($filepath) {
    if (!file_exists($filepath)) {
        die('File not found.');
    }
    
    $filename = basename($filepath);
    
    // Set headers
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($filepath));
    header('Cache-Control: max-age=0');
    
    // Output file
    readfile($filepath);
    exit();
}


// Example function to update specific cell
function updateCell($filePath, $cellAddress, $value) {
    try {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $worksheet->setCellValue($cellAddress, $value);
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
        
        return true;
    } catch (Exception $e) {
        echo 'Error: ', $e->getMessage();
        return false;
    }
}

// Example usage of update cell function
/*
updateCell('path/to/your/file.xlsx', 'B10', 'Updated Value');
*/
?>