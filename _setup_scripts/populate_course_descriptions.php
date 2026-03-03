<?php
require 'database.php';

// Helper for professional HTML content
function getContent($title, $subtitle, $features) {
    $featureList = '';
    foreach ($features as $f) {
        $featureList .= "<li style='margin-bottom: 10px;'><i class='fas fa-check-circle text-primary me-2'></i> $f</li>";
    }

    return <<<HTML
<div style="font-family: 'Outfit', sans-serif;">
    <h3 style="color: #1C56E1; font-weight: 600; margin-bottom: 20px;">About the $title</h3>
    <p style="font-size: 1.1rem; color: #555; line-height: 1.8; margin-bottom: 30px;">
        The <strong>$title</strong> ($subtitle) is meticulously designed by Infomaths to ensure success in top MCA entrance exams like NIMCET, CUET, and MAHCET.
        Our curriculum blends conceptual clarity with rigorous practice, ensuring you stay ahead of the competition.
    </p>

    <div class="row mb-5">
        <div class="col-lg-12">
            <h4 style="font-weight: 600; margin-bottom: 15px;">Key Highlights</h4>
            <ul style="list-style: none; padding-left: 0;">
                $featureList
            </ul>
        </div>
    </div>

    <div class="alert alert-light border" role="alert">
        <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i> Join Today!</h5>
        <p class="mb-0">Admissions are open. <a href="#" class="open-contact-modal fw-bold text-decoration-none" style="color: #1C56E1;">Contact us</a> or visit our center to enroll in this course.</p>
    </div>
</div>
HTML;
}

$coursesContent = [
    'adwintage' => [
        'title' => 'adWINtage',
        'subtitle' => '1-Year Integrated Regular Batch',
        'features' => [
            'Daily Classroom Lectures covering Mathematics & Reasoning.',
            'Comprehensive Study Material and Assignments.',
            'Regular Weekly Tests and Performance Analysis.',
            'Doubt Clearing Sessions with Expert Faculty.',
            'Focus on NIMCET and Other Top MCA Exams.'
        ]
    ],
    'weekender' => [
        'title' => 'Weekender',
        'subtitle' => '1-Year Integrated (Weekends) for Outstation Students',
        'features' => [
            'Intensive Weekend Classes for Working Professionals/Students.',
            'Complete Syllabus Coverage on Saturdays & Sundays.',
            'Recorded backup of missed lectures.',
            'Online Test Series integration.',
            'Special focus on Time Management strategies.'
        ]
    ],
    'marathon' => [
        'title' => 'Marathon',
        'subtitle' => '2-Year Integrated Batch for 2nd Year Students',
        'features' => [
            'Ideal for 2nd Year BCA/B.Sc. students.',
            'Slow-paced, concept-building approach in the first year.',
            'Advanced problem solving in the second year.',
            'Foundation strengthening in Mathematics.',
            'Long-term mentorship program.'
        ]
    ],
    'super-marathon' => [
        'title' => 'Super-Marathon',
        'subtitle' => '3-Year Integrated Batch for 1st Year Students',
        'features' => [
            'Early start advantage for 1st Year under-grads.',
            'Gradual progression from basics to advanced levels.',
            'Parallel preparation with college curriculum.',
            'Consistent practice habits built over 3 years.',
            'Zero pressure learning environment.'
        ]
    ],
    'target-course' => [
        'title' => 'Target Course',
        'subtitle' => 'Short Term Crash Course (60-90 Days)',
        'features' => [
            'Fast-track syllabus coverage.',
            'Daily Mock Tests and Rapid Revision.',
            'Focus on High-Weightage Topics.',
            'Previous Year Question Papers solving.',
            'Designed for final revision before exams.'
        ]
    ],
    'pigeon-a' => [
        'title' => 'Pigeon A',
        'subtitle' => 'Correspondence Study Material Batch',
        'features' => [
            'Complete Set of Infomaths Study Material delivered to your doorstep.',
            'Topic-wise Theory Books and Practice Question Banks.',
            'Access to Online Test Series.',
            'Email doubt support.',
            'Self-paced learning guide included.'
        ]
    ],
    'online-course' => [
        'title' => 'Online Course',
        'subtitle' => 'On Android App COURSEDU',
        'features' => [
            'Access lectures anywhere, anytime via COURSEDU App.',
            'Live Classes and Valid Recorded Backup.',
            'Digital Study Material.',
            'Online Tests with Instant Result Analysis.',
            'Chat support for doubt resolution.'
        ]
    ],
    'hybrid-course' => [
        'title' => 'Hybrid Course',
        'subtitle' => 'Flexible Combination of Online & Offline Learning',
        'features' => [
            'Best of both worlds: Attend Offline or Online as per convenience.',
            'Physical Study Material provided.',
            'Attend weekend offline sessions for doubts.',
            'Full access to Online App resources.',
            'Flexible schedule for college students.'
        ]
    ]
];

try {
    $stmt = $pdo->prepare("UPDATE course_profiles SET description = ? WHERE slug = ?");

    foreach ($coursesContent as $slug => $data) {
        $html = getContent($data['title'], $data['subtitle'], $data['features']);
        $stmt->execute([$html, $slug]);
        echo "Updated description for: $slug\n";
    }
    echo "All course descriptions populated successfully.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
