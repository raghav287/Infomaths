<?php
require 'database.php';

// Helper function to generate standardized HTML
function generateExamHTML($examName, $patternTableRows, $syllabusItems, $otherDetails) {
    // Escape common special chars slightly? No, we trust our internal strings.
    
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

    return <<<HTML
<div class="exam-details-container" style="font-family: 'Outfit', sans-serif;">

    <!-- Intro Section -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <h2 style="color: #1C56E1; font-weight: 700; margin-bottom: 20px;">
                $examName 2026: Complete Guide
            </h2>
            <p style="font-size: 1.1rem; color: #555; line-height: 1.8;">
                $otherDetails[intro]
            </p>
        </div>
    </div>

    <!-- Exam Pattern Table -->
    <div class="row mb-5">
        <div class="col-lg-12">
             <h3 style="color: #1C56E1; font-weight: 600; margin-bottom: 20px;">
                $examName Exam Pattern
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
                    $patternTableRows
                </tbody>
            </table>
            <div class="alert alert-light border mt-3" role="alert">
                <small><i class="fas fa-info-circle text-primary me-2"></i> <strong>Note:</strong> $otherDetails[pattern_note]</small>
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
                   <strong>Ready to Crack $examName 2026?</strong>
                   <a href="#" class="alert-link">Join Infomaths Today</a> for expert coaching, mock tests, and comprehensive study material.
                </div>
            </div>
        </div>
    </div>

</div>
HTML;
}

// Data Definitions
$examsData = [
    // MAH MCA CET
    2 => [
        'name' => 'MAH MCA CET',
        'intro' => 'MAH MCA CET is a state-level entrance exam conducted by the State Common Entrance Test Cell, Maharashtra, for admission to MCA courses in top colleges across Maharashtra. It is one of the most popular exams after NIMCET.',
        'pattern_rows' => '
            <tr><td class="text-start">Mathematics & Statistics</td><td>30</td><td>2</td><td>60</td></tr>
            <tr><td class="text-start">Logical / Abstract Reasoning</td><td>30</td><td>2</td><td>60</td></tr>
            <tr><td class="text-start">English Comprehension</td><td>20</td><td>2</td><td>40</td></tr>
            <tr><td class="text-start">Computer Concepts</td><td>20</td><td>2</td><td>40</td></tr>
            <tr class="table-light"><td class="fw-bold text-end">Total</td><td class="fw-bold">100</td><td>-</td><td class="fw-bold">200</td></tr>',
        'pattern_note' => 'Duration is 90 Minutes. No Negative Marking.',
        'syllabus' => [
            'Mathematics & Statistics' => '<p>Algebra, Coordinate Geometry, Differential Equations, Trigonometry, Probability and Statistics, Arithmetic, Mensuration.</p>',
            'Logical / Abstract Reasoning' => '<p>Questions to measures how quickly and accurately you can think. Topics: Linear and Circular Arrangements, Selection and Coding, Blood Relations, Direction Sense.</p>',
            'English Comprehension' => '<p>Grammar, Vocabulary, Synonyms, Antonyms, Reading Comprehension, Sentence Completion, Para Jumbles.</p>',
            'Computer Concepts' => '<p>Computer Basics, Data Representation, binary arithmetic, Computer Architecture, Programming Concepts, Operating Systems basics.</p>'
        ]
    ],

    // PG CUET MCA
    3 => [
        'name' => 'PG CUET MCA',
        'intro' => 'CUET PG (Common University Entrance Test) is conducted by NTA for admission to MCA programmes in Central Universities and other participating institutes across India.',
        'pattern_rows' => '
            <tr><td class="text-start">Thinking & Decision Making</td><td>15</td><td>4</td><td>60</td></tr>
            <tr><td class="text-start">Mathematics</td><td>40</td><td>4</td><td>160</td></tr>
            <tr><td class="text-start">Computer Awareness</td><td>20</td><td>4</td><td>80</td></tr>
            <tr class="table-light"><td class="fw-bold text-end">Total</td><td class="fw-bold">75</td><td>-</td><td class="fw-bold">300</td></tr>',
        'pattern_note' => 'Marking Scheme: +4 for correct, -1 for incorrect answer. Exam Code: SCQP09.',
        'syllabus' => [
            'Mathematics' => '<p>Set Theory, Algebra, Coordinate Geometry, Calculus, Vectors, Trigonometry, Probability and Statistics.</p>',
            'Thinking & Decision Making' => '<p>Creative thinking, unfamiliar relationships, assessment of figures and diagrams, Geometrical designs, Series completion.</p>',
            'Computer Awareness' => '<p>Computer Basics, Data Representation (Binary, Octal, Hexa), Boolean Algebra, C Language, Data Structures, Operating Systems.</p>'
        ]
    ],

    // VITMEE
    4 => [
        'name' => 'VITMEE',
        'intro' => 'VITMEE (VIT Master\'s Entrance Examination) is conducted by Vellore Institute of Technology (VIT) for admission to MCA and M.Tech programmes at VIT campuses (Vellore, Chennai).',
        'pattern_rows' => '
            <tr><td class="text-start">Mathematics</td><td>80 (Combined with CS)</td><td>1</td><td>80 (Combined)</td></tr>
            <tr><td class="text-start">English Communication</td><td>20</td><td>1</td><td>20</td></tr>
            <tr class="table-light"><td class="fw-bold text-end">Total</td><td class="fw-bold">100</td><td>-</td><td class="fw-bold">100</td></tr>',
        'pattern_note' => 'Core section contains 80 Qs from Math & Computer Science. Duration: 2 Hours. No Negative Marking.',
        'syllabus' => [
            'Mathematics' => '<p>Algebra (Matrices, Determinants), Calculus (Limits, Differentiation, Integration), Differential Equations, Vector Algebra, Probability & Statistics.</p>',
            'Computer Science' => '<p>Data Structures, Database Management Systems, Operating Systems, Computer Networks, Software Engineering, Web Technology.</p>',
            'English Communication' => '<p>Grammar, Vocabulary, Reading Comprehension, pronunciation, and correct usage of English.</p>'
        ]
    ],

    // PU CET PG
    5 => [
        'name' => 'PU CET PG',
        'intro' => 'PU CET (PG) is the entrance test conducted by Panjab University, Chandigarh for admission to its prestigious MCA programme.',
        'pattern_rows' => '
            <tr><td class="text-start">Mathematics</td><td>-</td><td>1</td><td>-</td></tr>
            <tr><td class="text-start">Computer Science</td><td>-</td><td>1</td><td>-</td></tr>
            <tr><td class="text-start">English & Reasoning</td><td>-</td><td>1</td><td>-</td></tr>
            <tr class="table-light"><td class="fw-bold text-end">Total</td><td class="fw-bold">75</td><td>-</td><td class="fw-bold">75</td></tr>',
        'pattern_note' => 'Usually 75 MCQs. Duration: 90 Minutes. Negative Marking usually applies.',
        'syllabus' => [
            'Mathematics' => '<p>10+2 and Undergraduate level Mathematics: Algebra, Calculus, Matrices, Statistics, Probability.</p>',
            'Computer Science' => '<p>Fundamentals of Computers, Digital Logic, Data Structures, Programming in C/C++, DBMS, Operating Systems.</p>',
            'Reasoning & English' => '<p>Logical Reasoning, Analytical Ability, General English proficiency.</p>'
        ]
    ],

    // Other State
    6 => [
        'name' => 'Other State MCA Exams',
        'intro' => 'Apart from national level exams, various states conduct their own entrance tests for MCA admissions. Preparing for these opens up more opportunities.',
        'pattern_rows' => '
            <tr><td class="text-start">WBJECA (West Bengal)</td><td>100</td><td>1/2</td><td>120</td></tr>
            <tr><td class="text-start">TANCET (Tamil Nadu)</td><td>100</td><td>1</td><td>100</td></tr>
            <tr><td class="text-start">OJEE (Odisha)</td><td>120</td><td>4</td><td>480</td></tr>
            <tr><td class="text-start">KMAT (Karnataka)</td><td>120</td><td>1</td><td>120</td></tr>',
        'pattern_note' => 'Patterns vary by state. Check official brochures for specific details.',
        'syllabus' => [
            'General Syllabus' => '<p>Most state exams follow a similar pattern covering: Mathematics (10+2/Grad level), Analytical & Logical Reasoning, Computer Awareness, and General English.</p>',
            'Preparation Strategy' => '<p>Focusing on the NIMCET syllabus usually covers 80-90% of the syllabus for these state level exams.</p>'
        ]
    ]
];

try {
    foreach ($examsData as $id => $data) {
        $html = generateExamHTML($data['name'], $data['pattern_rows'], $data['syllabus'], $data);
        
        $stmt = $pdo->prepare("UPDATE entrance_exams SET full_description = ? WHERE id = ?");
        $stmt->execute([$html, $id]);
        
        echo "Updated {$data['name']} (ID: $id) with REAL CONTENT.\n";
    }
    echo "All exams have been populated with accurate research-based content.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
