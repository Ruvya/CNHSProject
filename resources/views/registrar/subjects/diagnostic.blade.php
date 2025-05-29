<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Subjects Diagnostic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .diagnostic-card { transition: transform 0.2s; }
        .diagnostic-card:hover { transform: translateY(-2px); }
        .code-block { background: #f8f9fa; border-left: 4px solid #007bff; padding: 15px; margin: 10px 0; font-family: 'Courier New', monospace; }
        .data-table { font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h1 class="mb-0"><i class="fas fa-stethoscope me-3"></i>Registrar Subjects Diagnostic</h1>
                        <p class="mb-0 mt-2 opacity-75">Analyzing why Registrars can't see Admin-created subjects</p>
                    </div>
                    <div class="card-body p-5">

                        <!-- Database Analysis -->
                        <h2 class="mb-4"><i class="fas fa-database text-primary me-2"></i>Database Analysis</h2>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card diagnostic-card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-table me-2"></i>All Subjects in Database</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="code-block">
                                            <?php
                                            try {
                                                $allSubjects = \App\Models\Subject::all();
                                                echo "<strong>Total Subjects: " . $allSubjects->count() . "</strong><br><br>";

                                                if ($allSubjects->count() > 0) {
                                                    echo "<div class='table-responsive'>";
                                                    echo "<table class='table table-sm data-table'>";
                                                    echo "<thead><tr><th>ID</th><th>Name</th><th>Code</th><th>Grade</th><th>Strand</th></tr></thead>";
                                                    echo "<tbody>";
                                                    foreach ($allSubjects->take(10) as $subject) {
                                                        $name = $subject->name ?? $subject->subject_name ?? 'N/A';
                                                        $code = $subject->code ?? $subject->subject_code ?? 'N/A';
                                                        echo "<tr>";
                                                        echo "<td>{$subject->id}</td>";
                                                        echo "<td>" . htmlspecialchars($name) . "</td>";
                                                        echo "<td>" . htmlspecialchars($code) . "</td>";
                                                        echo "<td>" . htmlspecialchars($subject->grade_level ?? 'N/A') . "</td>";
                                                        echo "<td>" . htmlspecialchars($subject->strand ?? 'N/A') . "</td>";
                                                        echo "</tr>";
                                                    }
                                                    echo "</tbody></table>";
                                                    echo "</div>";
                                                    if ($allSubjects->count() > 10) {
                                                        echo "<small class='text-muted'>Showing first 10 of {$allSubjects->count()} subjects</small>";
                                                    }
                                                } else {
                                                    echo "<span class='text-warning'>No subjects found in database!</span>";
                                                }
                                            } catch (\Exception $e) {
                                                echo "<span class='text-danger'>Error: " . $e->getMessage() . "</span>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card diagnostic-card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Grade Level Analysis</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="code-block">
                                            <?php
                                            try {
                                                $gradeLevels = \App\Models\Subject::select('grade_level')
                                                    ->selectRaw('COUNT(*) as count')
                                                    ->groupBy('grade_level')
                                                    ->get();

                                                echo "<strong>Subjects by Grade Level:</strong><br><br>";
                                                foreach ($gradeLevels as $grade) {
                                                    echo "• " . ($grade->grade_level ?? 'NULL') . ": {$grade->count} subjects<br>";
                                                }

                                                echo "<br><strong>Strand Analysis:</strong><br><br>";
                                                $strands = \App\Models\Subject::select('strand')
                                                    ->selectRaw('COUNT(*) as count')
                                                    ->groupBy('strand')
                                                    ->get();

                                                foreach ($strands as $strand) {
                                                    echo "• " . ($strand->strand ?? 'NULL') . ": {$strand->count} subjects<br>";
                                                }
                                            } catch (\Exception $e) {
                                                echo "<span class='text-danger'>Error: " . $e->getMessage() . "</span>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Registrar View Logic Analysis -->
                        <h2 class="mb-4"><i class="fas fa-filter text-success me-2"></i>Registrar View Logic Analysis</h2>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card diagnostic-card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-search me-2"></i>Grade 11 Filtering Test</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="code-block">
                                            <?php
                                            try {
                                                // Test different grade level formats
                                                $grade11_numeric = \App\Models\Subject::where('grade_level', '11')->count();
                                                $grade11_text = \App\Models\Subject::where('grade_level', 'Grade 11')->count();
                                                $grade11_like = \App\Models\Subject::where('grade_level', 'LIKE', '%11%')->count();

                                                echo "<strong>Grade 11 Filtering Results:</strong><br><br>";
                                                echo "• grade_level = '11': {$grade11_numeric} subjects<br>";
                                                echo "• grade_level = 'Grade 11': {$grade11_text} subjects<br>";
                                                echo "• grade_level LIKE '%11%': {$grade11_like} subjects<br><br>";

                                                echo "<strong>HUMSS Strand Test:</strong><br><br>";
                                                $humss_grade11 = \App\Models\Subject::where('grade_level', '11')->where('strand', 'HUMSS')->count();
                                                $humss_grade11_text = \App\Models\Subject::where('grade_level', 'Grade 11')->where('strand', 'HUMSS')->count();

                                                echo "• Grade 11 + HUMSS (numeric): {$humss_grade11} subjects<br>";
                                                echo "• Grade 11 + HUMSS (text): {$humss_grade11_text} subjects<br>";
                                            } catch (\Exception $e) {
                                                echo "<span class='text-danger'>Error: " . $e->getMessage() . "</span>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card diagnostic-card border-secondary">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0"><i class="fas fa-search me-2"></i>Grade 12 Filtering Test</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="code-block">
                                            <?php
                                            try {
                                                // Test different grade level formats
                                                $grade12_numeric = \App\Models\Subject::where('grade_level', '12')->count();
                                                $grade12_text = \App\Models\Subject::where('grade_level', 'Grade 12')->count();
                                                $grade12_like = \App\Models\Subject::where('grade_level', 'LIKE', '%12%')->count();

                                                echo "<strong>Grade 12 Filtering Results:</strong><br><br>";
                                                echo "• grade_level = '12': {$grade12_numeric} subjects<br>";
                                                echo "• grade_level = 'Grade 12': {$grade12_text} subjects<br>";
                                                echo "• grade_level LIKE '%12%': {$grade12_like} subjects<br><br>";

                                                echo "<strong>HUMSS Strand Test:</strong><br><br>";
                                                $humss_grade12 = \App\Models\Subject::where('grade_level', '12')->where('strand', 'HUMSS')->count();
                                                $humss_grade12_text = \App\Models\Subject::where('grade_level', 'Grade 12')->where('strand', 'HUMSS')->count();

                                                echo "• Grade 12 + HUMSS (numeric): {$humss_grade12} subjects<br>";
                                                echo "• Grade 12 + HUMSS (text): {$humss_grade12_text} subjects<br>";
                                            } catch (\Exception $e) {
                                                echo "<span class='text-danger'>Error: " . $e->getMessage() . "</span>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Problem Identification -->
                        <h2 class="mb-4"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Problem Identification</h2>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card diagnostic-card border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <h6 class="mb-0"><i class="fas fa-bug me-2"></i>Identified Issues</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i><strong>Grade Level Format Mismatch:</strong> View expects '11'/'12' but data might be 'Grade 11'/'Grade 12'</li>
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i><strong>Strand Filtering Issue:</strong> Subjects might not have strand values set</li>
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i><strong>Field Name Confusion:</strong> Using 'name' vs 'subject_name' inconsistently</li>
                                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i><strong>Collection Filtering:</strong> View uses Collection filtering instead of database queries</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card diagnostic-card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-wrench me-2"></i>Recommended Solutions</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Normalize Grade Levels:</strong> Ensure consistent format across system</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Fix View Logic:</strong> Update filtering to match actual data format</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Add Fallback Logic:</strong> Handle both numeric and text grade formats</li>
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Improve Performance:</strong> Use database queries instead of collection filtering</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <h2 class="mb-4"><i class="fas fa-link text-secondary me-2"></i>Quick Navigation</h2>

                        <div class="row g-3 mb-5">
                            <div class="col-md-3">
                                <a href="{{ route('registrar.login') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>Registrar Login
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('registrar.subjects.index') }}" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-book me-2"></i>Registrar Subjects
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.subjects.index') }}" class="btn btn-info btn-lg w-100">
                                    <i class="fas fa-list me-2"></i>Admin Subjects
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="/registrar-subjects-fixed" class="btn btn-outline-success btn-lg w-100">
                                    <i class="fas fa-tools me-2"></i>View Fixed Version
                                </a>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="text-center mt-5">
                            <h3 class="text-primary mb-3">🔍 Registrar Subjects Diagnostic Complete</h3>
                            <p class="text-muted mb-4">Use the analysis above to identify why registrars can't see admin-created subjects.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
