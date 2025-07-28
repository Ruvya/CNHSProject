# Profile Update Functionality Guide

## Overview
The Excel upload system now includes comprehensive profile update functionality that automatically updates student profiles based on uploaded Excel data. This ensures that student information is always current and accurate.

## How Profile Updates Work

### 1. Student Identification
- **Primary Match**: Student ID (first priority)
- **Secondary Match**: LRN (Learner Reference Number)
- **Fallback**: Smart column detection for student identification

### 2. Profile Update Process
When uploading an Excel file:

1. **File Processing**: System reads Excel/CSV file with SF1-SHS format
2. **Student Matching**: Each row is matched against existing students
3. **Data Validation**: All fields are validated before updating
4. **Profile Update**: ALL non-empty fields from Excel update the student profile
5. **Change Tracking**: System logs what fields were changed
6. **Subject Reassignment**: If track/cluster changes, subjects are automatically reassigned
7. **Profile Lock**: Students can no longer edit their profiles after registrar upload

### 3. Fields That Get Updated

#### Core Information
- **Student ID** - School-specific identifier
- **LRN** - Learner Reference Number
- **Name Fields** - First Name, Middle Name, Last Name, Name Extension
- **Email** - Contact email address

#### Personal Information
- **Date of Birth** - Student's birthdate
- **Age** - Student's age
- **Sex/Gender** - Male/Female
- **Place of Birth** - Birthplace
- **Mother Tongue** - Primary language
- **IP/Ethnicity** - Indigenous People/Ethnicity
- **Religion** - Religious affiliation
- **Civil Status** - Marital status

#### Contact & Address
- **Complete Address** - Full residential address
- **Contact Number** - Student's phone number
- **Province, Municipality, Barangay** - Address components
- **Permanent Address** - Permanent residence

#### Family Information
- **Father's Name** - Father's full name
- **Mother's Name** - Mother's full name
- **Guardian's Name** - Guardian's full name
- **Relationship** - Relationship to guardian
- **Parent Contact** - Parent/Guardian contact number

#### Academic Information
- **Grade Level** - Current grade level
- **Section** - Class section
- **Track** - Academic track (Academic, TVL, Sports, Arts)
- **Cluster** - Specific cluster/strand
- **Adviser** - Class adviser name
- **Date of Enrollment** - Enrollment date
- **School Year** - Academic year
- **Remarks** - Additional notes

## Key Features

### 1. Smart Field Mapping
```php
// System recognizes multiple column name variations
'first_name' => ['first_name', 'First Name', 'firstname', 'fname']
'gender' => ['gender', 'sex', 'Sex']
'cluster' => ['cluster', 'Cluster', 'strand', 'Strand']
```

### 2. Data Preservation
- **Non-destructive Updates**: Empty cells don't overwrite existing data
- **Password Protection**: Existing passwords are preserved
- **Selective Updates**: Only provided fields are updated

### 3. Change Tracking
```php
// System logs all changes
'student_id' => 'Updated student profile'
'fields_to_update' => ['track', 'cluster', 'grade_level']
'critical_fields_changed' => ['track', 'cluster']
'will_trigger_subject_reassignment' => true
```

### 4. Automatic Subject Assignment
- **Track Changes**: When track or cluster changes, subjects are reassigned
- **Grade Level Changes**: Subject assignments updated for new grade level
- **Immediate Effect**: Changes take effect immediately after upload

## Usage Instructions

### 1. Prepare Excel File
1. Download the SF1-SHS template
2. Fill in student data with complete information
3. Ensure Student ID or LRN is provided for each student
4. Use proper date formats (MM/DD/YYYY or YYYY-MM-DD)

### 2. Upload Process
1. Go to Registrar → Students → Upload Excel
2. Select Grade Level and Track (if applicable)
3. Choose your Excel file
4. Click "Upload & Import"
5. Review the import summary

### 3. Verify Updates
- Check import summary for update count
- Review student profiles to confirm changes
- Verify subject assignments if track/cluster changed

## Import Summary Information

After upload, you'll see:
- **Success Count**: New students created
- **Update Count**: Existing profiles updated
- **Skip Count**: Empty rows skipped
- **Error Count**: Rows with validation errors
- **Detected Columns**: Columns found in your file

## Benefits

### 1. Efficiency
- **Bulk Updates**: Update hundreds of student profiles at once
- **Time Saving**: No need to manually edit each profile
- **Consistency**: Ensures all data follows the same format

### 2. Accuracy
- **Validation**: All data is validated before updating
- **Error Reporting**: Clear error messages for invalid data
- **Change Tracking**: Complete audit trail of changes

### 3. Integration
- **Subject Assignment**: Automatic subject reassignment
- **Profile Lock**: Prevents conflicting manual edits
- **System Sync**: All related systems updated automatically

## Best Practices

### 1. Data Preparation
- Use the official SF1-SHS template
- Verify all data before uploading
- Include complete information for better profiles

### 2. Upload Strategy
- Upload during off-peak hours for large files
- Test with small batches first
- Keep backup of original data

### 3. Post-Upload
- Review import summary carefully
- Check for any error messages
- Verify critical student profiles manually

## Troubleshooting

### Common Issues
1. **Student Not Found**: Ensure Student ID or LRN matches exactly
2. **Validation Errors**: Check date formats and required fields
3. **Subject Assignment**: Verify track and cluster values are correct

### Error Resolution
- Review error messages in import summary
- Check column headers match template
- Verify data formats (especially dates and gender)

This comprehensive profile update system ensures that student information is always current and accurate while maintaining data integrity and system consistency.
