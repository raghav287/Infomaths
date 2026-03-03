<!-- Modern Contact Us Modal -->
<style>
    #contactPopupModal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        overflow: hidden;
        font-family: 'Outfit', sans-serif;
    }
    #contactPopupModal .modal-header {
        background: linear-gradient(135deg, #1C56E1, #0d3aa9);
        color: white;
        border-bottom: none;
        padding: 24px 32px;
    }
    #contactPopupModal .modal-title {
        font-weight: 700;
        font-size: 1.5rem;
    }
    #contactPopupModal .btn-close-white {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }
    #contactPopupModal .modal-body {
        padding: 32px;
        background-color: #f9fbff;
    }
    #contactPopupModal .form-label {
        font-weight: 600;
        color: #444;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    #contactPopupModal .form-control, 
    #contactPopupModal .form-select {
        border-radius: 8px;
        border: 1px solid #e1e5ee;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background-color: #fff;
    }
    #contactPopupModal .form-control:focus, 
    #contactPopupModal .form-select:focus {
        border-color: #1C56E1;
        box-shadow: 0 0 0 4px rgba(28, 86, 225, 0.1);
        outline: none;
    }
    #contactPopupModal .btn-primary {
        background: linear-gradient(135deg, #1C56E1, #0d3aa9);
        border: none;
        border-radius: 8px;
        padding: 14px;
        font-weight: 600;
        font-size: 1rem;
        letter-spacing: 0.5px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    #contactPopupModal .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(28, 86, 225, 0.3);
    }
    #contactPopupModal .btn-primary:active {
        transform: translateY(0);
    }
</style>

<div class="modal fade" id="contactPopupModal" tabindex="-1" aria-labelledby="contactPopupLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Changed to lg for better layout -->
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title text-white" id="contactPopupLabel">Admissions are open.</h5>
                    <p class="mb-0 text-white" style="font-size: 0.9rem;">Contact us or visit our center to enroll in this course.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="popupContactForm" class="unified-contact-form">
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
                            <label class="form-label">Course of Interest <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="course_interest" placeholder="E.g. MCA Entrance, Bank PO, etc." required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Your Message (Optional)</label>
                            <textarea class="form-control" name="enquiry" rows="3" placeholder="Tell us about your requirements..."></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Request Callback <i class="fas fa-arrow-right ms-2"></i></button>
                            </div>
                        </div>
                    </div>
                    <div id="popupFormMessage" class="mt-3 text-center fw-bold" style="display:none;"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var checkJquery = setInterval(function() {
        if (window.jQuery && window.jQuery.fn.modal) {
            clearInterval(checkJquery);
            (function($) {
                // 1. Open Modal on Click
                console.log('Attaching click listener to .open-contact-modal');
                $(document).on('click', '.open-contact-modal', function(e) {
                    console.log('Open Contact Modal clicked');
                    e.preventDefault();
                    console.log('Attempting to show modal #contactPopupModal');
                    $('#contactPopupModal').modal('show');
                });

                // 2. Handle Form Submission
                $('#popupContactForm').on('submit', function(e) {
                    e.preventDefault();
                    
                    const form = $(this);
                    const submitBtn = form.find('button[type="submit"]');
                    const msgDiv = $('#popupFormMessage');
                    const originalText = submitBtn.html();

                    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Sending...');
                    msgDiv.hide().removeClass('text-success text-danger');

                    $.ajax({
                        url: 'submit_form.php',
                        type: 'POST',
                        data: form.serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                msgDiv.addClass('text-success').html('<i class="fas fa-check-circle me-1"></i> ' + response.message).show();
                                form[0].reset();
                                setTimeout(() => {
                                    $('#contactPopupModal').modal('hide');
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
