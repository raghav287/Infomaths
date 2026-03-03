<?php
require 'database.php';

// PU CET PG (ID 5) Correct Pattern (75 Qs total)
// Mathematics: 40
// Computer Science: 15
// Reasoning: 10
// English: 10
// But the user's table requested 3 rows: Math, CS, English & Reasoning.
// So I will combine English + Reasoning = 20.

$examName = "PU CET PG";
$examSlug = "pu_cet_pg";

$syllabusItems = [
    'Mathematics (10+2 Level)' => '<p>Algebra, Matrices and Determinants, Trigonometry, Coordinate Geometry, Calculus (Differential & Integral), Probability, Statistics.</p>',
    'Computer Science' => '<p>Basic Organization of Computer, Number Systems (Binary, Hex, Octal), Logic Gates, Data Structures, Fundamentals of C/C++ Programming, Operating System basics.</p>',
    'English & Reasoning' => '<p><strong>Reasoning:</strong> Logical sequences, Analogy, Classification, Blood Relations, Series.<br><strong>English:</strong> Vocabulary, Grammar, Synonyms/Antonyms, Reading Comprehension.</p>'
];

$syllabusAccordion = '';
$index = 1;
foreach ($syllabusItems as $title => $content) {
    $collapseId = "collapseSyll_" . md5($title . $examName);
    $headingId = "headingSyll_" . md5($title . $examName);
    
    $syllabusAccordion .= <<<HTML
        <div class="accordion-item" style="border: 1px solid #e0e0e0; margin-bottom: 10px; border-radius: 8px; overflow: hidden;">
            <h2 class="accordion-header" id="$headingId">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#$collapseId" aria-expanded="false" aria-controls="$collapseId" style="background-color: #f8f9fa; color: #1C56E1; font-weight: 600;">
                    $index. $title
                </button>
            </h2>
            <div id="$collapseId" class="accordion-collapse collapse" aria-labelledby="$headingId" data-bs-parent="#accordion_syllabus">
                <div class="accordion-body" style="background: #fff; padding: 20px;">
                    $content
                </div>
            </div>
        </div>
HTML;
    $index++;
}

$html = <<<HTML
<div class="exam-details-container" style="font-family: 'Outfit', sans-serif;">

    <!-- Intro Section -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <h2 style="color: #1C56E1; font-weight: 700; margin-bottom: 20px;">
                PU CET PG 2026: Complete Guide
            </h2>
            <p style="font-size: 1.1rem; color: #555; line-height: 1.8;">
                PU CET (PG) is the entrance test conducted by Panjab University, Chandigarh for admission to its prestigious courses, including MCA. The exam tests a candidate's aptitude in Mathematics, Computer Science, and General English/Reasoning.
            </p>
        </div>
    </div>

    <!-- Exam Pattern Table -->
    <div class="row mb-5">
        <div class="col-lg-12">
             <h3 style="color: #1C56E1; font-weight: 600; margin-bottom: 20px;">
                PU CET PG Exam Pattern
            </h3>
            <table class="table table-bordered table-striped text-center align-middle" style="box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                <thead class="table-dark">
                    <tr>
                        <th style="padding: 15px;">Section / Subject</th>
                        <th style="padding: 15px;">No. of Questions</th>
                        <th style="padding: 15px;">Marks per Question</th>
                        <th style="padding: 15px;">Total Marks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-start" style="font-weight: 600; color: #1C56E1;">Mathematics</td>
                        <td>40</td>
                        <td>1</td>
                        <td>40</td>
                    </tr>
                    <tr>
                        <td class="text-start" style="font-weight: 600; color: #1C56E1;">Computer Science</td>
                        <td>15</td>
                        <td>1</td>
                        <td>15</td>
                    </tr>
                     <tr>
                        <td class="text-start" style="font-weight: 600; color: #1C56E1;">English & Reasoning</td>
                        <td>20</td>
                        <td>1</td>
                        <td>20</td>
                    </tr>
                    <tr class="table-light">
                        <td class="text-end fw-bold">Total</td>
                        <td class="fw-bold fs-5">75</td>
                        <td>-</td>
                        <td class="fw-bold fs-5">75</td>
                    </tr>
                </tbody>
            </table>
            <div class="alert alert-light border mt-3" role="alert">
                <small><i class="fas fa-info-circle text-primary me-2"></i> <strong>Note:</strong> Duration is 90 Minutes. There is 25% (0.25 marks) Negative Marking for wrong answers.</small>
            </div>
        </div>
    </div>

    <!-- Accordion Section -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <h3 style="color: #1C56E1; font-weight: 600; margin-bottom: 25px;">
                Complete Syllabus
            </h3>
            
            <div class="accordion" id="accordion_syllabus">
                $syllabusAccordion
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="alert alert-primary d-flex align-items-center" role="alert" style="background-color: #e7f1ff; border-color: #b6d4fe;">
                <i class="fas fa-graduation-cap fa-2x me-3" style="color: #0d6efd;"></i>
                <div>
                   <strong>Ready to Crack PU CET PG 2026?</strong>
                   <a href="#" class="alert-link">Join Infomaths Today</a> for expert coaching, mock tests, and comprehensive study material.
                </div>
            </div>
        </div>
    </div>

</div>
HTML;

try {
    $stmt = $pdo->prepare("UPDATE entrance_exams SET full_description = ? WHERE id = 5");
    $stmt->execute([$html]);
    echo "Updated PU CET PG (ID 5) with correct 75-Question pattern.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
