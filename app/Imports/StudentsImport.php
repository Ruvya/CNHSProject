<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class StudentsImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    use Importable;

    protected $errors = [];
    protected $successCount = 0;
    protected $updateCount = 0;
    protected $skipCount = 0;
    protected $detectedColumns = [];
    protected $gradeLevel;
    protected $track;

    public function __construct($gradeLevel = null, $track = null)
    {
        $this->gradeLevel = $gradeLevel;
        $this->track = $track;
    }

    public function collection(Collection $rows)
    {
        // Capture detected columns from the first row
        if ($rows->isNotEmpty()) {
            $this->detectedColumns = array_keys($rows->first()->toArray());

            // Debug: Log detected columns to help troubleshoot
            \Log::info("Excel file columns detected", [
                'detected_columns' => $this->detectedColumns,
                'first_row_data' => $rows->first()->toArray()
            ]);
        }

        foreach ($rows as $index => $row) {
            try {
                // Skip empty rows
                if ($this->isEmptyRow($row)) {
                    $this->skipCount++;
                    continue;
                }

                $this->processStudentRow($row, $index + 2); // +2 because of header row and 0-based index
            } catch (\Exception $e) {
                $this->errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }
    }

    protected function processStudentRow($row, $rowNumber)
    {
        // Clean and prepare data
        $studentData = $this->prepareStudentData($row);

        // Skip rows where student_id is still empty after all attempts
        if (empty($studentData['student_id'])) {
            $detectedColumnsStr = implode(', ', $this->detectedColumns);
            $this->errors[] = "Row {$rowNumber}: Student ID is required but not found. Detected columns: [{$detectedColumnsStr}]. Please ensure your Excel file has a column with student ID information.";
            return;
        }

        // Validate required fields
        $validator = Validator::make($studentData, [
            'student_id' => 'required|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'grade_level' => 'nullable|string|max:50',
            'gender' => 'nullable|in:Male,Female',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'civil_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'track' => 'nullable|string|max:100',
            'strand' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            throw new \Exception("Validation failed: " . implode(', ', $errors));
        }

        // Check if student exists
        $existingStudent = Student::where('student_id', $studentData['student_id'])->first();

        if ($existingStudent) {
            // Update existing student
            $this->updateExistingStudent($existingStudent, $studentData);
            $this->updateCount++;
        } else {
            // Create new student
            $this->createNewStudent($studentData);
            $this->successCount++;
        }
    }

    /**
     * Check if a row is empty (all values are null or empty strings)
     */
    protected function isEmptyRow($row)
    {
        if ($row->isEmpty()) {
            return true;
        }

        // Check if all values are empty
        foreach ($row as $value) {
            if (!empty(trim($value))) {
                return false;
            }
        }

        return true;
    }

    protected function prepareStudentData($row)
    {
        // Use smart column mapping
        $data = [
            'student_id' => $this->smartGetColumnValue($row, 'student_id'),
            'first_name' => $this->getColumnValue($row, ['first_name', 'first-name', 'firstname', 'fname']),
            'middle_name' => $this->getColumnValue($row, ['middle_name', 'middle-name', 'middlename', 'mname']),
            'last_name' => $this->getColumnValue($row, ['last_name', 'last-name', 'lastname', 'lname']),
            'email' => $this->getColumnValue($row, ['email', 'email_address', 'email-address']),
            'grade_level' => $this->getColumnValue($row, ['grade_level', 'grade-level', 'gradelevel', 'grade']),
            'section' => $this->getColumnValue($row, ['section']),
            'track' => $this->getColumnValue($row, ['track']),
            'strand' => $this->getColumnValue($row, ['strand']),
            'gender' => $this->getColumnValue($row, ['gender', 'sex']),
            'date_of_birth' => $this->parseDate($this->getColumnValue($row, ['date_of_birth', 'date-of-birth', 'dateofbirth', 'dob', 'birthdate'])),
            'place_of_birth' => $this->getColumnValue($row, ['place_of_birth', 'place-of-birth', 'placeofbirth', 'birthplace']),
            'nationality' => $this->getColumnValue($row, ['nationality']),
            'religion' => $this->getColumnValue($row, ['religion']),
            'civil_status' => $this->getColumnValue($row, ['civil_status', 'civil-status', 'civilstatus', 'marital_status', 'marital-status']),
            'lrn' => $this->getColumnValue($row, ['lrn', 'learner_reference_number', 'learner-reference-number']),
            'profile_picture' => $this->getColumnValue($row, ['profile_picture', 'profile-picture', 'profilepicture', 'photo']),
            'contact_number' => $this->getColumnValue($row, ['contact_number', 'contact-number', 'contactnumber', 'phone', 'mobile']),
            'address' => $this->getColumnValue($row, ['address', 'home_address', 'home-address']),
            'parent_name' => $this->getColumnValue($row, ['parent_name', 'parent-name', 'parentname', 'guardian_name', 'guardian-name']),
            'parent_contact' => $this->getColumnValue($row, ['parent_contact', 'parent-contact', 'parentcontact', 'guardian_contact', 'guardian-contact']),
            'advisor' => $this->getColumnValue($row, ['advisor', 'class_advisor', 'class-advisor']),
            'province' => $this->getColumnValue($row, ['province']),
            'municipality' => $this->getColumnValue($row, ['municipality', 'city']),
            'barangay' => $this->getColumnValue($row, ['barangay']),
            'permanent_address' => $this->getColumnValue($row, ['permanent_address', 'permanent-address', 'permanentaddress']),
            'phone' => $this->getColumnValue($row, ['phone', 'telephone']),
            'emergency_name' => $this->getColumnValue($row, ['emergency_name', 'emergency-name', 'emergencyname', 'emergency_contact_name', 'emergency-contact-name']),
            'emergency_phone' => $this->getColumnValue($row, ['emergency_phone', 'emergency-phone', 'emergencyphone', 'emergency_contact_number', 'emergency-contact-number']),
            'emergency_relationship' => $this->getColumnValue($row, ['emergency_relationship', 'emergency-relationship', 'emergencyrelationship', 'emergency_contact_relationship', 'emergency-contact-relationship']),
        ];

        // Apply selected grade level and track if provided
        if ($this->gradeLevel) {
            $data['grade_level'] = $this->gradeLevel;
        }
        if ($this->track) {
            $data['track'] = $this->track;
        }

        return $data;
    }

    /**
     * Smart column value getter that tries to intelligently match column names
     */
    protected function smartGetColumnValue($row, $fieldType)
    {
        $rowArray = $row->toArray();
        $rowKeys = array_keys($rowArray);

        // For student_id, try a very aggressive approach
        if ($fieldType === 'student_id') {
            // First, try any column that might contain student ID
            foreach ($rowKeys as $rowKey) {
                $rowKeyLower = strtolower($rowKey);
                $value = trim($row[$rowKey]);

                // Skip empty values
                if (empty($value)) {
                    continue;
                }

                // Check if this looks like a student ID column
                if (strpos($rowKeyLower, 'student') !== false ||
                    strpos($rowKeyLower, 'id') !== false ||
                    strpos($rowKeyLower, 'number') !== false ||
                    strpos($rowKeyLower, 'lrn') !== false ||
                    strpos($rowKeyLower, 'learner') !== false ||
                    strpos($rowKeyLower, 'pupil') !== false ||
                    strpos($rowKeyLower, 'no') !== false ||
                    strpos($rowKeyLower, 'code') !== false) {

                    \Log::info("Found potential student_id column", [
                        'column_name' => $rowKey,
                        'value' => $value
                    ]);
                    return $value;
                }
            }

            // If no obvious student ID column, use the first non-empty column
            foreach ($rowKeys as $rowKey) {
                $value = trim($row[$rowKey]);
                if (!empty($value)) {
                    \Log::info("Using first non-empty column as student_id", [
                        'column_name' => $rowKey,
                        'value' => $value
                    ]);
                    return $value;
                }
            }
        }

        return '';
    }

    /**
     * Get column value with fallback for different column name variations
     */
    protected function getColumnValue($row, $possibleKeys)
    {
        $rowArray = $row->toArray();
        $rowKeys = array_keys($rowArray);

        // First try exact matches
        foreach ($possibleKeys as $key) {
            if (isset($row[$key]) && !empty(trim($row[$key]))) {
                return trim($row[$key]);
            }
        }

        // Then try case-insensitive matches
        foreach ($possibleKeys as $searchKey) {
            foreach ($rowKeys as $rowKey) {
                if (strtolower($searchKey) === strtolower($rowKey) && !empty(trim($row[$rowKey]))) {
                    return trim($row[$rowKey]);
                }
            }
        }

        // Finally try partial matches (contains)
        foreach ($possibleKeys as $searchKey) {
            foreach ($rowKeys as $rowKey) {
                $searchLower = strtolower($searchKey);
                $rowKeyLower = strtolower($rowKey);

                // Check if the row key contains the search key or vice versa
                if ((strpos($rowKeyLower, $searchLower) !== false || strpos($searchLower, $rowKeyLower) !== false)
                    && !empty(trim($row[$rowKey]))) {
                    return trim($row[$rowKey]);
                }
            }
        }

        return '';
    }

    /**
     * Parse date from various formats
     */
    protected function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        // Try different date formats
        $formats = ['Y-m-d', 'm/d/Y', 'd/m/Y', 'Y/m/d', 'm-d-Y', 'd-m-Y'];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $dateString);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        // If no format matches, try strtotime
        $timestamp = strtotime($dateString);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }

        return null;
    }

    protected function updateExistingStudent($student, $data)
    {
        // Mark as registrar-managed data
        $data['registrar_data_uploaded'] = true;
        $data['registrar_upload_date'] = now();
        $data['allow_profile_edit'] = false; // Prevent students from editing their profiles

        // Don't update password if student already has one and it's not temporary
        if (!$student->is_temporary_account && $student->password) {
            unset($data['password']);
        }

        $student->update($data);
    }

    protected function createNewStudent($data)
    {
        // Generate a temporary password if not provided
        if (empty($data['password'])) {
            $data['password'] = Hash::make('temp' . $data['student_id']);
            $data['is_temporary_account'] = true;
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        // Mark as registrar-managed data
        $data['registrar_data_uploaded'] = true;
        $data['registrar_upload_date'] = now();
        $data['allow_profile_edit'] = false; // Prevent students from editing their profiles
        $data['profile_completed'] = false;

        Student::create($data);
    }



    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getUpdateCount()
    {
        return $this->updateCount;
    }

    public function getSkipCount()
    {
        return $this->skipCount;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    public function getSummary()
    {
        return [
            'success_count' => $this->successCount,
            'update_count' => $this->updateCount,
            'skip_count' => $this->skipCount,
            'error_count' => count($this->errors),
            'errors' => $this->errors,
            'detected_columns' => $this->detectedColumns,
        ];
    }

    public function getDetectedColumns()
    {
        return $this->detectedColumns;
    }

    /**
     * Configure chunk reading for better memory management
     */
    public function chunkSize(): int
    {
        return env('EXCEL_CHUNK_SIZE', 500);
    }

    /**
     * Configure batch inserts for better performance
     */
    public function batchSize(): int
    {
        return env('EXCEL_CHUNK_SIZE', 500);
    }
}
