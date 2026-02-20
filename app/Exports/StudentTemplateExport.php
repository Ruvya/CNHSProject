<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class StudentTemplateExport implements FromArray, WithEvents, WithTitle
{
    private $headers;
    private $sampleData;

    public function __construct($headers, $sampleData)
    {
        $this->headers = $headers;
        $this->sampleData = $sampleData;
    }

    public function array(): array
    {
        // Return empty array as we'll build the entire sheet in the events
        return [];
    }

    public function title(): string
    {
        return 'SF1-SHS Student Register';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->buildSF1SHSFormat($sheet);
            },
        ];
    }

    private function buildSF1SHSFormat(Worksheet $sheet)
    {
        // Set page orientation to landscape
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        // Set column widths
        $columnWidths = [
            'A' => 12,  // LRN
            'B' => 20,  // Last Name
            'C' => 15,  // First Name
            'D' => 15,  // Middle Name
            'E' => 8,   // Name Extension
            'F' => 12,  // Date of Birth
            'G' => 6,   // Age
            'H' => 6,   // Sex
            'I' => 18,  // Place of Birth
            'J' => 12,  // Mother Tongue
            'K' => 12,  // IP/Ethnicity
            'L' => 12,  // Religion
            'M' => 25,  // Complete Address
            'N' => 18,  // Father's Name
            'O' => 18,  // Mother's Name
            'P' => 18,  // Guardian's Name
            'Q' => 12,  // Relationship
            'R' => 15,  // Contact Number
            'S' => 12,  // Date of Enrollment
            'T' => 10,  // Grade Level
            'U' => 12,  // Section
            'V' => 15,  // Track
            'W' => 20,  // Strand
            'X' => 15,  // Adviser
            'Y' => 12,  // School Year
            'Z' => 15,  // Remarks
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        // Build the header section
        $this->buildHeaderSection($sheet);

        // Build the school information section
        $this->buildSchoolInfoSection($sheet);

        // Build the student data table
        $this->buildStudentDataTable($sheet);

        // Add sample data
        $this->addSampleData($sheet);
    }

    private function buildHeaderSection(Worksheet $sheet)
    {
        // School identifier section
        $sheet->setCellValue('A1', 'CNHS');
        $sheet->mergeCells('A1:B1');

        // Main title
        $sheet->setCellValue('C1', 'School Form 1 School Register for Senior High School (SF1-SHS)');
        $sheet->mergeCells('C1:X1');

        // DepEd logo placeholder
        $sheet->setCellValue('Y1', 'DepEd');
        $sheet->mergeCells('Y1:Z1');

        // Style the header section
        $sheet->getStyle('A1:Z1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'name' => 'Arial'
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        // Set row height for title
        $sheet->getRowDimension(1)->setRowHeight(30);
    }

    private function buildSchoolInfoSection(Worksheet $sheet)
    {
        // School information section (rows 2-4)
        // Row 2: School details
        $sheet->setCellValue('A2', 'School Name:');
        $sheet->setCellValue('C2', 'Calingcaguing National High School');
        $sheet->mergeCells('C2:F2');
        $sheet->setCellValue('G2', 'School ID:');
        $sheet->setCellValue('H2', '303357');
        $sheet->setCellValue('J2', 'District:');
        $sheet->setCellValue('K2', 'Barugo II');
        $sheet->mergeCells('K2:M2');
        $sheet->setCellValue('N2', 'Division:');
        $sheet->setCellValue('O2', 'Leyte');
        $sheet->mergeCells('O2:Q2');
        $sheet->setCellValue('R2', 'Region:');
        $sheet->setCellValue('S2', 'Region VIII');
        $sheet->mergeCells('S2:Z2');

        // Row 3: Academic details
        $sheet->setCellValue('A3', 'Semester:');
        $sheet->setCellValue('C3', 'Second Semester');
        $sheet->mergeCells('C3:F3');
        $sheet->setCellValue('G3', 'School Year:');
        $sheet->setCellValue('H3', '2025-2026');
        $sheet->mergeCells('H3:I3');
        $sheet->setCellValue('J3', 'Grade Level:');
        $sheet->setCellValue('K3', 'Grade 12');
        $sheet->mergeCells('K3:M3');
        $sheet->setCellValue('N3', 'Track and Cluster:');
        $sheet->setCellValue('P3', 'Technical-Vocational-Livelihood Track');
        $sheet->mergeCells('P3:Z3');

        // Row 4: Section details
        $sheet->setCellValue('A4', 'Section:');
        $sheet->setCellValue('C4', 'Caregiving 12');
        $sheet->mergeCells('C4:F4');
        $sheet->setCellValue('G4', 'Course (for TVL only):');
        $sheet->mergeCells('G4:J4');
        $sheet->setCellValue('K4', 'Caregiving (NC II)');
        $sheet->mergeCells('K4:Z4');

        // Style school info section
        $sheet->getStyle('A2:Z4')->applyFromArray([
            'font' => [
                'size' => 10,
                'name' => 'Arial'
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        // Bold the labels
        $labels = ['A2', 'G2', 'J2', 'N2', 'R2', 'A3', 'G3', 'J3', 'N3', 'A4', 'G4'];
        foreach ($labels as $cell) {
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Set row heights
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(20);
        $sheet->getRowDimension(4)->setRowHeight(20);
    }

    private function buildStudentDataTable(Worksheet $sheet)
    {
        // Student data table headers starting from row 5
        $startRow = 5;

        // SF1-SHS style headers
        $headers = [
            'LRN',
            'Last Name',
            'First Name',
            'Middle Name',
            'Name Extension',
            'Date of Birth',
            'Age',
            'Sex',
            'Place of Birth',
            'Mother Tongue',
            'IP/Ethnicity',
            'Religion',
            'Complete Address',
            'Father\'s Name',
            'Mother\'s Name',
            'Guardian\'s Name',
            'Relationship',
            'Contact Number',
            'Date of Enrollment',
            'Grade Level',
            'Section',
            'Track',
            'Cluster',
            'Adviser',
            'School Year',
            'Remarks'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $startRow, $header);
            $col++;
        }

        // Style the header row
        $sheet->getStyle('A5:Z5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'name' => 'Arial'
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E1F2']
            ]
        ]);

        // Set row height for header
        $sheet->getRowDimension(5)->setRowHeight(40);
    }

    private function addSampleData(Worksheet $sheet)
    {
        // Sample data starting from row 6
        $sampleData = [
            [
                '123456789012', // LRN
                'DELA CRUZ', // Last Name
                'JUAN', // First Name
                'SANTOS', // Middle Name
                '', // Name Extension
                '05/15/2006', // Date of Birth
                '17', // Age
                'M', // Sex
                'QUEZON CITY', // Place of Birth
                'TAGALOG', // Mother Tongue
                'FILIPINO', // IP/Ethnicity
                'CATHOLIC', // Religion
                '123 MAIN ST, QUEZON CITY', // Complete Address
                'PEDRO DELA CRUZ', // Father's Name
                'MARIA SANTOS DELA CRUZ', // Mother's Name
                'MARIA SANTOS DELA CRUZ', // Guardian's Name
                'MOTHER', // Relationship
                '09123456789', // Contact Number
                '08/15/2024', // Date of Enrollment
                'Grade 11', // Grade Level
                'EINSTEIN', // Section
                'Academic Track', // Track
                'STEM', // Cluster
                'MS. TEACHER', // Adviser
                '2024-2025', // School Year
                'NEW' // Remarks
            ],
            [
                '123456789013', // LRN
                'GARCIA', // Last Name
                'MARIA', // First Name
                'LOPEZ', // Middle Name
                '', // Name Extension
                '03/22/2006', // Date of Birth
                '17', // Age
                'F', // Sex
                'MANILA', // Place of Birth
                'TAGALOG', // Mother Tongue
                'FILIPINO', // IP/Ethnicity
                'CATHOLIC', // Religion
                '456 RIZAL ST, MANILA', // Complete Address
                'JOSE GARCIA', // Father's Name
                'ANA LOPEZ GARCIA', // Mother's Name
                'ANA LOPEZ GARCIA', // Guardian's Name
                'MOTHER', // Relationship
                '09987654321', // Contact Number
                '08/15/2024', // Date of Enrollment
                'Grade 12', // Grade Level
                'NEWTON', // Section
                'TVL Track', // Track
                'ICT', // Cluster
                'MR. ADVISOR', // Adviser
                '2024-2025', // School Year
                'TRANSFEREE' // Remarks
            ]
        ];

        $row = 6;
        foreach ($sampleData as $data) {
            $col = 'A';
            foreach ($data as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }

        // Style data rows
        $sheet->getStyle('A6:Z' . ($row - 1))->applyFromArray([
            'font' => [
                'size' => 9,
                'name' => 'Arial'
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        // Add borders to empty rows for template
        for ($i = $row; $i <= $row + 20; $i++) {
            $sheet->getStyle('A' . $i . ':Z' . $i)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ]
            ]);
        }
    }
}
