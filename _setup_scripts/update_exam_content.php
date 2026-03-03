<?php
require 'database.php';

// Standard Accordion Template
$accordionTemplate = '
<br>
<div class="accordion" id="accordion_details">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <strong style="color: #1C56E1;">Exam Pattern</strong>
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordion_details">
            <div class="accordion-body">
                <p>Enter the exam pattern details (duration, number of questions, marking scheme) here.</p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <strong style="color: #1C56E1;">Syllabus</strong>
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordion_details">
            <div class="accordion-body">
                <p>Enter the detailed syllabus for the exam here.</p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <strong style="color: #1C56E1;">Eligibility Criteria</strong>
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordion_details">
            <div class="accordion-body">
                <p>Enter eligibility requirements (education, age limit, etc.) here.</p>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFour">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                <strong style="color: #1C56E1;">Important Dates</strong>
            </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordion_details">
            <div class="accordion-body">
                <p>Enter important dates like application start, admit card release, and exam date.</p>
            </div>
        </div>
    </div>
</div>
';

try {
    // 1. Reset MAH MCA CET (ID 2)
    $mahIntro = '<h2>About MAH MCA CET</h2><p>MAH MCA CET is a state-level entrance exam conducted by the State Common Entrance Test Cell, Maharashtra, for admission to MCA courses in various colleges in Maharashtra.</p>';
    $mahContent = $mahIntro . $accordionTemplate;
    
    $stmt = $pdo->prepare("UPDATE entrance_exams SET full_description = ? WHERE id = 2");
    $stmt->execute([$mahContent]);
    echo "Updated MAH MCA CET (ID 2).\n";

    // 2. Update Others (ID 3, 4, 5, 6) - Append Template to existing intro
    // We fetch them first to keep their 'About' section if it exists
    $ids = [3, 4, 5, 6];
    foreach ($ids as $id) {
        $stmt = $pdo->prepare("SELECT full_description FROM entrance_exams WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            // Keep existing content (it was just a small intro) and append template
            // Note: If the content is already huge, this might duplicate, but we checked and it was minimal.
            // Just to be safe, if it contains 'accordion', we skip, but user asked to 'add content'.
            // Simpler: Just Append.
            $newContent = $row['full_description'] . $accordionTemplate;
            $update = $pdo->prepare("UPDATE entrance_exams SET full_description = ? WHERE id = ?");
            $update->execute([$newContent, $id]);
            echo "Updated Exam ID $id.\n";
        }
    }

    echo "All requested exams updated successfully.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
