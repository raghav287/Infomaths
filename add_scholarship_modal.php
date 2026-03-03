<!-- Scholarship Application Modal -->
<style>
    #scholarshipModal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        overflow: hidden;
        font-family: 'Outfit', sans-serif;
    }
    #scholarshipModal .modal-header {
        background: linear-gradient(135deg, #1C56E1, #0d3aa9);
        color: white;
        border-bottom: none;
        padding: 24px 32px;
    }
    #scholarshipModal .modal-title {
        font-weight: 700;
        font-size: 1.5rem;
    }
    #scholarshipModal .btn-close-white {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }
    #scholarshipModal .modal-body {
        padding: 32px;
        background-color: #f9fbff;
    }
    #scholarshipModal .form-label {
        font-weight: 600;
        color: #444;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    #scholarshipModal .form-control {
        border-radius: 8px;
        border: 1px solid #e1e5ee;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    #scholarshipModal .form-control:focus {
        border-color: #1C56E1;
        box-shadow: 0 0 0 4px rgba(28, 86, 225, 0.1);
        outline: none;
    }
    #scholarshipModal .btn-primary {
        background: linear-gradient(135deg, #1C56E1, #0d3aa9);
        border: none;
        border-radius: 8px;
        padding: 14px;
        font-weight: 600;
        font-size: 1rem;
        letter-spacing: 0.5px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    #scholarshipModal .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(28, 86, 225, 0.3);
    }
</style>

<div class="modal fade" id="scholarshipModal" tabindex="-1" aria-labelledby="scholarshipLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title text-white" id="scholarshipLabel" style="color: #ffffff !important;">Apply Scholarship for MCA Entrance Exam</h5>
                    <p class="mb-0 text-white" style="font-size: 0.9rem; color: #ffffff !important; opacity: 1;">Fill out the form below to apply for our scholarship program.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="scholarshipForm">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="student_name" placeholder="E.g. John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="student_email" placeholder="john@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="student_mobile" placeholder="10-digit mobile number" pattern="[0-9]{10}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Course Applied For <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="course_interest" value="MCA Entrance Exam" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Qualification <span class="text-danger">*</span></label>
                            <select class="form-control" name="qualification" required>
                                <option value="" selected disabled>Select Qualification</option>
                                <option value="Appearing Graduation">Appearing Graduation</option>
                                <option value="Passed Graduation">Passed Graduation</option>
                                <option value="Post Graduation">Post Graduation</option>
                                <option value="12th Passed">12th Passed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Percentage/CGPA <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="percentage" placeholder="E.g. 85% or 8.5 CGPA" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Additional Message (Optional)</label>
                            <textarea class="form-control" name="enquiry" rows="3" placeholder="Tell us about yourself..."></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Apply for Scholarship <i class="fas fa-paper-plane ms-2"></i></button>
                            </div>
                        </div>
                    </div>
                    <div id="scholarshipMessage" class="mt-3 text-center fw-bold" style="display:none;"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Robust Open Modal Trigger (Vanilla JS to avoid jQuery timing issues)
    document.body.addEventListener('click', function(e) {
        // Handle click on matching element or its children
        var trigger = e.target.closest('.open-scholarship-modal');
        if (trigger) {
            e.preventDefault();
            console.log('Scholarship trigger clicked');
            
            var modalEl = document.getElementById('scholarshipModal');
            if (modalEl) {
                // Try Bootstrap 5 Instance or Create New
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.show();
                } 
                // Fallback to jQuery
                else if (window.jQuery && window.jQuery.fn.modal) {
                    window.jQuery('#scholarshipModal').modal('show');
                } 
                // Fallback: Manually add class (rare casae)
                else {
                    console.error('Bootstrap JS not loaded');
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    document.body.classList.add('modal-open');
                    // Add simple backdrop if missing
                    if (!document.querySelector('.modal-backdrop')) {
                        var backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                        backdrop.addEventListener('click', function() {
                            modalEl.classList.remove('show');
                            modalEl.style.display = 'none';
                            document.body.classList.remove('modal-open');
                            backdrop.remove();
                        });
                    }
                }
            }
        }
    });

    // 2. Handle Form Submission (Waits for jQuery for Ajax convenience)
    var checkJquerySch = setInterval(function() {
        if (window.jQuery) {
            clearInterval(checkJquerySch);
            (function($) {
                $('#scholarshipForm').on('submit', function(e) {
                    e.preventDefault();
                    
                    const form = $(this);
                    const submitBtn = form.find('button[type="submit"]');
                    const msgDiv = $('#scholarshipMessage');
                    const originalText = submitBtn.html();

                    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Processing...');
                    msgDiv.hide().removeClass('text-success text-danger');

                    $.ajax({
                        url: 'submit_scholarship.php',
                        type: 'POST',
                        data: form.serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                msgDiv.addClass('text-success').html('<i class="fas fa-check-circle me-1"></i> ' + response.message).show();
                                form[0].reset();
                                setTimeout(() => {
                                    // Close using the same logic
                                    var modalEl = document.getElementById('scholarshipModal');
                                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                        var modal = bootstrap.Modal.getInstance(modalEl);
                                        if(modal) modal.hide();
                                    } else {
                                        $('#scholarshipModal').modal('hide');
                                    }
                                    msgDiv.hide();
                                }, 3500);
                            } else {
                                msgDiv.addClass('text-danger').html('<i class="fas fa-times-circle me-1"></i> ' + response.message).show();
                            }
                        },
                        error: function() {
                            msgDiv.addClass('text-danger').text('Connection error. Please try again later.').show();
                        },
                        complete: function() {
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    });
                });
            })(window.jQuery);
        }
    }, 100);
});
</script>
