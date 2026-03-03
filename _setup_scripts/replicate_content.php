<?php
require 'database.php';

// The Master Template (Based on NIMCET structure but genericized)
// We use {{EXAM_NAME}} as a placeholder
$masterTemplate = <<<HTML
<div class="exam-details-container" style="font-family: 'Outfit', sans-serif;">

    <!-- Intro Section -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <h2 style="color: #1C56E1; font-weight: 700; margin-bottom: 20px;">
                {{EXAM_NAME}} 2026: Complete Guide
            </h2>
            <p style="font-size: 1.1rem; color: #555; line-height: 1.8;">
                <strong>{{EXAM_NAME}}</strong> is a premier entrance examination for admission to Master of Computer Applications (MCA) programmes. 
                Infomaths offers the most comprehensive coaching and study material to help you crack {{EXAM_NAME}} with top ranks.
            </p>
        </div>
    </div>

    <!-- Exam Pattern Table -->
    <div class="row mb-5">
        <div class="col-lg-12">
             <h3 style="color: #1C56E1; font-weight: 600; margin-bottom: 20px;">
                {{EXAM_NAME}} Exam Pattern
            </h3>
            <table class="table table-bordered table-striped text-center align-middle" style="box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                <thead class="table-dark">
                    <tr>
                        <th style="padding: 15px;">Subject</th>
                        <th style="padding: 15px;">No. of Questions</th>
                        <th style="padding: 15px;">Marks per Question</th>
                        <th style="padding: 15px;">Total Marks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-start" style="font-weight: 600; color: #1C56E1;">Mathematics</td>
                        <td>50</td>
                        <td>4</td>
                        <td>200</td>
                    </tr>
                    <tr>
                        <td class="text-start" style="font-weight: 600; color: #1C56E1;">Analytical & Logical Reasoning</td>
                        <td>40</td>
                        <td>4</td>
                        <td>160</td>
                    </tr>
                     <tr>
                        <td class="text-start" style="font-weight: 600; color: #1C56E1;">Computer Awareness</td>
                        <td>10</td>
                        <td>4</td>
                        <td>40</td>
                    </tr>
                    <tr>
                        <td class="text-start" style="font-weight: 600; color: #1C56E1;">General English</td>
                        <td>20</td>
                        <td>4</td>
                        <td>80</td>
                    </tr>
                    <tr class="table-light">
                        <td colspan="3" class="text-end fw-bold">Grand Total</td>
                        <td class="fw-bold fs-5">480</td>
                    </tr>
                </tbody>
            </table>
            <div class="alert alert-light border mt-3" role="alert">
                <small><i class="fas fa-info-circle text-primary me-2"></i> <strong>Note:</strong> The above pattern is indicative. Please verify with the official {{EXAM_NAME}} 2026 brochure.</small>
            </div>
        </div>
    </div>

    <!-- Accordion Section -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <h3 style="color: #1C56E1; font-weight: 600; margin-bottom: 25px;">
                Complete Syllabus & Details
            </h3>
            
            <div class="accordion" id="accordion_{{EXAM_SLUG}}">
            
                <!-- Syllabus Item -->
                <div class="accordion-item" style="border: 1px solid #e0e0e0; margin-bottom: 10px; border-radius: 8px; overflow: hidden;">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="background-color: #f8f9fa; color: #1C56E1; font-weight: 600;">
                            1. Mathematics Syllabus
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordion_{{EXAM_SLUG}}">
                        <div class="accordion-body" style="background: #fff; padding: 20px;">
                            <ul style="list-style-type: none; padding-left: 0;">
                                <li style="margin-bottom: 10px;"><i class="fas fa-check-circle text-success me-2"></i> <strong>Set Theory:</strong> Concept of sets, Union, Intersection, Cardinality.</li>
                                <li style="margin-bottom: 10px;"><i class="fas fa-check-circle text-success me-2"></i> <strong>Probability & Statistics:</strong> Basic concepts, Averages, Distributions.</li>
                                <li style="margin-bottom: 10px;"><i class="fas fa-check-circle text-success me-2"></i> <strong>Algebra:</strong> Fundamental operations, Expansions, Factorization.</li>
                                <li style="margin-bottom: 10px;"><i class="fas fa-check-circle text-success me-2"></i> <strong>Calculus:</strong> Limit of functions, Differentiation, Integration.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Logical Reasoning Item -->
                <div class="accordion-item" style="border: 1px solid #e0e0e0; margin-bottom: 10px; border-radius: 8px; overflow: hidden;">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="background-color: #f8f9fa; color: #1C56E1; font-weight: 600;">
                            2. Analytical Ability & Logical Reasoning
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordion_{{EXAM_SLUG}}">
                         <div class="accordion-body" style="background: #fff; padding: 20px;">
                            <p>Questions in this section will cover logical situations and questions based on the facts given in the passage.</p>
                            <ul style="list-style-type: none; padding-left: 0;">
                                <li style="margin-bottom: 8px;"><i class="fas fa-angle-right text-primary me-2"></i> Logical Puzzles</li>
                                <li style="margin-bottom: 8px;"><i class="fas fa-angle-right text-primary me-2"></i> Venn Diagrams</li>
                                <li style="margin-bottom: 8px;"><i class="fas fa-angle-right text-primary me-2"></i> Coding and Decoding</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Computer Awareness Item -->
                <div class="accordion-item" style="border: 1px solid #e0e0e0; margin-bottom: 10px; border-radius: 8px; overflow: hidden;">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="background-color: #f8f9fa; color: #1C56E1; font-weight: 600;">
                            3. Computer Awareness
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordion_{{EXAM_SLUG}}">
                         <div class="accordion-body" style="background: #fff; padding: 20px;">
                            <p><strong>Computer Basics:</strong> CPU, Input/Output devices, Memory, Backup devices.</p>
                            <p><strong>Data Representation:</strong> Binary, Hexadecimal, Boolean Algebra, Truth Tables.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="alert alert-primary d-flex align-items-center" role="alert" style="background-color: #e7f1ff; border-color: #b6d4fe;">
                <i class="fas fa-graduation-cap fa-2x me-3" style="color: #0d6efd;"></i>
                <div>
                   <strong>Ready to Crack {{EXAM_NAME}} 2026?</strong>
                   <a href="#" class="alert-link">Join Infomaths Today</a> for expert coaching, mock tests, and comprehensive study material.
                </div>
            </div>
        </div>
    </div>

</div>
HTML;

try {
    // List of exams to update (ID 2 to 6)
    // ID 1 already has the master content (mostly)
    $exams = [
        2 => 'MAH MCA CET',
        3 => 'PG CUET MCA',
        4 => 'VITMEE',
        5 => 'PU CET PG',
        6 => 'Other State MCA Exams'
    ];

    foreach ($exams as $id => $name) {
        $slug = strtolower(str_replace(' ', '_', $name));
        
        // Personalize the template
        $content = str_replace('{{EXAM_NAME}}', $name, $masterTemplate);
        $content = str_replace('{{EXAM_SLUG}}', $slug, $content);

        // Update DB
        $stmt = $pdo->prepare("UPDATE entrance_exams SET full_description = ? WHERE id = ?");
        $stmt->execute([$content, $id]);
        
        echo "Updated $name (ID: $id)\n";
    }
    
    echo "All exams successfully replicated with NIMCET style structure.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
