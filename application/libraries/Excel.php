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

		public function read_xls($file, $cacheSizeMB = 20)
		{
			return $this->read_xls_or_csv($file, 'Excel5', '', $cacheSizeMB);
		}

		public function read_csv($file, $delimiter = ',', $cacheSizeMB = 20)
		{
			return $this->read_xls_or_csv($file, 'CSV', $delimiter, $cacheSizeMB);
		}

		private function read_xls_or_csv
		(
			$file, $fileType, $delimiter = ',', $cacheSizeMB = 20
		)
		{
			PHPExcel_Settings::setCacheStorageMethod(
				PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp,
				array('memoryCacheSize' => $cacheSizeMB.'MB')
			);

			$reader = PHPExcel_IOFactory::createReader($fileType);
			$reader->setReadDataOnly(true);

			if ($fileType === 'CSV') {
				$reader->setDelimiter($delimiter);
			}

			$excel = $reader->load($file);

			$excel->setActiveSheetIndex(0);

			/**
			 * toArray: Create array from worksheet
			 *
			 * @param mixed $nullValue Value returned in the array entry if a cell doesn't exist
			 * @param boolean $calculateFormulas Should formulas be calculated?
			 * @param boolean $formatData  Should formatting be applied to cell values?
			 * @param boolean $returnCellRef False - Return a simple array of rows and columns indexed by number counting from zero
			 *                               True - Return rows and columns indexed by their actual row and column IDs
			 * @return array
			 */

			return $excel->getActiveSheet()->toArray(null, false, false, true);
		}

}
