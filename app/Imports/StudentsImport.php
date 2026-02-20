<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class StudentsImport implements ToCollection, WithChunkReading, WithBatchInserts
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
        if ($rows->isEmpty()) {
            $this->errors[] = 'The uploaded file is empty.';
            return;
        }

        // Find the header row by looking for rows with "First Name", "Last Name", etc.
        $headerRowIndex = $this->findHeaderRow($rows);

        if ($headerRowIndex === -1) {
            $this->errors[] = 'Could not find header row with required columns (First Name, Last Name, Student ID/LRN). Please ensure your Excel file has proper column headers.';
            return;
        }

        // Get the header row and create column mapping
        $headerRow = $rows->get($headerRowIndex);
        $columnMapping = $this->createColumnMapping($headerRow);

        // Debug: Log detected columns
        Log::info("Excel file analysis", [
            'header_row_index' => $headerRowIndex,
            'detected_columns' => $headerRow->toArray(),
            'column_mapping' => $columnMapping
        ]);

        // Process data rows (skip header and any rows before it)
        $rowNumber = $headerRowIndex + 1; // Start counting from header row

        foreach ($rows as $index => $row) {
            // Skip rows before and including header
            if ($index <= $headerRowIndex) {
                continue;
            }

            $rowNumber++;

            try {
                // Skip empty rows
                if ($this->isEmptyRow($row)) {
                    $this->skipCount++;
                    continue;
                }

                $this->processStudentRowWithMapping($row, $rowNumber, $columnMapping);
            } catch (\Exception $e) {
                $this->errors[] = "Row $rowNumber: " . $e->getMessage();
            }
        }
    }

    protected function processStudentRowWithMapping($row, $rowNumber, $columnMapping)
    {
        // Extract data using column mapping
        $studentData = $this->extractDataWithMapping($row, $columnMapping);

        // Always generate a proper Student ID in YYYY-NNNN format
        if (empty($studentData['student_id']) || !preg_match('/^\d{4}-\d{4}$/', $studentData['student_id'])) {
            // Generate a unique student ID automatically
            $studentData['student_id'] = $this->generateUniqueStudentId();
        }

        // Log the student data for debugging
        Log::info("Processing student row with mapping", [
            'row_number' => $rowNumber,
            'student_data_keys' => array_keys($studentData),
            'first_name' => $studentData['first_name'] ?? 'NOT_FOUND',
            'last_name' => $studentData['last_name'] ?? 'NOT_FOUND',
            'student_id' => $studentData['student_id'] ?? 'NOT_FOUND',
        ]);

        // Check if we have at least first name or last name
        $hasFirstName = !empty($studentData['first_name']);
        $hasLastName = !empty($studentData['last_name']);

        if (!$hasFirstName && !$hasLastName) {
            // If no names found, provide detailed error with column suggestions
            $availableColumns = array_keys($columnMapping);
            $this->errors[] = "Row $rowNumber: No name information found. Please ensure columns 'First Name' and 'Last Name' are present and contain data. Available mapped columns: " . implode(', ', $availableColumns);
            return;
        }

        // Check for duplicates using multiple criteria
        $duplicateCheck = $this->checkForDuplicates($studentData, $rowNumber);
        if ($duplicateCheck['is_duplicate']) {
            $this->skipCount++;
            $this->errors[] = "Row $rowNumber: " . $duplicateCheck['message'];
            return;
        }

        // Check if student already exists to determine validation rules
        $existingStudent = $duplicateCheck['existing_student'];

        // More flexible validation - only require student_id, make names nullable if empty
        $validationRules = [
            'student_id' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'grade_level' => 'required|string|max:50',
            'gender' => 'nullable|in:Male,Female',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'civil_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'track' => 'nullable|string|max:100',
            'cluster' => 'nullable|string|max:100',
        ];

        // Only require password for new students
        if (!$existingStudent) {
            $validationRules['password'] = 'required|string';
        }

        // Require names if they have values, but allow missing middle name
        if ($hasFirstName) {
            $validationRules['first_name'] = 'required|string|max:255';
        }
        if ($hasLastName) {
            $validationRules['last_name'] = 'required|string|max:255';
        }

        $validator = Validator::make($studentData, $validationRules, [
            'student_id.required' => 'Student ID is required',
            'email.required' => 'Email is required (auto-generated if not provided)',
            'email.email' => 'The email field must be a valid email address',
            'password.required' => 'Password is required (auto-generated if not provided)',
            'grade_level.required' => 'Grade level is required',
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'gender.in' => 'The gender field must be exactly "Male" or "Female" (case-sensitive). Accepted variations: male, m, female, f, man, woman, boy, girl, lalaki, babae. Leave empty if unknown.',
            'civil_status.in' => 'The civil status must be one of: Single, Married, Divorced, Widowed',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            // Log the problematic data for debugging
            Log::warning("Student validation failed", [
                'row_number' => $rowNumber,
                'student_data' => $studentData,
                'validation_errors' => $errors,
            ]);

            $this->errors[] = "Row $rowNumber: Validation failed: " . implode(', ', $errors);
            return;
        }

        if ($existingStudent) {
            // Update existing student
            $existingStudent->update($studentData);
            $this->updateCount++;
        } else {
            // Create new student
            Student::create($studentData);
            $this->successCount++;
        }
    }

    protected function processStudentRow($row, $rowNumber)
    {
        // Clean and prepare data
        $studentData = $this->prepareStudentData($row);

        // Always generate a proper Student ID in YYYY-NNNN format
        if (empty($studentData['student_id']) || !preg_match('/^\d{4}-\d{4}$/', $studentData['student_id'])) {
            // Generate a unique student ID automatically
            $studentData['student_id'] = $this->generateUniqueStudentId();
        }

        // Ensure required fields have values
        $studentData = $this->ensureRequiredFields($studentData);

        // Log the student data for debugging
        Log::info("Processing student row", [
            'row_number' => $rowNumber,
            'detected_columns' => array_keys($row->toArray()),
            'student_data_keys' => array_keys($studentData),
            'first_name' => $studentData['first_name'] ?? 'NOT_FOUND',
            'last_name' => $studentData['last_name'] ?? 'NOT_FOUND',
            'student_id' => $studentData['student_id'] ?? 'NOT_FOUND',
        ]);

        // Check for duplicates using multiple criteria
        $duplicateCheck = $this->checkForDuplicates($studentData, $rowNumber);
        if ($duplicateCheck['is_duplicate']) {
            $this->skipCount++;
            $this->errors[] = "Row $rowNumber: " . $duplicateCheck['message'];
            return;
        }

        // Check if student already exists to determine validation rules
        $existingStudent = $duplicateCheck['existing_student'];

        // Validation rules for required fields
        $validationRules = [
            'student_id' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'grade_level' => 'required|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'nullable|in:Male,Female',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'civil_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'track' => 'nullable|string|max:100',
            'cluster' => 'nullable|string|max:100',
        ];

        // Only require password for new students
        if (!$existingStudent) {
            $validationRules['password'] = 'required|string';
        }

        $validator = Validator::make($studentData, $validationRules, [
            'student_id.required' => 'Student ID is required',
            'email.required' => 'Email is required (auto-generated if not provided)',
            'email.email' => 'The email field must be a valid email address',
            'password.required' => 'Password is required (auto-generated if not provided)',
            'grade_level.required' => 'Grade level is required',
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'gender.in' => 'The gender field must be exactly "Male" or "Female" (case-sensitive). Accepted variations: male, m, female, f, man, woman, boy, girl, lalaki, babae. Leave empty if unknown.',
            'civil_status.in' => 'The civil status must be one of: Single, Married, Divorced, Widowed',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            // Log the problematic data for debugging
            Log::warning("Student validation failed", [
                'row_number' => $rowNumber,
                'student_data' => $studentData,
                'validation_errors' => $errors,
                'gender_value' => $studentData['gender'] ?? 'NOT_SET',
                'email_value' => $studentData['email'] ?? 'NOT_SET'
            ]);

            throw new \Exception("Validation failed: " . implode(', ', $errors));
        }

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
     * Check for duplicate students using multiple criteria
     */
    protected function checkForDuplicates($studentData, $rowNumber)
    {
        $existingStudent = null;
        $duplicateReasons = [];

        // Check for duplicate by LRN (if provided)
        if (!empty($studentData['lrn'])) {
            $existingByLrn = Student::where('lrn', $studentData['lrn'])->first();
            if ($existingByLrn) {
                $duplicateReasons[] = "LRN '{$studentData['lrn']}' already exists for student {$existingByLrn->student_id} ({$existingByLrn->first_name} {$existingByLrn->last_name})";
                $existingStudent = $existingByLrn;
            }
        }

        // Check for duplicate by email (if provided)
        if (!empty($studentData['email'])) {
            $existingByEmail = Student::where('email', $studentData['email'])->first();
            if ($existingByEmail && (!$existingStudent || $existingByEmail->id !== $existingStudent->id)) {
                $duplicateReasons[] = "Email '{$studentData['email']}' already exists for student {$existingByEmail->student_id} ({$existingByEmail->first_name} {$existingByEmail->last_name})";
                $existingStudent = $existingByEmail;
            }
        }

        // Check for duplicate by name combination (first name + last name + date of birth if available)
        if (!empty($studentData['first_name']) && !empty($studentData['last_name'])) {
            $nameQuery = Student::where('first_name', $studentData['first_name'])
                               ->where('last_name', $studentData['last_name']);

            // Add middle name to the check if available
            if (!empty($studentData['middle_name'])) {
                $nameQuery->where('middle_name', $studentData['middle_name']);
            }

            // Add date of birth to the check if available
            if (!empty($studentData['date_of_birth'])) {
                $nameQuery->where('date_of_birth', $studentData['date_of_birth']);
            }

            $existingByName = $nameQuery->first();
            if ($existingByName && (!$existingStudent || $existingByName->id !== $existingStudent->id)) {
                $nameMatch = "{$studentData['first_name']} {$studentData['last_name']}";
                if (!empty($studentData['middle_name'])) {
                    $nameMatch = "{$studentData['first_name']} {$studentData['middle_name']} {$studentData['last_name']}";
                }
                $duplicateReasons[] = "Student with name '{$nameMatch}' already exists as student {$existingByName->student_id}";
                $existingStudent = $existingByName;
            }
        }

        // If duplicates found, return error
        if (!empty($duplicateReasons)) {
            return [
                'is_duplicate' => true,
                'message' => 'Duplicate student detected - ' . implode('; ', $duplicateReasons) . '. Student will be skipped to prevent duplicates.',
                'existing_student' => $existingStudent
            ];
        }

        return [
            'is_duplicate' => false,
            'message' => '',
            'existing_student' => null
        ];
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
        // Use smart column mapping for both old format and new SF1-SHS format
        $data = [
            // Get LRN from the file but don't use it as student_id
            'lrn' => $this->getColumnValue($row, ['lrn', 'LRN', 'learner_reference_number', 'learner-reference-number']),
            // Only use existing student_id if it's in proper YYYY-NNNN format
            'student_id' => $this->getColumnValue($row, ['student_id', 'Student ID']),

            // SF1-SHS format names with enhanced detection
            'first_name' => $this->getColumnValue($row, ['first_name', 'First Name', 'first-name', 'firstname', 'fname', 'given_name', 'given name']),
            'middle_name' => $this->getColumnValue($row, ['middle_name', 'Middle Name', 'middle-name', 'middlename', 'mname', 'middle initial']),
            'last_name' => $this->getColumnValue($row, ['last_name', 'Last Name', 'last-name', 'lastname', 'lname', 'surname', 'family_name', 'family name']),

            // Email (not in SF1-SHS but keep for compatibility)
            'email' => $this->cleanEmail($this->getColumnValue($row, ['email', 'email_address', 'email-address'])),

            // SF1-SHS format fields
            'grade_level' => $this->getColumnValue($row, ['grade_level', 'Grade Level', 'grade-level', 'gradelevel', 'grade']),
            'section' => $this->getColumnValue($row, ['section', 'Section']),
            'track' => $this->getColumnValue($row, ['track', 'Track']),
            'cluster' => $this->getColumnValue($row, ['cluster', 'Cluster', 'strand', 'Strand']),

            // Gender/Sex mapping
            'gender' => $this->cleanGender($this->getColumnValue($row, ['gender', 'sex', 'Sex'])),

            // Date fields
            'date_of_birth' => $this->parseDate($this->getColumnValue($row, ['date_of_birth', 'Date of Birth', 'date-of-birth', 'dateofbirth', 'dob', 'birthdate'])),

            // Place and personal info
            'place_of_birth' => $this->getColumnValue($row, ['place_of_birth', 'Place of Birth', 'place-of-birth', 'placeofbirth', 'birthplace']),
            'nationality' => $this->getColumnValue($row, ['nationality', 'IP/Ethnicity']),
            'religion' => $this->getColumnValue($row, ['religion', 'Religion']),
            'civil_status' => $this->getColumnValue($row, ['civil_status', 'civil-status', 'civilstatus', 'marital_status', 'marital-status']),

            // Address fields
            'address' => $this->getColumnValue($row, ['address', 'Complete Address', 'home_address', 'home-address']),
            'contact_number' => $this->getColumnValue($row, ['contact_number', 'Contact Number', 'contact-number', 'contactnumber', 'phone', 'mobile']),

            // Parent/Guardian information (SF1-SHS format)
            'parent_name' => $this->getColumnValue($row, ['parent_name', 'Father\'s Name', 'Mother\'s Name', 'Guardian\'s Name', 'parent-name', 'parentname', 'guardian_name', 'guardian-name']),
            'parent_contact' => $this->getColumnValue($row, ['parent_contact', 'parent-contact', 'parentcontact', 'guardian_contact', 'guardian-contact']),

            // School information
            'advisor' => $this->getColumnValue($row, ['advisor', 'Adviser', 'class_advisor', 'class-advisor']),

            // Legacy fields for backward compatibility
            'profile_picture' => $this->getColumnValue($row, ['profile_picture', 'profile-picture', 'profilepicture', 'photo']),
            'province' => $this->getColumnValue($row, ['province']),
            'municipality' => $this->getColumnValue($row, ['municipality', 'city']),
            'barangay' => $this->getColumnValue($row, ['barangay']),
            'permanent_address' => $this->getColumnValue($row, ['permanent_address', 'permanent-address', 'permanentaddress']),
            'phone' => $this->getColumnValue($row, ['phone', 'telephone']),
            'emergency_name' => $this->getColumnValue($row, ['emergency_name', 'emergency-name', 'emergencyname', 'emergency_contact_name', 'emergency-contact-name']),
            'emergency_phone' => $this->getColumnValue($row, ['emergency_phone', 'emergency-phone', 'emergencyphone', 'emergency_contact_number', 'emergency-contact-number']),
            'emergency_relationship' => $this->getColumnValue($row, ['emergency_relationship', 'emergency-relationship', 'emergencyrelationship', 'emergency_contact_relationship', 'emergency-contact-relationship']),
        ];

        // Handle missing names by trying to extract from full name
        if (empty($data['first_name']) || empty($data['last_name'])) {
            $fullName = $this->getColumnValue($row, ['name', 'full_name', 'full name', 'student_name', 'student name']);
            if (!empty($fullName)) {
                $nameParts = $this->parseFullName($fullName);
                if (empty($data['first_name']) && !empty($nameParts['first_name'])) {
                    $data['first_name'] = $nameParts['first_name'];
                }
                if (empty($data['middle_name']) && !empty($nameParts['middle_name'])) {
                    $data['middle_name'] = $nameParts['middle_name'];
                }
                if (empty($data['last_name']) && !empty($nameParts['last_name'])) {
                    $data['last_name'] = $nameParts['last_name'];
                }
            }
        }

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

                    Log::info("Found potential student_id column", [
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
                    Log::info("Using first non-empty column as student_id", [
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

    /**
     * Clean and validate email address
     */
    protected function cleanEmail($email)
    {
        if (empty($email)) {
            return null; // Return null for empty emails (they're nullable)
        }

        $email = trim(strtolower($email));

        // Basic email validation
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        // If email is invalid, return null instead of invalid email
        return null;
    }

    /**
     * Parse a full name into first, middle, and last name components
     */
    protected function parseFullName($fullName)
    {
        $fullName = trim($fullName);
        if (empty($fullName)) {
            return ['first_name' => '', 'middle_name' => '', 'last_name' => ''];
        }

        // Split the name into parts
        $nameParts = explode(' ', $fullName);
        $nameParts = array_filter($nameParts); // Remove empty parts
        $nameParts = array_values($nameParts); // Re-index

        $result = ['first_name' => '', 'middle_name' => '', 'last_name' => ''];

        if (count($nameParts) == 1) {
            // Only one name part - treat as first name
            $result['first_name'] = $nameParts[0];
        } elseif (count($nameParts) == 2) {
            // Two parts - first and last name
            $result['first_name'] = $nameParts[0];
            $result['last_name'] = $nameParts[1];
        } elseif (count($nameParts) >= 3) {
            // Three or more parts - first, middle, and last
            $result['first_name'] = $nameParts[0];
            $result['last_name'] = end($nameParts); // Last element

            // Everything in between is middle name
            $middleParts = array_slice($nameParts, 1, -1);
            $result['middle_name'] = implode(' ', $middleParts);
        }

        return $result;
    }

    /**
     * Find the header row by looking for key columns
     */
    protected function findHeaderRow(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowArray = $row->toArray();
            $rowValues = array_map('strtolower', array_map('trim', $rowArray));

            // Look for key indicators of a header row
            $hasFirstName = false;
            $hasLastName = false;
            $hasStudentId = false;

            foreach ($rowValues as $value) {
                if (in_array($value, ['first name', 'firstname', 'first_name', 'given name'])) {
                    $hasFirstName = true;
                }
                if (in_array($value, ['last name', 'lastname', 'last_name', 'surname', 'family name'])) {
                    $hasLastName = true;
                }
                if (in_array($value, ['student id', 'student_id', 'studentid', 'lrn', 'id', 'student no', 'student number'])) {
                    $hasStudentId = true;
                }
            }

            // If we found at least 2 of the 3 key columns, this is likely the header
            if (($hasFirstName && $hasLastName) || ($hasFirstName && $hasStudentId) || ($hasLastName && $hasStudentId)) {
                return $index;
            }
        }

        return -1; // Header row not found
    }

    /**
     * Create column mapping from header row
     */
    protected function createColumnMapping($headerRow)
    {
        $mapping = [];
        $headerArray = $headerRow->toArray();

        foreach ($headerArray as $index => $columnName) {
            $cleanName = strtolower(trim($columnName));

            // Map various column name variations to standard field names
            if (in_array($cleanName, ['first name', 'firstname', 'first_name', 'given name'])) {
                $mapping['first_name'] = $index;
            } elseif (in_array($cleanName, ['last name', 'lastname', 'last_name', 'surname', 'family name'])) {
                $mapping['last_name'] = $index;
            } elseif (in_array($cleanName, ['middle name', 'middlename', 'middle_name', 'middle initial'])) {
                $mapping['middle_name'] = $index;
            } elseif (in_array($cleanName, ['name', 'full name', 'full_name', 'student name', 'student_name', 'complete name'])) {
                $mapping['full_name'] = $index;
            } elseif (in_array($cleanName, ['lrn', 'learner reference number', 'learner_reference_number'])) {
                $mapping['lrn'] = $index;
                // Don't automatically map LRN to student_id anymore
            } elseif (in_array($cleanName, ['student id', 'student_id', 'studentid', 'id', 'student no', 'student number'])) {
                // Map to student_id only if it's in proper format
                $mapping['student_id'] = $index;
            } elseif (in_array($cleanName, ['email', 'email address', 'email_address'])) {
                $mapping['email'] = $index;
            } elseif (in_array($cleanName, ['grade level', 'grade_level', 'gradelevel', 'grade'])) {
                $mapping['grade_level'] = $index;
            } elseif (in_array($cleanName, ['section'])) {
                $mapping['section'] = $index;
            } elseif (in_array($cleanName, ['track'])) {
                $mapping['track'] = $index;
            } elseif (in_array($cleanName, ['cluster', 'strand'])) {
                $mapping['cluster'] = $index;
            } elseif (in_array($cleanName, ['gender', 'sex'])) {
                $mapping['gender'] = $index;
            } elseif (in_array($cleanName, ['date of birth', 'date_of_birth', 'dateofbirth', 'dob', 'birthdate'])) {
                $mapping['date_of_birth'] = $index;
            } elseif (in_array($cleanName, ['place of birth', 'place_of_birth', 'placeofbirth', 'birth place'])) {
                $mapping['place_of_birth'] = $index;
            } elseif (in_array($cleanName, ['nationality'])) {
                $mapping['nationality'] = $index;
            } elseif (in_array($cleanName, ['religion'])) {
                $mapping['religion'] = $index;
            } elseif (in_array($cleanName, ['civil status', 'civil_status', 'civilstatus', 'marital status'])) {
                $mapping['civil_status'] = $index;
            }
        }

        return $mapping;
    }

    /**
     * Extract student data using column mapping
     */
    protected function extractDataWithMapping($row, $columnMapping)
    {
        $rowArray = $row->toArray();
        $data = [];

        // Extract data based on column mapping
        foreach ($columnMapping as $field => $columnIndex) {
            $data[$field] = isset($rowArray[$columnIndex]) ? trim($rowArray[$columnIndex]) : '';
        }

        // Handle missing names by trying to extract from full name if available
        if ((empty($data['first_name']) || empty($data['last_name'])) && isset($columnMapping['full_name'])) {
            $fullName = $data['full_name'];
            if (!empty($fullName)) {
                $nameParts = $this->parseFullName($fullName);
                if (empty($data['first_name']) && !empty($nameParts['first_name'])) {
                    $data['first_name'] = $nameParts['first_name'];
                }
                if (empty($data['middle_name']) && !empty($nameParts['middle_name'])) {
                    $data['middle_name'] = $nameParts['middle_name'];
                }
                if (empty($data['last_name']) && !empty($nameParts['last_name'])) {
                    $data['last_name'] = $nameParts['last_name'];
                }
            }
        }

        // Clean and process specific fields
        if (isset($data['email'])) {
            $data['email'] = $this->cleanEmail($data['email']);
        }
        if (isset($data['gender'])) {
            $data['gender'] = $this->cleanGender($data['gender']);
        }
        if (isset($data['date_of_birth'])) {
            $data['date_of_birth'] = $this->parseDate($data['date_of_birth']);
        }

        // Apply selected grade level and track if provided
        if ($this->gradeLevel) {
            $data['grade_level'] = $this->gradeLevel;
        }
        if ($this->track) {
            $data['track'] = $this->track;
        }

        // Handle required fields with defaults
        $data = $this->ensureRequiredFields($data);

        return $data;
    }

    /**
     * Ensure all required database fields have values
     */
    protected function ensureRequiredFields($data)
    {
        // Always generate a proper Student ID in YYYY-NNNN format
        if (empty($data['student_id']) || !preg_match('/^\d{4}-\d{4}$/', $data['student_id'])) {
            // Generate a unique student ID automatically
            $data['student_id'] = $this->generateUniqueStudentId();
        }

        // Handle required email field
        if (empty($data['email'])) {
            // Generate a temporary email if none provided
            $studentId = $data['student_id'] ?? 'unknown';
            $baseEmail = strtolower($studentId) . '@temp.cnhs.edu.ph';

            // Check if email already exists and make it unique
            $counter = 1;
            $email = $baseEmail;
            while (Student::where('email', $email)->exists()) {
                $email = strtolower($studentId) . $counter . '@temp.cnhs.edu.ph';
                $counter++;
            }
            $data['email'] = $email;
        }

        // Handle required password field (only for new students)
        if (empty($data['password'])) {
            // Check if student already exists
            $existingStudent = Student::where('student_id', $data['student_id'])->first();
            if (!$existingStudent) {
                // Generate a default password for new students
                $data['password'] = Hash::make('password123'); // Default password
            }
        }

        // Handle required gender field
        if (empty($data['gender'])) {
            $data['gender'] = null; // Will be handled by making it nullable in validation
        }

        // Handle required grade_level field
        if (empty($data['grade_level'])) {
            // Use the selected grade level or default
            $data['grade_level'] = $this->gradeLevel ?? 'Grade 11';
        }

        // Handle required first_name and last_name
        if (empty($data['first_name']) && empty($data['last_name'])) {
            // If both are empty, use student_id as name
            $data['first_name'] = $data['student_id'] ?? 'Unknown';
            $data['last_name'] = 'Student';
        } elseif (empty($data['first_name'])) {
            $data['first_name'] = 'Unknown';
        } elseif (empty($data['last_name'])) {
            $data['last_name'] = 'Student';
        }

        return $data;
    }

    /**
     * Clean and standardize gender values
     */
    protected function cleanGender($gender)
    {
        // Log the original value for debugging
        Log::info('Processing gender value', [
            'original_value' => $gender,
            'is_empty' => empty($gender),
            'type' => gettype($gender)
        ]);

        if (empty($gender)) {
            Log::info('Gender is empty, returning null');
            return null; // Return null for empty gender (it's nullable)
        }

        $gender = trim($gender);
        $genderLower = strtolower($gender);

        Log::info('Cleaned gender value', [
            'trimmed_value' => $gender,
            'lowercase_value' => $genderLower
        ]);

        // Map common gender variations to standard values (including SF1-SHS format)
        $genderMap = [
            'male' => 'Male',
            'm' => 'Male',
            'man' => 'Male',
            'boy' => 'Male',
            'lalaki' => 'Male',
            'female' => 'Female',
            'f' => 'Female',
            'woman' => 'Female',
            'girl' => 'Female',
            'babae' => 'Female',
        ];

        if (isset($genderMap[$genderLower])) {
            $result = $genderMap[$genderLower];
            Log::info('Gender mapped successfully', [
                'input' => $gender,
                'output' => $result
            ]);
            return $result;
        }

        // If it's already in correct format, return as is
        if (in_array($gender, ['Male', 'Female'])) {
            Log::info('Gender already in correct format', ['value' => $gender]);
            return $gender;
        }

        // If gender doesn't match any known pattern, return null
        Log::warning('Gender value not recognized, returning null', [
            'input' => $gender,
            'available_mappings' => array_keys($genderMap)
        ]);
        return null;
    }

    protected function updateExistingStudent($student, $data)
    {
        // Store original data for comparison
        $originalData = $student->toArray();

        // Mark as registrar-managed data
        $data['registrar_data_uploaded'] = true;
        $data['registrar_upload_date'] = now();
        $data['allow_profile_edit'] = false; // Prevent students from editing their profiles

        // Don't update password if student already has one and it's not temporary
        if (!$student->is_temporary_account && $student->password) {
            unset($data['password']);
        }

        // Remove null values to avoid overwriting existing data with empty values
        $data = array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });

        // Log the update for tracking
        Log::info("Updating existing student profile", [
            'student_id' => $student->student_id,
            'student_name' => $student->getFullNameAttribute(),
            'fields_to_update' => array_keys($data),
            'original_track' => $originalData['track'] ?? null,
            'new_track' => $data['track'] ?? null,
            'original_cluster' => $originalData['cluster'] ?? null,
            'new_cluster' => $data['cluster'] ?? null,
        ]);

        // Update the student
        $student->update($data);

        // Check if critical academic fields changed (this will trigger subject reassignment via model events)
        $criticalFields = ['track', 'cluster', 'grade_level'];
        $changedFields = [];
        foreach ($criticalFields as $field) {
            if (isset($data[$field]) && $originalData[$field] !== $data[$field]) {
                $changedFields[] = $field;
            }
        }

        if (!empty($changedFields)) {
            Log::info("Critical academic fields changed for student", [
                'student_id' => $student->student_id,
                'changed_fields' => $changedFields,
                'will_trigger_subject_reassignment' => true
            ]);
        }

        // Log successful update
        Log::info("Student profile updated successfully", [
            'student_id' => $student->student_id,
            'updated_fields' => array_keys($data),
            'critical_fields_changed' => $changedFields
        ]);
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
            'duplicate_prevention' => 'Enabled - duplicates are automatically skipped',
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

    /**
     * Generate a unique student ID in the format YYYY-NNNN
     */
    protected function generateUniqueStudentId(): string
    {
        $year = date('Y');

        // For Excel imports, always start from 0001 and find next available number
        // This ensures Excel uploads get sequential IDs starting from 0001
        $startNumber = 1; // Always start checking from 0001

        // Find the next available number starting from 0001
        while ($startNumber <= 9999) {
            $candidateId = $year . '-' . str_pad($startNumber, 4, '0', STR_PAD_LEFT);

            // Check if this ID exists in students table only (ignore temp credentials)
            $exists = Student::where('student_id', $candidateId)->exists();

            if (!$exists) {
                return $candidateId;
            }

            $startNumber++;
        }

        // Fallback: use timestamp-based ID if we can't find an available sequential one
        return $year . '-' . str_pad(time() % 10000, 4, '0', STR_PAD_LEFT);
    }
}
