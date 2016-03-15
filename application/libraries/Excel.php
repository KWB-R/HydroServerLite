<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/PHPExcel.php"; 
 
class Excel extends PHPExcel 
{ 
		public function __construct() 
		{ 
        parent::__construct(); 
    }

		public function output_as_xls(
			$result, 
			$sheetName = 'data', 
			$format = 'Excel5'
			// 'Excel5' (Excel 2003 .XLS) or 'Excel2007' (Excel 2007 .XLSX)
		)
		{
			$this->setActiveSheetIndex(0);
			$sheet = $this->getActiveSheet();
			$sheet->setTitle($sheetName);

			$sheet->fromArray($result, NULL, 'A2');

			$col = 0;
			$captions = array_keys($result[1]);

			foreach ($captions AS $caption) {

				// Set column caption in row 1
				$sheet->setCellValueByColumnAndRow($col, 1, $caption);

				// Set AutoSize for all columns
				$column = PHPExcel_Cell::stringFromColumnIndex($col);
				$sheet->getColumnDimension($column)->setAutoSize(true);

				$col++;
			}

			$writer = PHPExcel_IOFactory::createWriter($this, $format);
			$writer->save('php://output');
		}

		function read_xls
		(
			$file, $format = 'Excel5', $cacheSizeMB = 20
		)
		{
			PHPExcel_Settings::setCacheStorageMethod(
				PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp,
				array('memoryCacheSize' => $cacheSizeMB.'MB')
			);

			$reader = PHPExcel_IOFactory::createReader($format);
			$reader->setReadDataOnly(true);

			$excel = $reader->load($file);
			$excel->setActiveSheetIndex(0);

			return $excel->getActiveSheet()->toArray(null, true, true, true);
		}
}
