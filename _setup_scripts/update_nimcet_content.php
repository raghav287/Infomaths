<?php
require 'database.php';

try {
    $nimcetContent = '
    <div class="exam-details">
        <h2 style="color: #1C56E1; margin-bottom: 20px;">NIMCET 2026: Complete Guide</h2>
        
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Exam Overview</h4>
                <p>NIMCET (NIT MCA Common Entrance Test) is a National Level Entrance Examination conducted by NITs for admission to their Master of Computer Applications (MCA) programme. <strong>NIMCET 2026</strong> is the gateway to premier NITs including NIT Agartala, Allahabad, Bhopal, Jamshedpur, Kurukshetra, Raipur, Surathkal, Tiruchirappalli, and Warangal.</p>
            </div>
        </div>

        <h3 class="mt-4 mb-3">NIMCET 2026 Exam Pattern</h3>
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Subject</th>
                    <th>No. of Questions</th>
                    <th>Marks per Question</th>
                    <th>Total Marks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-start"><strong>Mathematics</strong></td>
                    <td>50</td>
                    <td>12</td>
                    <td>600</td>
                </tr>
                <tr>
                    <td class="text-start"><strong>Analytical Ability & Logical Reasoning</strong></td>
                    <td>40</td>
                    <td>6</td>
                    <td>240</td>
                </tr>
                <tr>
                    <td class="text-start"><strong>Computer Awareness</strong></td>
                    <td>10</td>
                    <td>6</td>
                    <td>60</td>
                </tr>
                <tr>
                    <td class="text-start"><strong>General English</strong></td>
                    <td>20</td>
                    <td>4</td>
                    <td>80</td>
                </tr>
                <tr class="table-primary">
                    <td class="text-start fw-bold">Total</td>
                    <td class="fw-bold">120</td>
                    <td>-</td>
                    <td class="fw-bold">980</td>
                </tr>
            </tbody>
        </table>
        <p class="text-muted"><small>* Negative Marking: 25% of the marks allotted to a question will be deducted for each wrong answer.</small></p>

        <h3 class="mt-5 mb-3">Complete Syllabus</h3>
        
        <div class="accordion" id="syllabusAccordion">
            <!-- Mathematics -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingMath">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMath" aria-expanded="true" aria-controls="collapseMath">
                        <strong style="color: #1C56E1;">1. Mathematics (50 Questions)</strong>
                    </button>
                </h2>
                <div id="collapseMath" class="accordion-collapse collapse show" aria-labelledby="headingMath" data-bs-parent="#syllabusAccordion">
                    <div class="accordion-body">
                        <ul>
                            <li><strong>Set Theory:</strong> Concept of sets – Union, Intersection, Cardinality, Elementary counting; permutations and combinations.</li>
                            <li><strong>Probability and Statistics:</strong> Basic concepts of probability theory, Averages, Dependent and independent events, frequency distributions, measures of central tendencies and dispersions.</li>
                            <li><strong>Algebra:</strong> Fundamental operations in algebra, expansions, factorization, simultaneous linear / quadratic equations, indices, logarithms, arithmetic, geometric and harmonic progressions, determinants and matrices.</li>
                            <li><strong>Coordinate Geometry:</strong> Rectangular Cartesian coordinates, distance formulae, equation of a line, and intersection of lines, pair of straight lines, equations of a circle, parabola, ellipse and hyperbola.</li>
                            <li><strong>Calculus:</strong> Limit of functions, continuous function, differentiation of function, tangents and normals, simple examples of Maxima and Minima. Integration of functions by parts, by substitution and by partial fraction, definite integrals, applications of definite integrals to areas.</li>
                            <li><strong>Vectors:</strong> Position vector, addition and subtraction of vectors, scalar and vector products and their applications to simple geometrical problems and mechanics.</li>
                            <li><strong>Trigonometry:</strong> Simple identities, trigonometric equations, properties of triangles, solution of triangles, heights and distances, general solutions of trigonometric equations.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Analytical Ability -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingReasoning">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReasoning" aria-expanded="false" aria-controls="collapseReasoning">
                        <strong style="color: #1C56E1;">2. Analytical Ability & Logical Reasoning (40 Questions)</strong>
                    </button>
                </h2>
                <div id="collapseReasoning" class="accordion-collapse collapse" aria-labelledby="headingReasoning" data-bs-parent="#syllabusAccordion">
                    <div class="accordion-body">
                        <p>The questions in this section will cover logical situations and questions based on the facts given in the passage.</p>
                        <ul>
                            <li>Logical Puzzles</li>
                            <li>Venn Diagrams</li>
                            <li>Coding and Decoding</li>
                            <li>Series and Pattern Completion</li>
                            <li>Blood Relations</li>
                            <li>Direction Sense</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Computer Awareness -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingComputer">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseComputer" aria-expanded="false" aria-controls="collapseComputer">
                        <strong style="color: #1C56E1;">3. Computer Awareness (10 Questions)</strong>
                    </button>
                </h2>
                <div id="collapseComputer" class="accordion-collapse collapse" aria-labelledby="headingComputer" data-bs-parent="#syllabusAccordion">
                    <div class="accordion-body">
                        <ul>
                            <li><strong>Computer Basics:</strong> Organization of a computer, Central Processing Unit (CPU), structure of instructions in CPU, input/output devices, computer memory, and back-up devices.</li>
                            <li><strong>Data Representation:</strong> Representation of characters, integers and fractions, binary and hexadecimal representations, binary arithmetic: addition, subtraction, multiplication, division, simple arithmetic and two’s complement arithmetic, floating point representation of numbers, Boolean algebra, truth tables, Venn diagrams.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- English -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingEnglish">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEnglish" aria-expanded="false" aria-controls="collapseEnglish">
                        <strong style="color: #1C56E1;">4. General English (20 Questions)</strong>
                    </button>
                </h2>
                <div id="collapseEnglish" class="accordion-collapse collapse" aria-labelledby="headingEnglish" data-bs-parent="#syllabusAccordion">
                    <div class="accordion-body">
                        <p>Questions in this section will be designed to test the candidates’ general understanding of the English language.</p>
                        <ul>
                            <li>Comprehension</li>
                            <li>Vocabulary</li>
                            <li>Basic English Grammar (verbs, prepositions, etc.)</li>
                            <li>Synonyms and Antonyms</li>
                            <li>Word Power & Meaning</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="alert alert-success mt-4">
            <strong>Ready to Crack NIMCET 2026?</strong> <a href="#" class="alert-link">Join Infomaths Today</a> for expert coaching and comprehensive study material.
        </div>
    </div>';

    // Update the existing NIMCET record
    // We assume the slug is 'nimcet' or the name is 'NIMCET' from previous step
    $stmt = $pdo->prepare("UPDATE entrance_exams SET 
        exam_name = ?, 
        slug = ?, 
        short_description = ?, 
        full_description = ?, 
        meta_title = ?, 
        meta_description = ? 
        WHERE exam_name LIKE 'NIMCET%'");
    
    $stmt->execute([
        'NIMCET 2026', 
        'nimcet-2026', 
        'Explore the official syllabus, pattern, and details for NIMCET 2026 Entrance Exam.',
        $nimcetContent,
        'NIMCET 2026 Syllabus & Exam Pattern - InfoMaths',
        'Official syllabus and exam pattern for NIMCET 2026. Mathematics, Logical Reasoning, Computer Awareness, and English detailed topics.'
    ]);

    echo "Successfully updated NIMCET content to NIMCET 2026.";

} catch (PDOException $e) {
    echo "Error updating content: " . $e->getMessage();
}
?>
