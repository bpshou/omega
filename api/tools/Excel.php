<?php

namespace api\tools;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Excel操作类
 * @author decezz@qq.com
 */
class Excel
{
    /**
     * 获取当前sheet页excel
     * @param string $file
     * @return array
     */
    public function getActiveExcel(string $file)
    {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        return $sheet->toArray();
    }

    /**
     * 获取excel所有内容
     * @param string $file
     * @return array
     */
    public function getExcel(string $file)
    {
        $spreadsheet = IOFactory::load($file);
        $allSheets = $spreadsheet->getAllSheets();
        $result = [];
        // 遍历所有sheet页
        foreach ($allSheets as $key => $sheet) {
            $title = $sheet->getTitle();
            $result[$title] = $sheet->toArray();
        }
        return $result;
    }

    /**
     * 写入excel
     * @param string $file
     * @param array $data (数组中如果值被转换为科学计数法，将值改为 '="$value"')
     * @return void
     */
    public function setExcel(string $file, array $data = [])
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        $row = 1;
        foreach ($data as $item) {
            $column = 1;
            foreach ($item as $value) {
                $worksheet->setCellValueByColumnAndRow($column, $row, $value);
                $column++;
            }
            $row++;
        }
        // 文件后缀
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        // write excel
        $writer = IOFactory::createWriter($spreadsheet, ucfirst(strtolower($extension)));
        $writer->save($file);
    }
}
